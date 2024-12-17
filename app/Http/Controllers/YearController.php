<?php

namespace App\Http\Controllers;

use App\Models\year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class YearController extends Controller
{

    public function index()
    {

        $all = year::get();
        return view('admin/year/index', compact('all'));
    }

    public function destroy(Request $request)
    {

        $year = year::findOrFail($request->delete_id);
        $year->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);
        year::create($data);
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


        $year = year::findOrFail($data["id"]);

        $year->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }


    public function changeOrder(Request $request)
    {


        year::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }
}
