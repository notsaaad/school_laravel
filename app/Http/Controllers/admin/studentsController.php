<?php

namespace App\Http\Controllers\admin;

use App\Models\fee;
use App\Models\user;
use App\Models\stage;
use App\Models\region;
use App\Models\definition;
use Illuminate\Http\Request;
use App\Models\studentDetail;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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

    public function choosType(){
      return view('admin.students.chooseUser');
    }

    public function index()
    {
        $studentsQuery = User::where("role", "user")->withTrashed();

        if (request()->filled('type')) {
            $studentsQuery->whereHas('details', function ($q) {
                $q->where('study_type', request('type'));
            });
        }

        $students = $studentsQuery->orderBy("id", "desc")->simplePaginate(50);
        $stages = Stage::get();

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
            'study_type' => $request->type

        ];

        $deleted = $request->deleted ?? '';

          $studentsQuery = User::where("role", "user");

          // التحكم في السجلات المحذوفة
          match ($deleted) {
              "yes"     => $studentsQuery = $studentsQuery->onlyTrashed(),
              ""        => $studentsQuery = $studentsQuery->withTrashed(),
              default   => $studentsQuery = $studentsQuery, // الحالة الافتراضية
          };

          // باقي الفلاتر والديناميكية:
          $studentsQuery = $studentsQuery->where(function ($query) use ($filters) {
              foreach ($filters as $key => $value) {
                  if ($value !== '' && $key !== 'study_type') {
                      $query->where($key, 'LIKE', "%{$value}%");
                  }
              }
          });

          if (!empty($filters['study_type'])) {
              $studentsQuery->whereHas('details', function ($q) use ($filters) {
                  $q->where('study_type', 'LIKE', "%{$filters['study_type']}%");
              });
          }

          // تنفيذ الكويري مع pagination
          $students = $studentsQuery->orderBy('id', 'desc')->simplePaginate(50);





        if (!isset($request->export)) {
            // $students = $students->simplePaginate(50);
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
            "name"        => "required|string",
            "email"       => "nullable|email",
            "mobile"      => "nullable",
            "stage_id"    => "required",
            "code"        => "required",
            "gender"      => "required",
            "own_package" => "required",
            "type"        => 'required',
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

        $user    = User::create($data);
        $details = $user->details;
        if ($details) {
            $details->study_type = $data['type'];
            $details->save();
        } else {
            $user->details()->create(['study_type' => $data['type']]);
        }

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
    function getmo7afza($code) {
      $defaultRegionId = 1; // مثلاً غير معروف
      $region = region::where('id', $code)->first();
      return $region ? $region->id : $defaultRegionId;
  }
    public function update(Request $request, user $user)
    {
// تحقق من البيانات الأساسية
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
    "email.email" => "هذه ليست صيغة ايميل صحيحة",
    "stage_id.required" => "يرجى اختيار مرحلة",
]);

// تحقق من بيانات الطالب الإضافية
$extra = $request->validate([
    'study_type' => 'nullable|in:national,international',
    'division' => 'nullable|in:أدبي,علمي,علمي علوم,علمي رياضة,ليس تابع للنظام الثانوي',
    'join_date' => 'nullable|date',
    'national_id' => 'nullable|numeric|digits:14',
    'region_id' => 'nullable|numeric',
    'birth_date' => 'nullable|date',
    'religion' => 'nullable|string',
    'nationality_id' => 'nullable|numeric',
    'is_international' => 'nullable|boolean',
    'residence_expiry_date' => 'nullable|date',
    'name_en' => 'nullable|string',
    'enrollment_status' => 'nullable|in:منقول,مستجد,باقي عام,باقي عامين,منقطع,حالة وفاة',
    'student_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'second_language' => 'nullable|string',
    'ministry_code'   => 'nullable|string',
]);

$data['permissions'] = '[]';

DB::beginTransaction();

try {
    // معالجة الرقم القومي (National ID)
    if (!empty($request->national_id) && strlen($request->national_id) === 14) {
        $nat_id = $request->national_id;

        $century = $nat_id[0] == '2' ? '19' : '20';
        $year = $century . $nat_id[1] . $nat_id[2];
        $month = $nat_id[3] . $nat_id[4];
        $day = $nat_id[5] . $nat_id[6];
        $birthdate = sprintf('%04d-%02d-%02d', $year, $month, $day);

        $region_code = $nat_id[7] . $nat_id[8];
        $region_id = (int) $this->getmo7afza($region_code);

        $serial = $nat_id[12];
        $gender = ((int)$serial % 2 === 0) ? 'girl' : 'boy';

        $extra['birth_date'] = $birthdate;
        $extra['region_id'] = $region_id;
        $data['gender'] = $gender;
    } else {
        $extra['birth_date'] = null;
        $extra['region_id'] = null;
        $extra['national_id'] = null;
        $data['gender'] = null;
    }

    // تحديث بيانات المستخدم الأساسية
    $user->update($data);

    // التأكد من وجود تفاصيل الطالب
    $studentDetails = $user->details()->firstOrCreate(['student_id' => $user->id]);

    // التعامل مع رفع صورة الطالب إن وجدت
    if ($request->hasFile('student_image')) {
        $image = $request->file('student_image');
        $imageName = 'student_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public/students', $imageName);
        $extra['student_image'] = Storage::url($path);
    }

    // تحديث أو إنشاء تفاصيل الطالب
    $studentDetails->update($extra);

    DB::commit();

    return Redirect::back()->with("success", "تم التعديل بنجاح");
} catch (\Throwable $th) {
    DB::rollBack();
    return Redirect::back()->with("error", "حدث خطأ أثناء التعديل: " . $th->getMessage());
}
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
        // return get_defined_vars();
        return view('admin/students/show_student_fee', get_defined_vars());
    }
}
