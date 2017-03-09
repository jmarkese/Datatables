<?php

namespace App\Libs\Datatables;

use Illuminate\Support\Collection;

interface DatatablesServerSide
{
    public function __construct(Collection $collectionIn, int $recordsTotal, int $recordsFiltered, int $draw, ?string $collectionName = null);

    public function adapt(callable $callback): DatatablesServerSide;

    public function toCollection(): Collection;
}
