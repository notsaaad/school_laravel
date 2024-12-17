<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\product;
use App\Models\stage;
use App\Models\variant;
use App\Models\WarehouseProduct;
use App\Models\WarehouseProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Facades\Excel;

use Milon\Barcode\DNS1D;


class productsController extends Controller
{


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', 'تم الاسترداد بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function index()
    {
        $products = product::withTrashed()->simplePaginate(50);

        $stages = stage::get();

        return view('admin/products/index', compact('products', "stages"));
    }

    public function search(Request $request)
    {

        $filters = [
            'name' => $request->name ?? '',
            'gender' => $request->gender ?? '',
            'show' => $request->show ?? '',
            'stage_id' => $request->stage_id ?? '',
        ];


        $products = product::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }
        })->orderBy('id', 'desc');

        $deleted = $request->deleted ?? '';

        match ($deleted) {
            "yes" => $products = $products->onlyTrashed(),
            "" => $products = $products->withTrashed(),
            default  => "",
        };




        if (!isset($request->export)) {
            $products = $products->simplePaginate(50);
            $stages = stage::get();

            return view('admin.products.index', compact('products', 'stages'));
        }

        $products = $products->get();



        return (new FastExcel($products))->download('المنتجات.xlsx', function ($product) {
            return [
                'الاسم' => $product->name,
                'المرحلة' => $product->stage->name,
                "النوع" => match ($product->gender) {
                    "boy" => "ذكر",
                    "girl" => "انثي",
                    "both" => "ذكر او انثي",
                },
                'سعر المنتج' => $product->price,
                'سعر البيع' => $product->sell_price,
                'الكمية' => $product->stock,

                "حالة المنتج" => match ($product->show) {
                    0 => "غير نشط",
                    1 => "نشط",
                },
                "تاريخ التسجيل" => $product->created_at->format('Y-m-d h:i:s A')


            ];
        });
    }

    public function store(Request $request)
    {



        $data = $request->validate([
            "name" => "required",
            "stage_id" => "required",
            "stock" => "required|min:0",
            "img" => "nullable|mimes:jpeg,png,jpg,gif,webp",
            "gender" => "required",
            "price" => "required",
            "sell_price" => "required",
            'variants.*.size' => 'nullable|string|max:255',
            'variants.*.quantity' => 'nullable|integer|min:0',
        ], [
            "stage_id.required" => "يرجي اختيار مرحلة",
        ]);


        if ($data["sell_price"] < $data["price"]) {
            return redirect()->back()->with("error", "لا يمكن ان يكون سعر البيع اقل من سعر المنتج");
        }

        DB::beginTransaction();

        $data["sku"] = generateProductSKU();


        if (isset($data["img"])) {
            $data["img"] = Storage::put("public/products", $data["img"]);
        }

        $product = product::create($data);

        if (isset($data["variants"])) {
            foreach ($data["variants"] as $variant) {

                if (variant::where("value", $variant["size"])->where("product_id", $product->id)->exists()) {
                    DB::rollBack();
                    return redirect()->back()->with("error", "الخاصية" . " " . $variant["size"] . " " . "موجودة من قبل");
                }
                variant::create([
                    "product_id" => $product->id,
                    "value" => $variant["size"],
                    "stock" => $variant["quantity"],
                ]);
            }
        }

        DB::commit();


        return redirect()->back()->with("success", "تم الاضافة بنجاح");
    }

    function showHideProduct(Request $request)
    {

        try {

            $product = product::findOrFail($request->id);

            $product->update([
                "show" => "$request->show"
            ]);


            return json(["status" => "done", "show" => $product->show]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "show" => $product->show]);
        }
    }

    public function destroy(Request $request)
    {

        $product = product::findOrFail($request->delete_id);
        $product->update(["show" => 0]);
        $product->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }

    public function restore($id)
    {

        try {

            $product = product::withTrashed()->find($id);
            $product->restore();

            return Redirect::back()->with("success", value: "تم استرجاع المنتج بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الاسترجاع");
        }
    }

    public function edit(product $product)
    {
        $stages = stage::get();

        return view('admin/products/edit', compact('stages', "product"));
    }

    public function update(Request $request, product $product)
    {
        $data = $request->validate([
            "name" => "required",
            "stage_id" => "required",
            "img" => "nullable|mimes:jpeg,png,jpg,gif,webp",
            "gender" => "required",
            "price" => "required",
            "sell_price" => "required",
            "show" => "required",
            "stock" => "required|min:0",
        ], [
            "stage_id.required" => "يرجي اختيار مرحلة",
        ]);

        if (isset($data["img"])) {
            $data["img"] = Storage::put("public/products", $data["img"]);
        }

        $product->update($data);

        return redirect()->back()->with("success", "تم التعديل بنجاح");
    }




    public function update_vairant(Request $request)
    {
        $variant = variant::findOrFail($request->value_id);

        $variant->update([
            "value" => $request->new_value_input,
            "stock" => $request->new_stock_input,
        ]);

        return redirect()->back()->with("success", "تم التعديل بنجاح");
    }

    function variants(Request $request, product $product)
    {
        $data = $request->validate([
            'variants.*.size' => 'nullable|string|max:255',
            'variants.*.quantity' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();


        if (isset($data["variants"])) {
            foreach ($data["variants"] as $variant) {


                if (variant::where("value", $variant["size"])->where("product_id", $product->id)->exists()) {
                    DB::rollBack();
                    return redirect()->back()->with("error", "الخاصية" . " " . $variant["size"] . " " . "موجودة من قبل");
                }




                $newVariant  = variant::create([
                    "product_id" => $product->id,
                    "value" => $variant["size"],
                    "stock" => $variant["quantity"],
                    "sku" =>  generateProductSKU()
                ]);

                $warehouseProduct = warehouseProduct::where('product_id', $product->id)->first();

                if ($warehouseProduct) {
                    WarehouseProductVariant::create([
                        'warehouse_product_id' => $warehouseProduct->id,
                        'variant_id' => $newVariant->id,
                        'stock' => 0,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->back()->with("success", "تم الاضافة بنجاح");
    }

    public function variants_destroy(Request $request)
    {
        $variant = Variant::findOrFail($request->delete_id);

        $isLinkedToWarehouse = WarehouseProductVariant::where('variant_id', $variant->id)->first();

        if ($isLinkedToWarehouse) {

            $isLinkedToWarehouse->delete();
        }

        $variant->delete();

        return redirect()->back()->with("success", "تمت إزالة المقاس بنجاح");
    }


    public function changeOrder(Request $request)
    {


        variant::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }


    function qr(product $product)
    {
        $qr = new DNS1D();
        return json(["status" => "success", "qr" => $qr->getBarcodeSVG($product->sku, 'C128', 1.5, 32), "product" => $product]);
    }

    function qrVairant($id)
    {
        $qr = new DNS1D();

        $variant = variant::with("product")->find($id);

        return json(["status" => "success", "qr" => $qr->getBarcodeSVG($variant->sku, 'C128', 1.5, 32), "name" => " " . $variant->product->name . " " . "[ "  . $variant->value . " ]"  . " "]);
    }


    function ajaxVariant(Request $request)
    {
        $id = $request->input("query");
        $variant = variant::where('product_id', $id)->get();

        if ($variant->isNotEmpty()) {
            return json(["status" => "success", "variant" => $variant]);
        } else {
            return json(["status" => "no_variant"]);
        }
    }
}
