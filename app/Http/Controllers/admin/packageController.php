<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\package;
use App\Models\packageProduct;
use App\Models\product;
use App\Models\stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class packageController extends Controller
{
    public function index()
    {
        $packages = package::withTrashed()->withCount("products")->simplePaginate(50);

        $stages = stage::get();

        return view('admin/packages/index', compact('packages', "stages"));
    }

    function searchpackages(Request $request)
    {
        return json(["packages" => product::where("gender",  $request->gender)->where("stage_id", $request->stage_id)->get()]);
    }


    public function store(Request $request)
    {
        // return $request;
        $data = $request->validate([
            "name" => "required",
            "stage_id" => "required|array|min:1",
            "stage_id.*" => "exists:stages,id",
            "img" => "nullable|mimes:jpeg,png,jpg,gif,webp",
            "gender" => "required",
            "price" => "required",
        ], [
            "stage_id.required" => "يرجى اختيار مرحلة واحدة على الأقل.",
            "stage_id.array" => "المرحلة يجب أن تكون قائمة من العناصر.",
            "stage_id.*.exists" => "المرحلة المختارة غير موجودة.",
        ]);



        if (isset($data["img"])) {
            $data["img"] = Storage::put("public/packages", $data["img"]);
        }
        $packageData = collect($data)->except('stage_id')->toArray();
        $package     = package::create($data);
        $package->stages()->sync($data['stage_id']);
        return redirect()->back()->with("success", "تم الاضافة بنجاح");
    }


    function showHidepackage(Request $request)
    {

        try {

            $package = package::withCount("products")->findOrFail($request->id);

            if ($package->products_count == 0) {
                return json(["status" => "error", "message" => "لا يمكن اظهار الحزمة التي لا تحتوي علي منتجات"]);
            }


            $package->update([
                "show" => "$request->show"
            ]);


            return json(["status" => "done", "show" => $package->show]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "show" => $package->show]);
        }
    }


    public function destroy(Request $request)
    {

        $package = package::findOrFail($request->delete_id);
        $package->update(["show" => 0]);
        $package->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function products_destroy(Request $request, package $package)
    {


        $product = packageProduct::where("package_id", $package->id)->where("product_id", $request->delete_id)->first();

        $product->delete();

        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }

    public function restore($id)
    {

        try {

            $package = package::withTrashed()->find($id);
            $package->restore();

            return Redirect::back()->with("success", value: "تم استرجاع المنتج بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الاسترجاع");
        }
    }

    public function search(Request $request)
    {

        $filters = [
            'name' => $request->name ?? '',
            'gender' => $request->gender ?? '',
            'show' => $request->show ?? '',
            'stage_id' => $request->stage_id ?? '',
        ];


        $packages = package::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }
        })->orderBy('id', 'desc');

        $deleted = $request->deleted ?? '';

        match ($deleted) {
            "yes" => $packages = $packages->onlyTrashed(),
            "" => $packages = $packages->withTrashed(),
            default  => "",
        };


        $packages = $packages->simplePaginate(50);
        $stages = stage::get();

        return view('admin.packages.index', compact('packages', 'stages'));
    }

    public function edit(package $package)
    {
        $stages = stage::get();

        $stageIds = $package->stages->pluck('id')->toArray();

        $products = Product::when($package->gender !== 'both', function ($query) use ($package) {
                $query->where('gender', $package->gender);
            })
            ->whereHas('stages', function ($query) use ($stageIds) {
                $query->whereIn('stage_id', $stageIds);
            })
            ->whereDoesntHave('packages', function ($query) use ($package) {
                $query->where('package_id', $package->id);
            })
            ->get();

        // return $products;

        return view('admin/packages/edit', compact('stages', "package", "products"));
    }

    public function update(Request $request, package $package)
    {
        $data = $request->validate([
            "name" => "required|string|max:255",
            "stage_id" => "required|array|min:1",
            "stage_id.*" => "exists:stages,id",
            "img" => "nullable|mimes:jpeg,png,jpg,gif,webp",
            "gender" => "required",
            "price" => "required|numeric|min:0",
        ], [
            "stage_id.required" => "يرجى اختيار مرحلة واحدة على الأقل.",
        ]);

        if (isset($data["img"])) {
            $data["img"] = $request->file("img")->store("packages", "public");
        }

        $packageData = collect($data)->except('stage_id')->toArray();

        $package->update($packageData);

        $package->stages()->sync($data['stage_id']);

        return redirect()->back()->with("success", "تم التعديل بنجاح");
    }


    function products(Request $request)
    {

        $data = $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            "package_id" => "required"
        ], [
            "products.required" => "يرجي اختيار منتج"
        ]);

        try {
            DB::beginTransaction();


            foreach ($data["products"] as $product_id) {
                packageProduct::create([
                    "product_id" => $product_id,
                    "package_id" => $data["package_id"]
                ]);
            }

            DB::commit();


            return redirect()->back()->with("success", "تم الاضافة بنجاح");
        } catch (\Throwable $th) {

            DB::rollBack();

            throw new \Exception($th->getMessage());
        }
    }
}
