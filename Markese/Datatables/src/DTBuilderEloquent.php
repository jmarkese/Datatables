<?php

namespace App\Libs\Datatables;

use Illuminate\Database\Eloquent\Builder;

class DTBuilderEloquent extends DTBuilderTemplate
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

        $this->obj = ($this->obj->getModel())::selectRaw(' q.* ')
            ->from(\DB::raw(' ( ' . $this->obj->toSql() . ' ) AS q '))
            ->mergeBindings($this->obj->getQuery());

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

    protected function buildReponse(): DatatablesServerSide
    {
        return new DTResponse($this->obj->get(), $this->recordsTotal, $this->recordsFiltered, $this->dtRequest->draw, $this->collectionName);
    }
}
