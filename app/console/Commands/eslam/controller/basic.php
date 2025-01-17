<?php

namespace App\Http\Controllers\_ControllerPath;

use App\Http\Controllers\Controller;
use App\Models\_ModelName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
class _ControllerName extends Controller
{

    public function index()
    {

        $all = _ModelName::orderBy('order', 'Asc')->paginate(25);
        return view('admin/_ModelName/index', compact('all'));
    }

    public function create()
    {
        return view('admin/_ModelName/create');
    }

    public function edit(_ModelName $_ModelName)
    {
        return view('admin/_ModelName/edit', compact('_ModelName'));
    }

    public function destroy(Request $request)
    {

        $_ModelName = _ModelName::findOrFail($request->delete_id);
        $_ModelName->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }



}
