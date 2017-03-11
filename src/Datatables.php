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

        // DTBuilder for Eloquent queries: Illuminate\Database\Eloquent\Builder
        if ($obj instanceof Eloquent) {
            //Collections has better integration features for now, so we'll use that
            //return (new DTBuilderEloquent($obj, $request, $collectionNameIn))->buildDT();
            $obj = $obj->get();
        }

        // DTBuilder for Query Builder queries: Illuminate\Database\Query\Builder
        if ($obj instanceof Builder) {
            //Collections has better integration for now, so we'll use that
            //return (new DTBuilderBuilder($obj, $request, $collectionNameIn))->buildDT();
            $obj = $obj->get();
        }

        // DTBuilder for Laravel collections: Illuminate\Support\Collection
        if ($obj instanceof Collection) {
            return (new DTBuilderCollection($obj, $request, $collectionNameIn))->buildDT();
        }

        throw new \InvalidArgumentException(
            'Expects Illuminate\Database\Eloquent\Builder, Illuminate\Database\Query\Builder, or Illuminate\Support\Collection. Input was: '.get_class($obj)
        );
    }
}
