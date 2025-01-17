<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\role;
use Illuminate\Http\Request;

class rolesController extends Controller
{
    function index()
    {

        $roles = role::get();
        return view("admin/roles/index", compact("roles"));
    }

    function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "permissions" => "nullable"
        ]);

        if (!isset($data['permissions'])) {
            $data['permissions'] = '[]';
        } else {

            $data['permissions'] =  json_encode($data['permissions']);
        }

        role::create($data);

        return redirect()->back()->with('success', 'تم الاضافة بنجاح');
    }

    public function destroy(Request $request)
    {

        $role = role::findOrFail($request->delete_id);

        try {
            $role->delete();

            return redirect()->back()->with("success", "تم الازالة بنجاح");
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "لم يتم الازالة");
        }
    }

    function edit(role $role)
    {
        return view("admin/roles/edit", compact("role"));
    }

    function update(role $role, request $request)
    {


        $data = $request->validate([
            "name" => "required|string",
            "permissions" => "nullable"
        ]);

        // dd($data['permissions']);


        if (!isset($data['permissions'])) {
            $data['permissions'] = '[]';
        } else {

            $data['permissions'] =  json_encode($data['permissions']);
        }

        $role->update($data);

        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }
}
