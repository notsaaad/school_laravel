<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\customState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class customStatusController extends Controller
{
    public function index()
    {
        $all = customState::get();
        return view('admin/customState/index', compact('all'));
    }

    public function destroy(Request $request)
    {

        $customState = customState::findOrFail($request->delete_id);
        $customState->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'status'=> 'required',
        ]);
        customState::create($data);
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


        $customState = customState::findOrFail($data["id"]);

        $customState->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }


    public function changeOrder(Request $request)
    {


        customState::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }
}
