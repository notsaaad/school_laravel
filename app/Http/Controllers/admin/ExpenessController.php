<?php

namespace App\Http\Controllers\admin;

use App\Models\year;
use App\Models\stage;
use App\Models\yearcost;
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

        $systems = DB::table('system')->get();
        foreach ($systems as $system) {
            $system_id = $system->id;

            $array = array(
                "year_id"           => $request->year,
                "installment_count" => $request->installment_count,
                "system_id"         => $system_id
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
                DB::table('yearcost_stages')->insert($arr);
            }
        }

        return redirect()->route('admin.Expenses.installment', $yearcost_id)->with(['success'=> 'تم الاضافة بنجاح']);
    }

    function Handelinstallment(int $yearcost_id){
        $row = DB::table('yearcost')->where('id' ,$yearcost_id )->get();
        $installment_count =  $row[0]->installment_count;
        $year_id = $row[0]->year_id;


        return view('admin.Expeness.installlment', compact('yearcost_id', 'installment_count', 'year_id'));
    }

    function setInstallment(Request $request){
        $data = $request->validate([
            "per" => "required|array",
            "per.*" => "integer"
        ],
        [
            'per.Numeric' => 'يجب ان تكون النسب ارقام',
            'per.*.integer' => 'يجب ان تكون النسب ارقام',

        ]
    );
        $sum = 0;

        $year_id = $request->year_id;
        foreach ($request->per as $per) {
            $sum += $per;
        }

        if($sum != 100){
            return redirect()->back()->with(['error'=> 'يجب ان يكون مجموع النسب 100']);
        }

        $year_ids_row =  DB::table('yearcost')->where('year_id', $year_id)->get();

        foreach ($year_ids_row as $year_id_row) {
            $yearcost_id =$year_id_row->id;
            foreach ($request->per as $per) {
                $arr = array(
                    'per' => $per,
                    'yearcost_id' =>$yearcost_id
                );
                DB::table('installment')->insert($arr);
            }
        }




        return redirect()->route('admin.Expenses.yearcost_stage', $year_id)->with(['success'=> 'تم الاضافة بنجاح']);
    }


    function HandelYearcostStage(int $year_id){
        // $yearcost_stages = DB::table('yearcost_stage')->where('yearcost_id', $yearcost_id)->get();
        // $yearcost = DB::table('yearcost')->where('id' , $yearcost_id)->get();
        // $year_id = $yearcost[0]->year_id;
        // $year = DB::table('years')->where('id' , $year_id)->get();
        // $year_name = $year[0]->name;
        // $yearcost_stages = $yearcost_stages->toArray();
        // $arr = array();
        // for($i=0; $i<count($yearcost_stages); $i++){
        //     $stage = DB::table('stages')->where('id', $yearcost_stages[$i]->stage_id)->get();
        //     $stage_name = $stage[0]->name;
        //     $yearcost_stages[$i]->stage_name = $stage_name;

        // }
        $data = array();
        $yearcosts = DB::table('yearcost')->where('year_id', $year_id)->get();
        foreach ($yearcosts as $yearcost) {
            $arr = array();
            $yearcost_id = $yearcost->id;
            $system_name_row = DB::table('system')->where('id', $yearcost->system_id)->get();
            $system_name = $system_name_row[0]->name;
            $arr['system_name'] = $system_name;
            $stages                = DB::table('yearcost_stages')->where('yearcost_id', $yearcost_id)->get(['id','yearcost_id','book','stage_id', 'cash', 'installment']);
            for($i=0; $i<count($stages); $i++){
                $stage_id = $stages[$i]->stage_id;
                $stage    = DB::table('stages')->where('id', $stage_id)->get(['name']);
                $stage_name = $stage[0]->name;
                $stages[$i]->stage_name = $stage_name;
            }
            $arr['stages']         = $stages;
            array_push($data, $arr);
        }

        return view('admin.Expeness.yearcost_stage', compact('data'));
    }
    function storeyearcostupdate(Request $request){
        return $request;
    }

    function add_new_system(){
        return view('admin.Expeness.addsystem');
    }
    function store_new_system(Request $request){
        DB::table('system')->insert(["name"=>$request->name]);
        return redirect()->route('admin.Expenses.add.system')->with(['success'=>'تم اضافة النظام الدراسي بنجاح']);
    }

    function view_new_system(){
        $systems = DB::table('system')->get();
        return view('admin.Expeness.viewsystem', compact('systems'));
    }

    function Edit_system_from(int $id){
        $systems = DB::table('system')->get()->where('id', $id);
        return view('admin.Expeness.edit_form_system',compact('systems'));
    }

    function Edit_system(Request $request){
        $id = $request->id;
        DB::table('system')->where('id', $id)->update(['name'=>$request->name]);
        return redirect()->route('admin.Expenses.view.system')->with(['success'=>'تم التعديل النظام الدراسي بنجاح']);
    }

    function Delete_system(Request $request){
        $id = $request->id;
        DB::table('system')->where('id', $id)->delete();
        return redirect()->route('admin.Expenses.view.system')->with(['success'=>'تم المسح النظام الدراسي بنجاح']);
    }

}

