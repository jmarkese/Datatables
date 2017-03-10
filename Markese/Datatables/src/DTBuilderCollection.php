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
                continue;
            }
            $this->obj = $this->obj->intersect(
                $this->obj->filter(function ($value, $key) use ($columns, $term) {
                    $search = "";
                    foreach ($columns as $col) {
                        if ( isset( $value->$col ) ) $search .= " " . strtolower($value->$col);
                    }
                    return strpos($search, $term);
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

    protected function buildReponse(): DatatablesServerSide
    {
        return new DTResponse($this->obj, $this->recordsTotal, $this->recordsFiltered, $this->dtRequest->draw, $this->collectionName);
    }

}
