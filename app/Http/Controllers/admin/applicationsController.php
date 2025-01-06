<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\application;
use App\Models\applicationData;
use App\Models\applicationFee;
use App\Models\applicationSubject;
use App\Models\definition;
use App\Models\DynamicField;
use App\Models\place;
use App\Models\stage;
use App\Models\User;
use App\Models\year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class applicationsController extends Controller
{
    function fees(request $request)
    {
        $data = $request->validate([
            "stage_id" => "required|integer",
            "study_type" => "required|in:national,international",
            "year_id" => "required|integer",
            "amount" => "required|integer",
        ]);


        if (ApplicationFee($data["stage_id"], $data["study_type"], $data["year_id"])->exists()) {
            return back()->with("error", "المصاريف ديه مضافة من قبل");
        }

        applicationFee::create($data);

        return back()->with("success", "تم الاضافة بنجاح");
    }


    public function fees_update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            "study_typeInput" => "required|in:national,international",
            "stage_idInput" => "required|integer",
            "year_idInput" => "required|integer",
            "amountInput" => "required|integer",
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        if (ApplicationFee($data["stage_id"], $data["study_type"], $data["year_id"])->where('id', '!=', $data["id"])->count() > 0) {
            return back()->with("error", "تأكد من عدم وجود مصاريف مضافة مسبقا بنفس التعديلات  المطلوبة");
        }


        $applicationFee = applicationFee::findOrFail($data["id"]);


        $applicationFee->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }


    public function destroy_fees(Request $request)
    {

        $fees = applicationFee::findOrFail($request->delete_id);
        $fees->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }






    function index(request $request)
    {
        $applications = new application();


        $filters = [
            'name' => $request->name ?? '',
            'stage_id' => $request->stage_id ?? '',
            'year_id' => $request->year_id ?? '',
            'status' => $request->status ?? '',
            'study_type' => $request->study_type ?? '',
        ];



        $applications = $applications->where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }
        })->orderBy('id', 'desc');

        if (!empty($request->phone)) {
            $applications = $applications->where(function ($query) use ($request) {
                $query->where("phone1", "like", "%{$request->phone}%")
                    ->orWhere("phone2", "like", "%{$request->phone}%");
            });
        }


        $applications = $applications->simplePaginate(50);

        $stages = stage::get();
        $years = year::get();
        $places = place::get();


        $discountTypes = definition::where("type", "discountType")->get();
        $referralSources = definition::where("type", "referralSource")->get();
        $specialStatus = definition::where("type", "specialStatus")->get();


        return view('admin.applications.index', get_defined_vars());
    }


    function store(request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "stage_id" => "required|integer",
            "gender" => "required",
            "study_type" => "required|in:national,international",
            "year_id" => "required|integer",
            "phone1" => "required",
            "phone2" => "nullable",
            "place_id" => "required",
            "discountType" => "nullable",
            "referralSource" => "nullable",
            "specialStatus" => "nullable",
            "notes" => "nullable|string",
        ]);


        $fees = ApplicationFee($data["stage_id"], $data["study_type"], $data["year_id"])->first();

        if (!$fees) {
            return back()->with("error", "لا يوجد مصاريف مسجلة  لهذا التقديم برجاء اضافة مصاريف")->withInput();
        }




        $test = testCheck($data["stage_id"], $data["study_type"], $data["year_id"])->withCount("subjects")->first();

        if (!$test) {
            return back()->with("error", "لا يوجد اختبارات مسجلة  لهذا التقديم برجاء اضافة اختبار")->withInput();
        } else {
            if ($test->subjects_count == 0) {
                return back()->with("error", "لا يوجد مواد مسجلة  لهذا التقديم برجاء اضافة مواد للاختبار")->withInput();
            }
        }



        $data["fees_id"] = $fees->id;
        $data["code"] = generateCode(new application(), "APP");

        DB::beginTransaction();
        $application = application::create($data);

        $application->subjects()->attach($test->subjects()->pluck("id")->toArray());


        DB::commit();

        return back()->with("success", "تم الاضافة بنجاح");
    }

    function show($code)
    {
        $fields = DynamicField::get();

        $application = application::where("code", $code)->firstOrFail();

        return view("admin.applications.show", get_defined_vars());
    }


    public function update(Request $request)
    {


        $data = $request->validate([
            "idInput" => "required|string",
            "nameInput" => "required|string",
            "phone1Input" => "required",
            "genderInput" => "required",
            "phone2Input" => "nullable",

            "place_idInput" => "required",
            "discountTypeInput" => "nullable",
            "referralSourceInput" => "nullable",
            "specialStatusInput" => "nullable",
            "notesInput" => "nullable|string",


        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();



        $application = application::findOrFail($data["id"]);


        if (!canEdit($application)) {
            return back()->with("error", "لا يمكن التعديل علي طلب التقديم");
        }




        $application->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }


    public function destroy(Request $request)
    {

        $application = application::findOrFail($request->delete_id);
        // $application->forceDelete();
        $application->delete();


        if (!canEdit($application)) {
            return back()->with("error", "لا يمكن الازالة و طلب التقديم مكتمل");
        }


        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }



    public function update_subject(Request $request)
    {
        applicationSubject::where("application_id", $request->application_id)->where("subject_id", $request->subject_id)->update([
            "status" => $request->status,
            "retake_data" => $request->retake_date ?? null
        ]);

        return Redirect::back()->with("success", "تم التعديل");
    }



    function complate(Request $request)
    {

        $application = application::findOrFail($request->application_id);


        if (!canEdit($application)) {
            return back()->with("error", "لا يمكن التعديل علي طلب التقديم حاليا");
        }

        if ($application->application_subjects()->where('status', '!=', 'Accepted')->exists()) {
            return back()->with("error", "يجب اجتياز الاختبارات اولا");
        }

        DB::beginTransaction();

        $application->update([
            "status" => "complate",
        ]);


        $data = [
            "name" => $application->name,
            "stage_id" => $application->stage_id,
            "code" =>  $application->code,
            "gender" => $application->gender,
            "own_package" => "no",
            "mobile" => $application->phone1
        ];
        $data["active"] = "0";
        $data["role"] = "user";
        $data["password"] = bcrypt("12345678");

        $old = User::where("code", $application->code)->exists();

        if (!$old) {
            $user = User::create($data);

            $studentDetails = $user->details()->first();
            if (!$studentDetails) {
                $studentDetails = $user->details()->create();
            }

            $studentDetails->update([
                "study_type" => $application->study_type,
                "father_alive" => "1",
                "mother_alive" => "1",
                "father_phone" => $application->phone1,
                "mother_phone" => $application->phone2,
            ]);


            DB::commit();
            return back()->with("success", "تم تغير الحالة وتسجيل الطالب");
        } else {
            DB::commit();
            return back()->with("success", "تم تغير الحالة  لاكن الطالب مسجل من قبل بنفس الكود");
        }
    }








    function changeStatus(Request $request)
    {


        $application = application::findOrFail($request->application_id);


        if (!canEdit($application)) {
            return back()->with("error", "لا يمكن التعديل علي طلب التقديم");
        }

        DB::beginTransaction();

        $application->update([
            "status" => $request->status,
        ]);


        DB::commit();


        return back()->with("success", trans("messages.update_success"));
    }




    function changeCustomStatus(Request $request)
    {


        $application = application::findOrFail($request->application_id);



        DB::beginTransaction();

        $application->update([
            "custom_status_id" => $request->status,
        ]);


        DB::commit();


        return back()->with("success", trans("messages.update_success"));
    }




    public function updateFields(Request $request, Application $application)
    {
        DB::beginTransaction();

        foreach ($request->fields as $field_id => $value) {
            $field = DynamicField::findOrFail($field_id);

            // إذا كان الحقل checkbox، قم بتحويل القيمة إلى نص مفصول بفواصل
            if ($field->type == "checkbox") {
                $value = trim(implode(",", $value));
            }

            // تحقق إذا كانت القيمة موجودة مسبقًا
            $applicationData = applicationData::where('application_id', $application->id)
                ->where('field_id', $field_id)
                ->first();

            if ($applicationData) {
                $applicationData->update(['value' => $value]);
            } else {
                applicationData::create([
                    'application_id' => $application->id,
                    'field_id' => $field_id,
                    'value' => $value,
                ]);
            }
        }

        DB::commit();

        return redirect()->back()->with("success", "تم التحديث بنجاح");
    }


    function enableToggle(Request $request)
    {

        try {

            $application = application::findOrFail($request->id);

            $application->update([
                "can_share" => "$request->show"
            ]);


            return json(["status" => "done", "show" => $application->show]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "show" => $application->show]);
        }
    }
}
