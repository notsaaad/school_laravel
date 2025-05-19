<?php

namespace App\Http\Controllers\admin;

use App\Models\order;
use App\Models\product;
use App\Models\variant;
use App\Models\orderDatail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersUserController extends Controller
{
    function items_makeOrder(Request $request){
      $order     = order::where('reference', $request->addTo)->firstOrFail();

      $existingDetail = orderDatail::where('order_id', $order->id)
          ->where('product_id', $request->product_id)
          ->where('variant_id', $request->variant_id)
          ->first();
        $productName  = product::find($request->product_id)?->name ?? '';
        $variantValue = variant::find($request->variant_id)?->value ?? '';
        $description  = "{$productName} [{$variantValue}]";
      if ($existingDetail) {
          $existingDetail->qnt += (int) $request->stock;
          $existingDetail->save();
      } else {

          orderDatail::create([
              'order_id'    => $order->id,
              'product_id'  => $request->product_id,
              'variant_id'  => $request->variant_id,
              'discription' => $description,
              'qnt'         => (int) $request->stock,
              'picked'      => 0,
              'price'       => 0,
              'sell_price'  => 0,
          ]);
      }

      $msg = recalculateOrderTotal($request->addTo);

            return back()->with('success', 'تم الاضافة بنجاح')
            ->with('order_update', $msg);
    }
}
