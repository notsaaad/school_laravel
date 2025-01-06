<?php

namespace App\Http\Controllers\admin\statistics;

use App\Models\year;
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
            $stage_id = $Stages[$i]->id;
            $stage_name = $Stages[$i]->name;
            $Application = application::where('stage_id', $stage_id)->where('year_id', $year_id)->get();
            $applicationFess = applicationFee::where('stage_id', $stage_id)->where('year_id', $year_id)->get();
            $applicationEnrolled = application::where('stage_id', $stage_id)->where('custom_status_id', 7)->where('year_id', $year_id)->get();
            $applicationCanceld = application::where('stage_id', $stage_id)->where('custom_status_id', 10)->get();
            // $applicationDate = applicationData::where('stage_id', $stage_id)->get();
            $arr["stage_id"]                            =  $stage_id;
            $arr["stage_name"]                          =  $stage_name;
            $arr["applications_counter" ]               =  count($Application);
            $arr["applications_Fees_counter" ]          =  count($applicationFess);
            $arr["applications_Enrolled_Counter" ]      =  count($applicationEnrolled);
            $arr["applications_Canceld_Counter" ]       =  count($applicationEnrolled);

            $All[$i] = $arr;

        }

        $years = year::get();



        return view('admin.statistics.index', compact('All', 'years','year_id'));
    }


    public function applicationChar(){

        return "Test";
        return view('admin.statistics.applicationchar');
    }
}
