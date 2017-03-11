<?php

namespace Markese\Datatables;

use Illuminate\Database\Eloquent\Builder;

abstract class DTBuilderTemplate implements DTBuilder
{
    protected $obj;
    protected $dtResponse;
    protected $dtRequest;
    protected $recordsTotal;
    protected $recordsFiltered;
    protected $collectionName;

    public function __construct(DTRequest $requestIn, ?string $collectionNameIn = null)
    {
        $this->dtRequest = $req = $requestIn;
        $this->collectionName = $collectionNameIn;
    }

    abstract protected function search(): void;

    abstract protected function sort(): void;

    abstract protected function paginate(): void;

    abstract protected function buildResponse(): DatatablesServerSide;

    protected function count(): int
    {
        return $this->obj->count();
    }

    final public function buildDT(): DatatablesServerSide
    {
        $this->recordsTotal = $this->count();

        if ($this->dtRequest->search) {
            $this->search();
            $this->recordsFiltered = $this->count();
        } else {
            $this->recordsFiltered = $this->recordsTotal;
        }

        if ($this->dtRequest->sort) {
            $this->sort();
        }

        if ($this->dtRequest->paginate) {
            $this->paginate();
        }

        return $this->buildResponse();
    }
}
