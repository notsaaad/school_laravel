<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\busSetting;
use App\Models\place;
use App\Models\region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class regionsController extends Controller
{

    public function index()
    {

        $all = region::get();
        return view('admin/region/index', compact('all'));
    }

    public function destroy(Request $request)
    {

        $region = region::findOrFail($request->delete_id);
        $region->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);
        region::create($data);
        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            'nameInput' => 'required|string',
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        $region = region::findOrFail($data["id"]);

        $region->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }


    public function changeOrder(Request $request)
    {


        region::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }

    function places_index(region $region)
    {
        return view("admin/region/places", compact("region"));
    }

    function places(Request $request)
    {

        $rules = [];


        $data =  $request->validate($rules + [
            "name" => "required|string",
            "region_id" => "required|integer",
        ]);


        place::create($data);
        return redirect()->back()->with("success", trans("messages.added_success"));
    }


    public function places_update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            'nameInput' => 'required|string',
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        $place = place::findOrFail($data["id"]);

        $place->update($data);
        return Redirect::back()->with("success", trans('messages.update_success'));
    }

    public function places_destroy(Request $request)
    {

        $place = place::findOrFail($request->delete_id);

        $place->delete();

        return redirect()->back()->with("success", trans("messages.done"));
    }





    function settings_store(Request $request)
    {

        $rules = [];


        $data =  $request->validate($rules + [
            "name" => "required|string",
            "price" => "required|string",
            "go_chairs_count" => "required|string",
            "return_chairs_count" => "required|string",
            "date" => "required|string",
            "bus_id" => "required|integer",
        ]);


        busSetting::create($data);
        return redirect()->back()->with("success", trans("messages.added_success"));
    }

    public function settings_update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            'nameInput' => 'required|string',
            "priceInput" => "required|string",
            "go_chairs_countInput" => "required|string",
            "return_chairs_countInput" => "required|string",
            "dateInput" => "required|string",

        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        $busSetting = busSetting::findOrFail($data["id"]);

        $busSetting->update($data);
        return Redirect::back()->with("success", trans('messages.update_success'));
    }

    public function settings_destroy(Request $request)
    {

        $busSetting = busSetting::findOrFail($request->delete_id);

        $busSetting->delete();

        return redirect()->back()->with("success", trans("messages.done"));
    }
}
