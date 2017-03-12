<?php

namespace Markese\Datatables;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class Datatables
{
    public static function response ($obj, Request $requestIn, ?string $collectionNameIn = null): DatatablesServerSide
    {
        try {
            $request = new DTRequest($requestIn);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Invalid Datatables Request: ".$e);
        }

        if ($obj instanceof Eloquent) {
            $obj = $obj->get();
        }

        if ($obj instanceof Builder) {
            $obj = $obj->get();
        }

        if ($obj instanceof Collection) {
            return (new DTBuilderCollection($obj, $request, $collectionNameIn))->buildDT();
        }

        throw new \InvalidArgumentException(
            'Expects Illuminate\Database\Eloquent\Builder, Illuminate\Database\Query\Builder, or Illuminate\Support\Collection. Input was: '.get_class($obj)
        );
    }
}
