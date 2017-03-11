<?php

namespace Markese\Datatables;;

use Illuminate\Database\Eloquent\Builder;

class DTBuilderBuilder extends DTBuilderTemplate
{
    protected $obj;

    public function __construct(Builder $queryIn, DTRequest $requestIn, ?string $collectionNameIn = null)
    {
        parent::__construct($requestIn, $collectionNameIn);
        $this->obj = $queryIn;
    }

    protected function search(): void
    {
        $terms = $this->dtRequest->searchTerms;
        $columns = $this->dtRequest->searchColumns;

        $this->obj = \DB::table( \DB::raw("({$this->obj->toSql()}) as q") )
            ->mergeBindings($this->obj)
            ->select('q.*');

        foreach ($terms as $term) {
            $this->obj->where(function ($q) use ($term, $columns) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'LIKE', '%' . $term . '%');
                }
            });
        }
    }

    protected function sort(): void
    {
        $this->obj->orderBy($this->dtRequest->sortCol, $this->dtRequest->sortDir);
    }

    protected function paginate(): void
    {
        $req = $this->dtRequest;
        $this->obj->offset($req->start)->limit($req->length);
    }

    protected function buildResponse(): DatatablesServerSide
    {
        $collection = collect($this->obj->get());
        return new DTResponse($collection, $this->recordsTotal, $this->recordsFiltered, $this->dtRequest->draw, $this->collectionName);
    }
}
