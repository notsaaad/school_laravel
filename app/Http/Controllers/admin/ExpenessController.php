<?php

namespace App\Http\Controllers\admin;

use App\Models\year;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenessController extends Controller
{
    function index(){
        $years = year::get();
        return get_defined_vars();
    }
}
