<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 3/4/17
 * Time: 8:30 PM
 */

namespace App\Libs\Datatables;
use Illuminate\Http\Request;

interface DTBuilder
{
    public function buildDT(): DatatablesServerSide;
}
