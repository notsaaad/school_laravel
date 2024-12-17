<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {


        try {


            DB::beginTransaction();
            $googleUser = Socialite::driver('google')->stateless()->user();


            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create(
                    [
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'password' => Hash::make(generateComplexPassword()),
                        'social_type' => "google",
                        "active" => 1,
                        "role" => "user"
                    ]
                );
            } else {
                if ($user->active  != 1) {
                    return Redirect("login")->withErrors("حسابك غير نشط")->withInput();
                }
            }

            Auth::login($user);



            DB::commit();


            return redirect()->to("home");
        } catch (\Throwable $th) {
            DB::rollBack();
            return Redirect("login")->withErrors("خطأ اثناء التسجيل من خلال جوجل")->withInput();
        }
    }
}
