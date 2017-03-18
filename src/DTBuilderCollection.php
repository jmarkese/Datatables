<?php

namespace Markese\Datatables;

use Illuminate\Support\Collection;

/**
 * Class DTBuilderCollection builds a DTResponse instance from a Laravel Collection.
 * @package Markese\Datatables
 */
class DTBuilderCollection extends DTBuilderTemplate
{
    /**
     * @var Collection The Laravel collection that will be adapted to a DTresponse.
     */
    protected $obj;

    /**
     * DTBuilderCollection constructor. Construct a DTBuilder for a Laravel Collection.
     * @param Collection $collectionIn The Laravel collection that will be adapted to a DTresponse.
     * @param DTRequest $requestIn The request data from the client.
     * @param null|string|null $collectionNameIn Optional json "data" attribute for the Datatable to retrieve from.
     */
    public function __construct(Collection $collectionIn, DTRequest $requestIn, ?string $collectionNameIn = null)
    {
        parent::__construct($requestIn, $collectionNameIn);
        $this->obj = $collectionIn;
    }

    /**
     * Case insensitive search algorithm for Laravel Collections. Alters the Collection $obj
     * to include rows that have all of the search terms in any of its columns.
     */
    protected function search(): void
    {
        $terms = $this->dtRequest->searchTerms;
        $columns = $this->dtRequest->searchColumns;

        // Iteravely intersect the Collection with a filtered version of itself.
        foreach ($terms as $term) {

            $this->obj = $this->obj->intersect(

                // Filter by finding the term in the columns values.
                $this->obj->filter(function ($row, $key) use ($term, $columns) {

                    foreach ($columns as $col) {

                        // Check if the column is an attribute or use data_get to retrieve from a nested collection/obj.
                        $data = isset($row->col) ? $row->col : data_get($row, $col);

                        // If the data is an array from a nested collection then concatenate those values.
                        $data = !is_array($data) ? $data : implode(" ", $data);

                        // look for the term in the value data.
                        if ( stristr( $data, $term ) !== false ) {

                            // If the term was found then include this row in the intersection.
                            return true;
                        }
                    }

                    // If the term was not found in any columns then filter this row.
                    return false;
                })
            );
        }
    }

    /**
     * Retrieve the sort direction from the client request and sort the Collection $obj using
     * the appropriate sort method (ascending or descending).
     */
    protected function sort(): void
    {
        $req = $this->dtRequest;
        $this->obj = ($req->sortDir === 'asc') ? $this->obj->sortBy($req->sortCol) : $this->obj->sortByDesc($req->sortCol);
    }

    /**
     * Retrieve the pagination details from the client request and paginate the Collection $obj.
     */
    protected function paginate(): void
    {
        $req = $this->dtRequest;
        $this->obj = $this->obj->forPage($req->page, $req->length)->values();
    }

    /**
     * Build the DTResponse according to the interface's contructor.
     * @return DatatablesServerSide
     */
    protected function buildResponse(): DatatablesServerSide
    {
        return new DTResponse($this->obj, $this->recordsTotal, $this->recordsFiltered, $this->dtRequest->draw, $this->collectionName);
    }

}
