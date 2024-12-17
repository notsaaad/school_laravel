<?php

namespace App\Http\Controllers;

use App\Models\definition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class definitionsController extends Controller
{
    public function index($type)
    {
        $all = definition::where("type", $type)->get();


        $title = trans("words." . $type);



        return view('admin/definition/index', compact('all', "title"));
    }

    public function destroy(Request $request)
    {

        $definition = definition::findOrFail($request->delete_id);
        $definition->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'ar_name' => 'required|string',
            'en_name' => 'required|string',
            'type' => 'required|string',
        ]);
        definition::create($data);
        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            'ar_nameInput' => 'required|string',
            'en_nameInput' => 'required|string',
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        $definition = definition::findOrFail($data["id"]);

        $definition->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }


    public function changeOrder(Request $request)
    {


        definition::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }
}
