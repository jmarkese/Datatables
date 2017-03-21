<?php

namespace Markese\Datatables;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Datatables
 * @package Markese\Datatables
 */
class Datatables
{
    /**
     * @param $obj
     * @param Request $requestIn
     * @param null|string|null $collectionNameIn
     * @return DatatablesServerSide
     */
    public static function response ($obj, Request $requestIn, ?string $collectionNameIn = null): DatatablesServerSide
    {
        try {
            $request = new DTRequest($requestIn);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Invalid Datatables Request: ".$e);
        }

        if ($obj instanceof Eloquent) {
            $obj = self::eagerLoadEloquent($obj, $request);
        }

        if ($obj instanceof Builder) {
            $obj = $obj->get();
        }

        if ($obj instanceof Collection) {
            return (new DTBuilderCollection($obj, $request, $collectionNameIn))->buildDT();
        }

        throw new \InvalidArgumentException(
            'Expects Illuminate\Database\Eloquent\Builder, Illuminate\Database\Query\Builder, '.
            'or Illuminate\Support\Collection. Input was: '.get_class($obj)
        );
    }

    /**
     * @param Eloquent $obj
     * @param DTRequest $request
     * @return Collection
     */
    private static function eagerLoadEloquent(Eloquent $obj, DTRequest $request): Collection
    {
        foreach($request->columns as $col){
            $relation = collect(explode('.', $col));
            $cnt = $relation->count();
            if($cnt > 1){
                $load = $relation->filter(function($val, $key) use ($cnt) {
                    return ($val !== '*' && $key !== $cnt - 1);
                })->implode('.');
                $obj->with($load);
            }
        }
        return $obj->get();
    }
}
