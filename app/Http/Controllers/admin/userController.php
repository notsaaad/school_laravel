<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\role;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Rap2hpoutre\FastExcel\FastExcel;

class userController extends Controller
{

    public function index()
    {
        $users = user::orderBy('id', 'desc')->where("role", "admin")->withTrashed()->simplePaginate(50);
        $roles = role::get();


        return view('admin/users/index', compact('users', "roles"));
    }

    public function search(Request $request)
    {

        $filters = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
            'mobile' => $request->mobile ?? '',
            'role' => $request->role ?? '',
            'active' => $request->active ?? '',
        ];

        $deleted = $request->deleted ?? '';

        $users = User::where("role", "admin")->where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }
        })->orderBy('id', 'desc');


        match ($deleted) {
            "yes" => $users = $users->onlyTrashed(),
            "" => $users = $users->withTrashed(),
            default  => "",
        };


        if (!isset($request->export)) {
            $users = $users->simplePaginate(50);
            $roles = Role::get();
            return view('admin.users.index', compact('users', 'roles'));
        }

        $users = $users->get();



        return (new FastExcel($users))->download('المستخدمين.xlsx', function ($user) {
            return [
                'الاسم' => $user->name,
                'الايميل' => $user->email,
                "التليفون" => $user->mobile,
                "نوع الحساب	" => match ($user->role) {
                    "admin" => "ادمن",
                    "user" => "مسوق",
                    "postman" => "مندوب",
                    "trader" => "تاجر",
                    "moderator" => "مودريتور",
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
            "email" => "required|email",
            "password" => "required|min:8|max:32|confirmed|string",
            "role_id" => "nullable",
            "mobile" => "required",
        ], [
            "email.email" => "هذه ليست صيغة ايميل صحيحية",
            "password.confirmed" => "كلمة السر غير متطابقة",
            "password.min" => "يجب ان لا تقل كلمة السر عن 8 ارقام"
        ]);

        if (check_Email($data['email'])) {
            return redirect()->back();
        }

        $data["active"] = "1";
        $data["role"] = "admin";
        $data["password"] = bcrypt($data["password"]);

        User::create($data);

        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }

    public function edit(user $user)
    {
        $roles = role::get();
        return view('admin/users/edit', compact('user', "roles"));
    }

    public function update(Request $request, user $user)
    {

        $data = $request->validate([
            "name" => "required|min:3|string",
            "password" => "nullable|min:8|max:32|confirmed|string",
            "role_id" => "nullable",
            "mobile" => "required",
            "active" => "required",
        ], [
            "password.confirmed" => "كلمة السر غير متطابقة",
        ]);

        if (!isset($data['permissions'])) {
            $data['permissions'] = '[]';
        } else {

            $data['permissions'] = json_encode($data['permissions']);
        }

        if ($data['password'] != null) {
            $data["password"] = bcrypt($data["password"]);
        } else {
            unset($data["password"]);
        }

        $data["role"] = "admin";

        try {

            $user->update($data);

            return Redirect::back()->with("success", "تم التعديل بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم التعديل");
        }
    }

    public function destroy(Request $request)
    {
        try {

            $user = user::findOrFail($request->delete_id);

            $user->delete();

            return redirect('admin/users')->with("success", "تم الازالة بنجاح");
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

    function searchAjax(Request $request)
    {

        $mobile = $request->input("query");

        if (isset($request->all)) {
            $users =  user::whereIn("role", ["user", "trader"]);
        } else {
            $users =  user::where("role", "user");
        }

        $users = $users->where("mobile", "LIKE", "%{$mobile}%")->select("id", 'name', "mobile")->get();

        if (isset($users[0]->id)) {
            return json(["status" => "success", "users" => $users]);
        } else {
            return json(["status" => "error"]);
        }
    }

    public function login(user $user)
    {
        auth()->login($user);
        return redirect("home");
    }
}
