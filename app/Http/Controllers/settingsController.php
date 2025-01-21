<?php

namespace App\Http\Controllers;

use App\Models\year;
use App\Models\email;
use App\Models\setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class settingsController extends Controller
{
    public function index()
    {
        $current_year = setting::where('key', 'year')->first();

        $value = $current_year->value;
        $years        = year::get();
        $current_year = "";
        foreach($years as $year){
            if($year->id == $value){
                $year->currrent = "yes";
                $current_year = $year->name;
            }else{
                $year->current = "no";
            }
        }


        return view("admin/settings/index", compact('years', 'current_year'));
    }
    function changeYear(Request $request){
        $year = $request->year;
        DB::table('settings')->where('key', 'year')->update(['value'=>$year]);
        return redirect()->back()->with(['success'=>'تم تغير السنة بنجاح']);
    }

    function update(Request $request)
    {
        $data = $request->validate([
            "logo" => "nullable|image",
            "fav" => "nullable|image",
            "website_ar_name" => "required|string",
            "website_en_name" => "required|string",
            "meta_description_ar" => "nullable|string",
            "meta_description_en" => "nullable|string",
            "facebook" => "nullable|string",
            "instagram" => "nullable|string",
            "twitter" => "nullable|string",
            "tiktok" => "nullable|string",


            "youtube" => "nullable|string",
            "linkedin" => "nullable|string",

        ]);

        if (isset($data["logo"])) {
            $data["logo"] = Storage::put("public/imgs", $data["logo"]);
        }

        if (isset($data["fav"])) {
            $data["fav"] = Storage::put("public/imgs", $data["fav"]);
        }

        foreach ($data as $key => $value) {
            setting::where("key", $key)->update([
                "value" => $value
            ]);
        }

        return redirect()->back()->with("success", "تم التعديل بنجاح");
    }

    function update_financial(Request $request)
    {
        $data = $request->validate([
            "service_expenses" => "required",
        ]);


        foreach ($data as $key => $value) {
            setting::where("key", $key)->update([
                "value" => $value
            ]);
        }

        return redirect()->back()->with("success", "تم التعديل بنجاح");
    }
}
