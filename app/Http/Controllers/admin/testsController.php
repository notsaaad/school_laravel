<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\stage;
use App\Models\test;
use App\Models\testSubject;
use App\Models\year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class testsController extends Controller
{
    public function index()
    {

        $tests = test::get();
        $stages = stage::get();
        $years = year::get();

        return view('admin/test/index', get_defined_vars());
    }

    public function destroy(Request $request)
    {

        $test = test::findOrFail($request->delete_id);
        $test->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            "stage_id" => "required|integer",
            "study_type" => "required|in:national,international",
            "year_id" => "required|integer",
        ]);


        if (testCheck($data["stage_id"], $data["study_type"], $data["year_id"])->exists()) {
            return back()->with("error", "الاختبار ده مضافة من قبل");
        }



        test::create($data);
        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            "study_typeInput" => "required|in:national,international",
            "stage_idInput" => "required|integer",
            "year_idInput" => "required|integer",
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();



        if (testCheck($data["stage_id"], $data["study_type"], $data["year_id"])->where('id', '!=', $data["id"])->count() > 0) {
            return back()->with("error", "تأكد من عدم وجود اختبار مضاف مسبقا بنفس التعديلات  المطلوبة");
        }



        $test = test::findOrFail($data["id"]);

        $test->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }

    function subject_index(test $test)
    {
        return view("admin/test/subjects", compact("test"));
    }

    function subject(Request $request)
    {

        $rules = [];


        $data =  $request->validate($rules + [
            "name" => "required|string",
            "test_id" => "required|integer",
        ]);


        testSubject::create($data);
        return redirect()->back()->with("success", trans("messages.added_success"));
    }

    public function subject_update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            'nameInput' => 'required|string',
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        $subject = testSubject::findOrFail($data["id"]);

        $subject->update($data);
        return Redirect::back()->with("success", trans('messages.update_success'));
    }

    public function subject_destroy(Request $request)
    {

        $subject = testSubject::findOrFail($request->delete_id);

        $subject->delete();

        return redirect()->back()->with("success", trans("messages.done"));
    }
}
