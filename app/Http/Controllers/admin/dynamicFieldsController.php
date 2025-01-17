<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicField;
use Illuminate\Http\Request;
use Redirect;

class dynamicFieldsController extends Controller
{
    public function index()
    {

        $all = DynamicField::get();
        return view("admin.settings.dynamic_fields", compact("all"));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "type" => "required",
            "options" => "nullable",
            "is_required" => "nullable"
        ]);

        if (isset($data["is_required"])) {
            $data["is_required"] = true;
        }

        if (in_array($data["type"],  ["checkbox", "select", "radio"]) && $data["options"] == null) {
            return redirect()->back()->with("error", "من فضلك قم بكتابة اختيارات");
        }


        DynamicField::create($data);
        return redirect()->back()->with('success', 'تم إضافة الحقل بنجاح');
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            'nameInput' => 'required|string',
            "optionsInput" => "nullable",
            "is_requiredInput" => "nullable"
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        if (isset($data["is_required"])) {
            $data["is_required"] = true;
        } else {
            $data["is_required"] = false;
        }

        $DynamicField = DynamicField::findOrFail($data["id"]);


        if (in_array($DynamicField->type,  ["checkbox", "select", "radio"]) && $data["options"] == null) {
            return redirect()->back()->with("error", "من فضلك قم بكتابة اختيارات");
        }


        $DynamicField->update($data);
        return redirect()->back()->with("success", "تم التعديل بنجاح");
    }


    public function destroy(Request $request)
    {

        $DynamicField = DynamicField::findOrFail($request->delete_id);
        $DynamicField->delete();
        return redirect()->back()->with("success", "تم الازالة بنجاح");
    }



    public function changeOrder(Request $request)
    {


        DynamicField::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }
}
