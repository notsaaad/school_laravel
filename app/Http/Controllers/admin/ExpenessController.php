<?php

namespace App\Http\Controllers\admin;

use App\Models\year;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenessController extends Controller
{
    function index(){
        $years = year::whereDoesntHave('yearcost')->get();
        // return get_defined_vars();
        return view('admin.Expeness.index', get_defined_vars());
    }
}
