<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\definition;
use App\Models\stage;
use App\Models\user;
use App\Models\fee;
use App\Models\region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Facades\Excel;


class studentsController extends Controller
{

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));
            return redirect()->back()->with('success', 'تم الاسترداد بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        $students = user::where("role", "user")->orderBy("id", "desc")->withTrashed()->simplePaginate(50);

        $stages = stage::get();


        return view('admin/students/index', compact('students', "stages"));
    }

    public function search(Request $request)
    {

        $filters = [
            'name' => $request->name ?? '',
            'code' => $request->code ?? '',
            'email' => $request->email ?? '',
            'role' => $mobile->mobile ?? '',
            'active' => $request->active ?? '',
            'gender' => $request->gender ?? '',
            'own_package' => $request->own_package ?? '',
            'stage_id' => $request->stage_id ?? '',
        ];

        $deleted = $request->deleted ?? '';

        $students = User::where("role", "user")->where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }
        })->orderBy('id', 'desc');


        match ($deleted) {
            "yes" => $students = $students->onlyTrashed(),
            "" => $students = $students->withTrashed(),
            default  => "",
        };


        if (!isset($request->export)) {
            $students = $students->simplePaginate(50);
            $stages = stage::get();

            return view('admin.students.index', compact('students', "stages"));
        }

        $students = $students->get();



        return (new FastExcel($students))->download('الطلاب.xlsx', function ($user) {
            return [
                'الاسم' => $user->name,
                "الكود" => $user->code,
                'المرحلة' => $user->stage->name,
                'الايميل' => $user->email,
                "التليفون" => $user->mobile,
                "النوع" => match ($user->gender) {
                    "boy" => "ذكر",
                    "girl" => "انثي",
                },
                "بمتلك باكيدج" => match ($user->own_package) {
                    "yes" => "نعم",
                    "no" => "لا",
                },
                "حالة الحساب" => match ($user->active) {
                    0 => "غير نشط",
                    1 => "نشط",
                },
                "تاريخ التسجيل" => $user->created_at->format('Y-m-d h:i:s A')

            ];
        });
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            "name" => "required|string",
            "email" => "nullable|email",
            "mobile" => "nullable",
            "stage_id" => "required",
            "code" => "required",
            "gender" => "required",
            "own_package" => "required",
        ], [
            "email.email" => "هذه ليست صيغة ايميل صحيحية",
            "stage_id.required" => "يرجي اختيار مرحلة",
        ]);

        if (check_Email($data['email'])) {
            return redirect()->back();
        }

        $data["active"] = "1";
        $data["role"] = "user";
        $data["password"] = bcrypt("12345678");

        User::create($data);

        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }

    public function edit(user $user)
    {

        $stages = stage::get();

        $regions = region::get();

        $nationalities = definition::where("type", "nationalities")->get();
        $jobs = definition::where("type", "jobs")->get();
        $qualifications = definition::where("type", "qualifications")->get();
        $kinships = definition::where("type", "kinship")->get();
        $disabilities = definition::where("type", "disability")->get();




        return view('admin/students/edit', get_defined_vars());
    }

    public function update(Request $request, user $user)
    {

        $data = $request->validate([
            "name" => "required|string",
            "email" => "nullable|email",
            "mobile" => "nullable",
            "stage_id" => "required",
            "code" => "required",
            "gender" => "required",
            "active" => "required",
            "own_package" => "required",
        ], [
            "email.email" => "هذه ليست صيغة ايميل صحيحية",
            "stage_id.required" => "يرجي اختيار مرحلة",

        ]);


        $extra = $request->validate([
            'study_type' => 'nullable|in:national,international',
            'join_date' => 'nullable',
            'national_id' => 'nullable|numeric|digits:14',
            'region_id' => 'nullable',
            'birth_date' => 'nullable',
            'religion' => 'nullable',
            'nationality_id' => 'nullable',
        ]);


        $data['permissions'] = '[]';

        // try {

        DB::beginTransaction();

        $user->update($data);






        $studentDetails = $user->details()->first();
        if (!$studentDetails) {
            $studentDetails = $user->details()->create();
        }



        $studentDetails->update($extra);


        DB::commit();


        return Redirect::back()->with("success", "تم التعديل بنجاح");
        // } catch (\Throwable $th) {

        //     DB::rollBack();
        //     return Redirect::back()->with("error", "لم يتم التعديل");
        // }
    }

    public function updateOther(Request $request, user $user)
    {

        $data = $request->validate([
            'father_alive' => 'nullable|boolean',
            'mother_alive' => 'nullable|boolean',



            'father_phone' => 'nullable',
            'father_whatsapp' => 'nullable',
            'father_national_id' => 'nullable|numeric|digits:14',
            'father_job_id' => 'nullable',
            'father_workplace' => 'nullable',
            'father_nationality_id' => 'nullable',
            'father_qualification_id' => 'nullable',



            'mother_phone' => 'nullable',
            'mother_whatsapp' => 'nullable',
            'mother_national_id' => 'nullable|numeric|digits:14',
            'mother_job_id' => 'nullable',
            'mother_workplace' => 'nullable',
            'mother_nationality_id' => 'nullable',
            'mother_qualification_id' => 'nullable',



            'kinship_name' => 'nullable',
            'kinship_phone' => 'nullable',
            'kinship_email' => 'nullable|email',


            'kinship_national_id' => 'nullable|numeric|digits:14',
            'kinship_id' => 'nullable',
            'kinship_job_id' => 'nullable',


            'kinship_type' => 'nullable',
            'kinship_reason' => 'nullable',
            'kinship_notes' => 'nullable',
            'kinship2_phone' => 'nullable',
            'kinship2_name' => 'nullable',
            'kinship2_id' => 'nullable',




            'disability_id' => 'nullable',
            'disability_notes' => 'nullable',




        ]);

        $data['father_alive'] = $data['father_alive'] ?? false;
        $data['mother_alive'] = $data['mother_alive'] ?? false;


        DB::beginTransaction();


        $studentDetails = $user->details()->first();
        if (!$studentDetails) {
            $studentDetails = $user->details()->create();
        }

        $studentDetails->update($data);


        DB::commit();


        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }

    public function destroy(Request $request)
    {
        try {

            $user = user::findOrFail($request->delete_id);

            $user->delete();

            return redirect('admin/students')->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }

    public function restore($id)
    {

        try {

            $user = user::withTrashed()->find($id);
            $user->restore();

            return Redirect::back()->with("success", "تم استرجاع الحساب بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الاسترجاع");
        }
    }

    public function login(user $user)
    {
        auth()->login($user);
        return redirect("home");
    }


    public function fees($code)
    {

        $user = User::where("code", $code)->firstOrFail();

        $fees = $user->fees()->get();

        return view('admin/students/fees', get_defined_vars());
    }
    public function show_student_fee($studentCode, $feeID)
    {

        $user = User::where('code', $studentCode)->firstOrFail();

        $fee = fee::withTrashed()->with(["items" => function ($q) {
            $q->withTrashed()->with("payments");
        }])->where(function ($query) use ($user) {
            $query->where('stage_id', $user->stage_id)
                ->orWhereHas('students', function ($query) use ($user) {
                    $query->where('user_id',  $user->id);
                });
        })->findOrFail($feeID);

        return view('admin/students/show_student_fee', get_defined_vars());
    }
}
