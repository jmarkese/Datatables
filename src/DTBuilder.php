<?php

namespace Markese\Datatables;;
use Illuminate\Http\Request;

interface DTBuilder
{
    public function buildDT(): DatatablesServerSide;
}
