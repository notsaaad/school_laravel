<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\warehouse;
use App\Models\WarehouseProduct;
use App\Models\WarehouseProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class warehouseController extends Controller
{

  public function index()
  {

    $all = warehouse::get();

    return view('admin/warehouse/index', compact('all'));
  }

  public function destroy(Request $request)
  {

    $warehouse = warehouse::findOrFail($request->delete_id);
    $warehouse->delete();
    return Redirect::back()->with("success", "تم الازالة بنجاح");
  }


  public function store(Request $request)
  {


    $data = $request->validate([
      "name" => "required|string"
    ]);

    warehouse::create($data);

    return redirect()->back()->with("success", "تم الاضافة بنجاح");
  }

  function edit(warehouse $warehouse)
  {


    $products = Product::whereDoesntHave('warehouses', function($query) use ($warehouse) {
      $query->where('warehouse_id', $warehouse->id);
      })->get();

    $warehouseProducts = WarehouseProduct::with(['product', 'variants.variant'])
      ->where('warehouse_id', $warehouse->id)
      ->get();





    return view("admin/warehouse/edit", compact("warehouse", "products", "warehouseProducts"));
  }

  function update(Request $request, warehouse $warehouse)
  {

    $rules = $messages = [];

    $data = $request->validate([
      "name" => "required|string"
    ]);



    $warehouse->update($data);

    return redirect()->back()->with("success", "تم التعديل بنجاح");
  }

  public function changeOrder(Request $request)
  {


    warehouse::where('id', $request->id)->update([
      'order' => $request->order + 1
    ]);

    return true;
  }

  function products(Request $request)
  {
    $data = $request->validate([
      'products' => 'required|array',
      'products.*' => 'exists:products,id',
      "warehouse_id" => "required|exists:warehouses,id"
    ], [
      "products.required" => "يرجي اختيار منتج"
    ]);

    $warehouse = Warehouse::find($data["warehouse_id"]);
    $warehouseId = $data["warehouse_id"];
    try {
      DB::beginTransaction();

      foreach ($data["products"] as $productId) {
        $product = Product::where('id', $productId)
        ->whereDoesntHave('warehouses', function ($query) use ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        })
        ->first();

        if ($product) {
          $warehouseProduct = WarehouseProduct::firstOrCreate([
            'warehouse_id' => $warehouse->id,
            'product_id' => $productId,
          ]);

          foreach ($product->variants as $variant) {
            WarehouseProductVariant::firstOrCreate([
              'warehouse_product_id' => $warehouseProduct->id,
              'variant_id' => $variant->id,
              'stock' => 0 // أو القيمة التي تفضلها
            ]);
          }
        }
      }

      DB::commit();

      return redirect()->back()->with("success", "تم الاضافة بنجاح");
    } catch (\Throwable $th) {
      DB::rollBack();
      throw new \Exception($th->getMessage());
    }
  }


  public function products_destroy(Request $request, Warehouse $warehouse)
  {
    $validatedData = $request->validate([
      'delete_id' => 'required|exists:warehouse_products,product_id'
    ], [
      'delete_id.required' => 'يرجى تحديد المنتج الذي تريد حذفه.',
      'delete_id.exists' => 'المنتج المحدد غير موجود في المخزن.'
    ]);

    // استرجاع المنتج بناءً على product_id في المخزن
    $warehouseProduct = $warehouse->warehouseProducts()->where('product_id', $validatedData['delete_id'])->first();

    // تحقق مما إذا كان المنتج موجودًا
    if ($warehouseProduct) {
      // حذف السجلات المرتبطة في warehouse_product_variants
      $warehouseProduct->variants()->delete();

      // قم بحذف المنتج من warehouse_products
      $warehouseProduct->delete();

      return redirect()->back()->with("success", "تمت إزالة المنتج بنجاح");
    }

    return redirect()->back()->with("error", "المنتج غير موجود في المخزن");
  }

public function availableProducts($warehouseId)
{
    $variants = WarehouseProductVariant::with(['variant', 'warehouseProduct.product'])
        ->whereHas('warehouseProduct', function ($q) use ($warehouseId) {
            $q->where('warehouse_id', $warehouseId);
        })
        ->where('stock', '>', 0)
        ->get();

    return response()->json($variants->map(function ($item) {
        return [
            'variant_id' => $item->variant->id,
            'label' => $item->warehouseProduct->product->name . ' - ' . $item->variant->value . ' (المتوفر: ' . $item->stock . ')',
        ];
    }));
}
}
