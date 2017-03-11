<?php

namespace Markese\Datatables;

use Illuminate\Support\Collection;

class DTBuilderCollection extends DTBuilderTemplate
{
    protected $obj;

    public function __construct(Collection $collectionIn, DTRequest $requestIn, ?string $collectionNameIn = null)
    {
        parent::__construct($requestIn, $collectionNameIn);
        $this->obj = $collectionIn;
    }

    protected function search(): void
    {
        $terms = $this->dtRequest->searchTerms;
        $columns = $this->dtRequest->searchColumns;

        foreach ($terms as $term) {
            if (empty($term)) {
                //continue;
            }

            $this->obj = $this->obj->intersect(
                $this->obj->filter(function ($value, $key) use ($columns, $term) {
                    foreach ($columns as $col) {
                        $data = isset($value->col) ? $value->col : data_get($value, $col);
                        $data = !is_array($data) ? $data : implode(" ", $data);
                        if ( stristr( $data, $term ) !== false ) {
                            return true;
                        }
                    }
                    return false;
                })
            );
        }
    }

    protected function sort(): void
    {
        $req = $this->dtRequest;
        $this->obj = ($req->sortDir === 'asc') ? $this->obj->sortBy($req->sortCol) : $this->obj->sortByDesc($req->sortCol);
    }

    protected function paginate(): void
    {
        $req = $this->dtRequest;
        $this->obj = $this->obj->forPage($req->page, $req->length)->values();
    }

    protected function buildResponse(): DatatablesServerSide
    {
        return new DTResponse($this->obj, $this->recordsTotal, $this->recordsFiltered, $this->dtRequest->draw, $this->collectionName);
    }

}
