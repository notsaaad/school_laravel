<?php

use App\Mail\TestMail;
use App\Models\region;
use App\Models\busPlace;
use App\Models\application;
use App\Models\DynamicField;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use App\Models\applicationData;
use App\Models\bus as busModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\users\userController;
use App\Http\Controllers\users\hatem\mailController;
use App\Http\Controllers\users\profile\profileController;


include('auth_routes.php');


Route::get('/', function () {
    return view("users.login");
});

Route::prefix("profile")->middleware("auth")->group(function () {
    Route::get('/', [profileController::class, 'index']);
    Route::put('info', [profileController::class, 'info']);
    Route::put('password', [profileController::class, 'password']);
});


Route::get('delete_img', function () {

    $user = auth()->user();

    if ($user->img != null) {
        Storage::delete($user->img);
    }
    $user->img = null;
    $user->save();

    return redirect()->back()->with("success", "تم ازالة الصورة بنجاح");
});



Route::middleware("auth")->get('home', function () {
    if (auth()->user()->role == 'admin') {
        return redirect("admin/home");
    } else if (auth()->user()->role == 'user') {
        return redirect("students/home");
    }
});

Route::get('changeLang/{lang}', function () {

    $lang = request()->route('lang');
    app()->setLocale($lang);

    session(['locale' => $lang]);


    return redirect()->back();
});


Route::prefix("profile")->middleware("auth")->group(function () {
    Route::get('/', [profileController::class, 'index']);
    Route::put('info', [profileController::class, 'info']);
    Route::put('password', [profileController::class, 'password']);
});


Route::post('students_login', [userController::class, 'login']);

// Route::get('students/hatem-check', [userController::class, 'Hatem_handel_All_with_nat_id']);



Route::get('get_places/{id}', function ($id) {
    $region = region::find($id);
    return json($region->places);
});
Route::get('get_bus_settings/{place_id}', function ($place_id) {
    $bus_places = busPlace::where("place_id", $place_id)->first();

    if ($bus_places == null) {
        return json(["status" => "error", "message" => "There are currently no buses in the selected area"]);
    } else {
        $bus = busModel::with("settings")->findOrFail($bus_places->bus_id);
        return json(["status" => "success", "data" => $bus->settings]);
    }
});















Route::get('applications/{code}/link', function ($code) {

    $application = application::where("code", $code)->where("can_share", "yes")->firstOrFail();
    $fields = DynamicField::get();

    return view("applicationsLink", get_defined_vars());
});





Route::put('applications/{code}', function (Request $request, $code) {

    $application = application::where("code", $code)->where("can_share", "yes")->firstOrFail();


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
});


// Route::get('/mail', function(){
//     Mail::to("amir.hatem545@gmail.com")->send(new TestMail());
//     return "email sent";
// });
