<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class stageController extends Controller
{

    public function changeOrder(Request $request)
    {


        stage::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }

    public function index()
    {

        $all = stage::get();
        return view('admin/stage/index', compact('all'));
    }

    public function destroy(Request $request)
    {

        $stage = stage::findOrFail($request->delete_id);
        $stage->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);
        stage::create($data);
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


        $stage = stage::findOrFail($data["id"]);

        $stage->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }
}
