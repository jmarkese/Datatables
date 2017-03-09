<?php

namespace App\Libs\Datatables;
use Illuminate\Support\Collection;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

class DTResponse implements DatatablesServerSide, Arrayable, JsonSerializable, Jsonable
{
    private $response;
    private $collectionName;

    public function __construct(Collection $collection, int $recordsTotal, int $recordsFiltered, int $draw, ?string $collectionName = null)
    {
        $this->response = collect();
        $this->collectionName = (is_null($collectionName)) ? "data" : $collectionName;
        $this->response->put($this->collectionName, $collection);
        $this->response->put('recordsTotal', $recordsTotal);
        $this->response->put('recordsFiltered', $recordsFiltered);
        $this->response->put('draw', $draw);
    }

    public function adapt(callable $callback): DatatablesServerSide
    {
        $this->response->get($this->collectionName)->each($callback);
        return $this;
    }

    public function toCollection(): Collection
    {
        return $this->response;
    }

    public function toArray ()
    {
        return $this->toCollection()->toArray();
    }

    public function jsonSerialize ()
    {
        return $this->toCollection()->toArray();
    }

    public function toJson ($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

}
