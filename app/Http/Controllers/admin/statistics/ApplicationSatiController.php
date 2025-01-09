<?php

namespace App\Http\Controllers\admin\statistics;

use App\Models\year;
use App\Models\place;
use App\Models\stage;
use App\Models\application;
use Illuminate\Http\Request;
use App\Models\applicationFee;
use App\Models\applicationData;
use App\Http\Controllers\Controller;

class ApplicationSatiController extends Controller
{
    public function index(){

        $year_id = 2;

        if(isset($_GET['year'])){
            $year_id = $_GET['year'];
        }
        $All = array();
        $Stages  = stage::get();
        $counter = count($Stages);
        for($i=0; $i<$counter; $i++){
            $arr = array();
            $stage_id               = $Stages[$i]->id;
            $stage_name             = $Stages[$i]->name;
            $Application            = application::where('stage_id', $stage_id)->where('year_id', $year_id)->get();
            $applicationFess        = applicationFee::where('stage_id', $stage_id)->where('year_id', $year_id)->get();
            $applicationEnrolled    = application::where('stage_id', $stage_id)->where('custom_status_id', 7)->where('year_id', $year_id)->get();
            $Parent_interview       = application::where('stage_id', $stage_id)->where('custom_status_id', 11)->where('year_id', $year_id)->get();
            $over_due_date          = application::where('stage_id', $stage_id)->where('custom_status_id', 12)->where('year_id', $year_id)->get();
            $wating_mr              = application::where('stage_id', $stage_id)->where('custom_status_id', 13)->where('year_id', $year_id)->get();
            $Cancel_with_refund     = application::where('stage_id', $stage_id)->where('custom_status_id', 14)->where('year_id', $year_id)->get();
            $Cancel_with_no_refund  = application::where('stage_id', $stage_id)->where('custom_status_id', 15)->where('year_id', $year_id)->get();
            $DidnotFinish           = application::where('stage_id', $stage_id)->where('custom_status_id', 16)->where('year_id', $year_id)->get();
            $watting_list           = application::where('stage_id', $stage_id)->where('custom_status_id', 17)->where('year_id', $year_id)->get();
            $applicationCanceld     = application::where('stage_id', $stage_id)->where('custom_status_id', 10)->get();
            $arr["stage_id"]                            =  $stage_id;
            $arr["stage_name"]                          =  $stage_name;
            $arr["applications_counter" ]               =  count($Application);
            $arr["applications_Fees_counter" ]          =  count($applicationFess);
            $arr["applications_Enrolled_Counter" ]      =  count($applicationEnrolled);
            $arr["Parent_interview" ]                   =  count($Parent_interview);
            $arr["over_due_date" ]                      =  count($over_due_date);
            $arr["wating_mr" ]                          =  count($wating_mr);
            $arr["Cancel_with_refund" ]                 =  count($Cancel_with_refund);
            $arr["Cancel_with_no_refund" ]              =  count($Cancel_with_no_refund);
            $arr["DidnotFinish" ]                       =  count($DidnotFinish);
            $arr["watting_list" ]                       =  count($watting_list);
            $arr["applications_Canceld_Counter" ]       =  count($applicationCanceld);


            $All[$i] = $arr;

        }

        $years = year::get();



        return view('admin.statistics.index', compact('All', 'years','year_id'));
    }


    public function applicationChar(){
<<<<<<< HEAD
        $labels = array();
        $place_id = array();
        $datasets = array();

        $Places = place::get();
        foreach ($Places as $place) {
            array_push($labels, $place['name']);
            array_push($place_id, $place['id']);
        }
        $Stages  = stage::get();

        for($i=0; $i<count($Stages); $i++){
            $data = array();
            $stage_id = $Stages[$i]->id;
            $dateset = array(
                "label" => $Stages[$i]->name,
                "backgroundColor" => "rgb(255, 99, 132)",
                "borderColor" => "rgb(255, 99, 132)",

            );
            for($j=0; $j<count($Places); $j++){
                $place_id = $Places[$j]->id;
                $application = application::where('stage_id', $stage_id)->where('place_id', $place_id)->get();
                $Counter = count($application);
                array_push($data, $Counter);
            }
            $dateset['data'] = $data;
            array_push($datasets , $dateset);


        }



        $data = array(20,30,22);
        $TestData = array(
            [
                "label" => "kg1",
                "backgroundColor" => "rgb(255, 99, 132)",
                "borderColor" => "rgb(255, 99, 132)",
                "data" => array(10,20,30,40)
            ],
            [
                "label" => "kg2",
                "backgroundColor" => "rgb(255, 99, 132)",
                "borderColor" => "rgb(255, 99, 132)",
                "data" => array(30,40,22,23)
            ],
            [
                "label" => "prim1",
                "backgroundColor" => "rgb(255, 99, 132)",
                "borderColor" => "rgb(255, 99, 132)",
                "data" => array(22,10,50,13)
            ],
        );

        return view('admin.statistics.applicationchar', compact('data', 'labels', 'datasets'));
=======
        $labels = array('Test1', "test2", "test3");
        $data = array(20,30,22);

        return view('admin.statistics.applicationchar', compact('data', 'labels'));
>>>>>>> 32f3f66 (try pull)
    }
}
