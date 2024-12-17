<?php

namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class authController extends Controller
{


    public function login(Request $request)
    {
        $data = $request->validate([
            "identifier" => "required",
            "password" => "required|string|min:8",
        ], [
            "identifier.required" => "البريد الإلكتروني أو الهاتف مطلوب",
            "password.required" => "كلمة المرور مطلوبة",
            "password.min" => "يجب أن لا تقل كلمة المرور عن 8 أحرف",
        ]);

        $identifier = $data['identifier'];
        $password = $data['password'];

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identifier, 'password' => $password];
        } else {
            $credentials = ['mobile' => $identifier, 'password' => $password];
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->active == 3) {
                Auth::logout();
                return redirect("login")->withErrors("حسابك غير نشط")->withInput();
            } elseif ($user->active == 0) {
                Auth::logout();
                return redirect("login")->withErrors("تم تسجيل الحساب، انتظر حتى يتم تفعيل الحساب خلال 24 ساعة")->withInput();
            } elseif ($user->active == 1) {

                return redirect("home");
            } else {
                Auth::logout();
                return redirect("login")->withErrors("هناك خطأ ما، يرجى التواصل معنا")->withInput();
            }
        } else {
            $user = User::where("email", $identifier)->orWhere("mobile", $identifier)->first();

            if ($user == null) {
                return redirect("login")->withErrors("هذا البريد الإلكتروني أو الهاتف غير موجود")->withInput();
            } else {
                if (!password_verify($password, $user->password)) {
                    return redirect("login")->withErrors("كلمة المرور غير صحيحة")->withInput();
                }
            }
        }

        return redirect("login")->withErrors("فشل في تسجيل الدخول")->withInput();
    }



    public function register(Request $request)
    {
        // تحديد قواعد التحقق
        $data = $request->validate([
            "name" => "required|min:3|string",
            "identifier" => [
                "required",
                function ($attribute, $value, $fail) {
                    // تحقق مما إذا كان الإدخال بريدًا إلكترونيًا
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        // تحقق من صحة البريد الإلكتروني
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('البريد الإلكتروني غير صحيح.');
                        }
                    } else {
                        // تحقق من أن رقم الهاتف يتكون من 11 رقم
                        if (!preg_match('/^\d{11}$/', $value)) {
                            $fail('رقم الهاتف يجب أن يتكون من 11 رقم.');
                        }
                    }
                }
            ],
            "password" => "required|min:8|max:32|confirmed|string",
        ], [
            "name.required" => "يرجى كتابة اسم المستخدم",
            "identifier.required" => "البريد الإلكتروني أو الهاتف مطلوب",
            "password.required" => "يرجى كتابة كلمة المرور",
            "password.min" => "يجب ألا تقل كلمة المرور عن 8 أحرف",
            "password.confirmed" => "كلمة المرور غير متطابقة",
        ]);

        // استخراج البيانات
        $identifier = $data['identifier'];
        $data["password"] = bcrypt($data["password"]);
        $data["active"] = "1";

        // تعيين الحقل المناسب بناءً على نوع الإدخال
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $identifier;
            unset($data['identifier']);

            if (User::where('email', $data['email'])->exists()) {
                return Redirect::back()->withErrors('البريد الإلكتروني هذا مسجل بالفعل.')->withInput();
            }
        } else {
            $data['mobile'] = $identifier;
            unset($data['identifier']);

            if (User::where('mobile', $data['mobile'])->exists()) {
                return Redirect::back()->withErrors('رقم الهاتف هذا مسجل بالفعل.')->withInput();
            }
        }

        try {
            $user = User::create($data);
            Auth::login($user);
            return redirect("home");
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors("لم يتم التسجيل")->withInput();
        }
    }



    public function logout()
    {
        Auth::logout();

        return redirect('login');
    }

    public function changePasswoed(Request $request)
    {
        $data = $request->validate([
            "old" => "required|string",
            "password" => "required|string|min:8|max:32|confirmed",
        ], [
            "password.min" => "يجب ان لا تقل كلمة السر عن 8 حروف",
            "old.required" => "كلمة السر القديمة مطلوبة",
            "password.required" => "كلمة السر الجديدة مطلوبة",
            "password.confirmed" => "كلمة السر غير متطابقة",
        ]);

        try {

            if (password_verify($data['old'], auth()->user()->password)) {

                User::find(auth()->user()->id)->update([
                    "password" => bcrypt($data['password'])
                ]);

                return Redirect::back()->with("success", "تم تغير كلمة السر بنجاح");
            } else {
                return Redirect::back()->with("error", "كلمة السر القديمة غلط");
            }
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لا يمكن تغير كلمة السر");
        }
    }

    public function reset_password(Request $request)
    {
        $data = $request->validate([
            "email" => "required|email|exists:users",
            // 'g-recaptcha-response' => 'required|captcha'

        ], [
            "email.required" => "يرجي كتابة البريد الالكتروني",
            "email.exists" => "البريد ده مش  موجود",
            "g-recaptcha-response.required" => "الكابتشا مطلوبة"
        ]);


        $email = $data["email"];
        $token = Str::random(64);


        DB::table("password_reset_tokens")->where("email", $email)->delete();


        DB::table("password_reset_tokens")->insert([
            "email" => $email,
            "token" => $token,
            "created_at" => Carbon::now()
        ]);


        try {
            Mail::send("auth/restTemplate", ["token" => $token], function ($message) use ($email) {
                $message->to($email);
                $message->subject("استعادة كلمة السر");
            });

            return Redirect::back()->with("success", "تم ارسال الكود الي البريد");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "فشل ارسال الكود الي البريد")->withInput();;
        }
    }

    public function create_password_form($token)
    {
        return view('auth/create_password', compact("token"));
    }

    public function create_password(Request $request)
    {
        // validation

        $data = $request->validate([
            "email" => "required|email|exists:users",
            "password" => "required|min:8|max:32|confirmed",
            // 'g-recaptcha-response' => 'required|captcha'

        ], [
            "email.exists" => "هذا البريد غير موجود لدينا"
        ]);

        // cheak token


        $tokenRecord = DB::table("password_reset_tokens")
            ->where("email", $data["email"])
            ->where("token", $request->passwordToken)
            ->first();



        if (!$tokenRecord) {
            return Redirect::back()->withErrors("الرمز خطأ !!")->withInput();
        }

        // change pawword


        user::where("email", $data["email"])->update([
            "password" => Hash::make($data["password"])
        ]);


        //delete oldToken


        DB::table("password_reset_tokens")->where("email", $data["email"])->delete();


        return redirect("/login")->with("success", "تم تغير كلمة السر بنجاح");
    }
}
