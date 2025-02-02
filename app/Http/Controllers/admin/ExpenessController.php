<?php

namespace App\Http\Controllers\admin;

use App\Models\year;
use App\Models\stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class ExpenessController extends Controller
{
    function index(){
        $years = year::whereDoesntHave('yearcost')->get();
        return view('admin.Expeness.index', compact('years'));
    }

    function storeyearcost(Request $request){
        $array = array(
            "year_id"           => $request->year,
            "installment_count" => $request->installment_count,
        );
        $yearcost_id = DB::table('yearcost')->insertGetId($array);
        $Stages = stage::get();

        foreach ($Stages as $stage) {
            $arr = array(
                "yearcost_id"           => $yearcost_id,
                "stage_id"              => $stage->id,
                "book"                  => 0,
                "installment"           => 0,
                "cash"                  => 0
            );
            DB::table('yearcost_stage')->insert($arr);
        }
        return Redirect::back()->with(['success'=>"تم انشاء السنة بنجاح"]);
    }
}
