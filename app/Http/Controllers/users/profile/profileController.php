<?php



namespace App\Http\Controllers\users\profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


class profileController extends Controller
{


    function index()
    {

        // $paymentMethods = auth()->user()->paymentMethods;

        return view("users/profile/index");
    }

    function info(Request $request)
    {

        $data = $request->validate([
            "mobile" => "required|numeric|digits:11",
            "email" => "required|email",
            "img" => "nullable"
        ]);



        try {

            $user = User::findOrFail(auth()->user()->id);



            if (isset($data["img"])) {
                $data["img"] = Storage::put("public/logo", $data['img']);
            }


            $user->update($data);

            app()->setLocale('en');


            return Redirect::back()->with("success", trans("messages.update_success"));
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", trans("messages.update_error"))->withInput();
        }
    }
    function password(Request $request)
    {


        $data = $request->validate([
            "passwordOld" => "required|string",
            "password" => "required|min:8|max:32|confirmed|string",

        ], [
            "passwordOld.required" => "يرجي كتابة كلمة السر القديمة",
            "password.required" => "يرجي كتابة  كلمة المرور الجديدة",
            "password.min" => "يجب ان لا تقل كلمة المرور  الجديدة عن 8 ارقام",
            "password.confirmed" => "كلمة المرور غير متطابقة",
        ]);


        if (!Hash::check($data["passwordOld"], auth()->user()->password)) {
            return Redirect::back()->with("error", "كلمة المرور القديمة خطأ")->withInput();
        }
        $data["password"] = bcrypt($data["password"]);


        try {

            $user = User::findOrFail(auth()->user()->id);

            $user->update($data);


            return Redirect::back()->with("success", trans("messages.password_changed_successfully"));
        } catch (\Throwable $th) {

            return Redirect::back()->with("error",  trans("messages.password_change_failed"));
        }
    }
}
