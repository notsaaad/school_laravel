<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\product;
use App\Models\variant;
use App\Models\warehouse;
use Illuminate\Http\Request;
use App\Models\StockTransfer;
use App\Models\WarehouseProduct;
use Illuminate\Support\Facades\DB;
use App\Models\WarehouseProductVariant;

class StockTransferController extends Controller
{
    public function create(Request $request)
    {
      $selectedWarehouseId = $request->input('warehouse_id'); // دا هو الوجهة

        if (!$selectedWarehouseId || !is_numeric($selectedWarehouseId)) {
            return redirect()->back()->with('error', 'لم يتم تحديد المخزن الوجهة');
        }

        // المخازن المصدر = كل المخازن ما عدا المخزن الوجهة
        $warehouses = Warehouse::where('id', '!=', $selectedWarehouseId)->get();

        $warehouse = Warehouse::findOrFail($selectedWarehouseId);

        return view('admin.warehouse.stocktransfer', compact(
            'warehouses',
            'selectedWarehouseId',
            'warehouse'
        ));
    }






    public function store(Request $request)
    {
      $request->validate([
          'from_warehouse_id' => 'required|exists:warehouses,id',
          'to_warehouse_id'   => 'required|different:from_warehouse_id|exists:warehouses,id',
          'variant_id'        => 'required|exists:variants,id',
          'quantity'          => 'required|integer|min:1',
      ]);

      DB::beginTransaction();

      try {
          $variant = Variant::findOrFail($request->variant_id);
          $productId = $variant->product_id;

          // المخزن المصدر
          $fromWarehouseProductId = WarehouseProduct::where('warehouse_id', $request->from_warehouse_id)
              ->where('product_id', $productId)
              ->value('id');

          if (!$fromWarehouseProductId) {
              throw new \Exception('المنتج غير موجود في المخزن المصدر.');
          }

          $source = WarehouseProductVariant::where('warehouse_product_id', $fromWarehouseProductId)
              ->where('variant_id', $request->variant_id)
              ->first();

          if (!$source || $source->stock < $request->quantity) {
              throw new \Exception('الكمية غير متاحة للتحويل.');
          }

          $source->stock -= $request->quantity;
          $source->save();

          // المخزن الوجهة
          $toWarehouseProduct = WarehouseProduct::firstOrCreate([
              'warehouse_id' => $request->to_warehouse_id,
              'product_id'   => $productId,
          ]);

          $target = WarehouseProductVariant::firstOrCreate([
              'warehouse_product_id' => $toWarehouseProduct->id,
              'variant_id'           => $request->variant_id,
          ], [
              'stock' => 0,
          ]);

          $target->stock += $request->quantity;
          $target->save();

          // سجل التحويل
          StockTransfer::create([
              'from_warehouse_id' => $request->from_warehouse_id,
              'to_warehouse_id'   => $request->to_warehouse_id,
              'product_id'        => $productId,
              'variant_id'        => $request->variant_id,
              'quantity'          => $request->quantity,
          ]);

          DB::commit();

          return redirect()->route('admin.warehouse.edit', $request->to_warehouse_id)
                          ->with('success', 'تم التحويل بنجاح');
      } catch (\Exception $e) {
          DB::rollBack();
          return back()->with('error', $e->getMessage());
      }
    }

    public function showTransfers($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);

        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'product', 'variant'])
            ->where(function ($query) use ($warehouseId) {
                $query->where('from_warehouse_id', $warehouseId)
                      ->orWhere('to_warehouse_id', $warehouseId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.warehouse.showtranfare', compact('warehouse', 'transfers'));
    }
}
