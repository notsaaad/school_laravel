<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\setting;
use App\Models\email;

class settingsController extends Controller
{
    public function index()
    {
        return view("admin/settings/index");
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
