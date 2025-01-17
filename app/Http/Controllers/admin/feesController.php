<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\fee;
use App\Models\feesItem;
use App\Models\stage;
use App\Models\User;
use App\Models\year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class feesController extends Controller
{
    function index()
    {
        $stages = stage::get();
        $years = year::get();

        $fees = fee::get();

        return view("admin.fees.index", get_defined_vars());
    }
    function search(Request $request)
    {
        $stages = stage::get();
        $years = year::get();


        $filters = [
            'name' => $request->name ?? '',
            'enable' => $request->enable ?? '',
            'stage_id' => $request->stage_id ?? '',
            'year_id' => $request->year_id ?? '',
        ];


        $fees = fee::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }
        })->orderBy('id', 'desc');



        $fees = $fees->get();

        return view("admin.fees.index", get_defined_vars());
    }

    function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            "name" => ["required"],
            "price" => ["required"],
            "year_id" => ["required"],
            "stage_id" => ["required"],
            "end_at" => ["nullable"],
            "students" => ["nullable", "array"],
            "items.*.name" => ["nullable", "string", "max:255"],
            "items.*.price" => ["nullable", "integer", "min:1"],
        ], [
            "stage_id.required" => "يرجى اختيار مرحلة",
            "year_id.required" => "يرجى اختيار سنة دراسية",
        ]);


        if ($validator->fails()) {
            return json(["status" => "validation", 'message' => $validator->errors()]);
        }

        $data = $validator->validated();

        DB::beginTransaction();

        try {
            $fee = fee::create([
                "name" => $data["name"],
                "price" => $data["price"],
                "end_at" => $data["end_at"],
                "stage_id" => $data["stage_id"],
            ]);

            if (isset($data["items"])) {
                foreach ($data["items"] as $item) {
                    feesItem::create([
                        "name" => $item["name"],
                        "price" => $item["price"],
                        "fee_id" => $fee->id,
                    ]);
                }
            }

            if (isset($data["students"])) {
                foreach ($data["students"] as $code) {
                    $student = User::where("code", $code)->first();

                    if ($student == null) {
                        DB::rollBack();
                        return json(["status" => "error", "message" =>  "كود الطالب " . $code . "غير موجود"]);
                    }

                    $fee->students()->attach($student->id);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            return json(["status" => "error", "message" =>   $th->getMessage()]);
        }


        DB::commit();


        return json(["status" => "success", "message" =>  "تم الاضافة بنجاح"]);
    }

    public function changeOrder(Request $request)
    {


        fee::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }

    public function destroy(Request $request)
    {

        $fee = fee::findOrFail($request->delete_id);
        $fee->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }

    function showHide(Request $request)
    {

        try {

            $fee = fee::findOrFail($request->id);


            $fee->update([
                "enable" => "$request->show"
            ]);


            return json(["status" => "done", "show" => $fee->enable]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "show" => $fee->enable]);
        }
    }



    function edit(fee $fee)
    {
        $stages = stage::get();
        $years = year::get();


        return view("admin.fees.edit", get_defined_vars());
    }


    function update(Request $request,  fee $fee)
    {


        $data = $request->validate([
            "name" => ["required"],
            "year_id" => ["required"],
            "price" => ["required"],
            "stage_id" => ["required"],
            "end_at" => ["nullable"],
            "students" => ["nullable", "array"],
            "items.*.name" => ["nullable", "string", "max:255"],
            "items.*.price" => ["nullable", "integer", "min:1"],
        ], [
            "stage_id.required" => "يرجى اختيار مرحلة",
            "year_id.required" => "يرجى اختيار سنة دراسية",

        ]);


        DB::beginTransaction();

        $fee->update([
            "name" => $data["name"],
            "price" => $data["price"],
            "end_at" => $data["end_at"],
            "year_id" => $data["year_id"],
            "stage_id" => $data["stage_id"],
        ]);



        if (isset($data["students"])) {
            foreach ($data["students"] as $code) {
                $student = User::where("code", $code)->first();

                if ($student == null) {
                    DB::rollBack();
                    return back()->with("error",  "كود الطالب " . $code . "غير موجود");
                }

                $fee->students()->syncWithoutDetaching([$student->id]);
            }
        } else {
            $fee->students()->sync([]);
        }


        DB::commit();


        return back()->with("success",  "تم التعديل بنجاح");
    }



    public function destroy_item(Request $request)
    {

        $feesItem = feesItem::findOrFail($request->delete_id);
        $feesItem->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }

    public function changeOrder_items(Request $request)
    {


        feesItem::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }


    public function update_item(Request $request)
    {

        $request->validate([
            "new_price_input" => "required|integer|min:1"
        ]);
        $feesItem = feesItem::findOrFail($request->value_id);

        $feesItem->update([
            "name" => $request->new_name_input,
            "price" => $request->new_price_input,
        ]);

        return redirect()->back()->with("success", "تم التعديل بنجاح");
    }
    public function store_items(Request $request,   fee $fee)
    {

        $data = $request->validate([
            "items.*.name" => ["nullable", "string", "max:255"],
            "items.*.price" => ["nullable", "integer", "min:1"],
        ]);



        DB::beginTransaction();

        foreach ($data["items"] as $item) {

            $last = feesItem::max('order');


            feesItem::create([
                "name" => $item["name"],
                "price" => $item["price"],
                "fee_id" => $fee->id,
                "order" => $last += 1
            ]);
        }

        DB::commit();


        return redirect()->back()->with("success", "تم الاضافة بنجاح");
    }
}
