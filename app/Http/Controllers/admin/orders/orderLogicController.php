<?php

namespace App\Http\Controllers\admin\orders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\order;
use App\Models\variant;
use App\Models\orderDatail;
use Illuminate\Http\Request;
use App\Models\paymentHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class orderLogicController extends Controller
{
    function cancel_order(Request $request, order $order)
    {

        if ($order->status != "pending") {
            return redirect()->back()->with("success", "لا يمكن الغاء الاوردر");
        }

        $order->update(attributes: [
            "status" => "canceled",
            "reason" => $request->input("reason")
        ]);

        return redirect()->back()->with("success", "تم الغاء الاوردر بنجاح");
    }

    function return_requested(Request $request, order $order)
    {

        if ($order->status != "pending") {
            return redirect()->back()->with("success", "لا يمكن عمل استرجاع الاوردر");
        }


        $order->update(attributes: [
            "status" => "return_requested",
        ]);

        return redirect()->back()->with("success", "تم عمل طلب استرجاع للاوردر بنجاح");
    }

    function update_order($reference,  Request $request)
    {
        $order = order::where("reference", $reference)->firstOrFail();

        if ($order->status != "pending") {
            return redirect()->back()->with("error", "This order cannot be modified");
        }

        $data = $request->validate([
            "id" => "required|string",
            "variant_id" => "required|string"
        ]);

        $vairant = variant::findOrFail($data["variant_id"]);
        $datail = orderDatail::findOrFail($data["id"]);



        /*********************************************************************************************************************
         * التاكد من المنتجات
         *********************************************************************************************************************/

        $productVariants = $datail->product->variants->pluck('id')->toArray();

        if (!in_array($data["variant_id"], $productVariants)) {

            return redirect()->back()->with("error", "Please choose the package correctly");
        }




        $datail->update([
            "variant_id" => $data["variant_id"],
            "discription" =>  " " . $vairant->product->name . " " . "[ "  . $vairant->value . " ]"  . " ",
        ]);

        return redirect()->back()->with("success", "The size was changed successfully");
    }

    function changeOrderStatus(Request $request)
    {
        $order = order::findOrFail(intval($request->order_id));

        if ($order->status != "return_requested") {
            return redirect()->back()->with("success", "يجب ان يكون الاوردر return_requested");
        }

        $order->update([
            "status" => $request->status
        ]);

        return redirect()->back()->with("success", "تم تغير حالة الاوردر بنجاح");
    }


    function payment(Request $request, order $order)
    {


        $data = $request->validate([
            "amount" => "required|min:1",
            "receivedAmount" => "required|min:1",
            "paymentMethod" => "required|in:visa,bank,cash"
        ]);


        if ($order->status != 'pending') {
            return redirect()->back()->with("error", "لا يمكن عمل تسوية لي اوردر مدفوع");
        }




        try {
            DB::beginTransaction();


            paymentHistory::create([
                "order_id" => $order->id,
                "amount" => $data["receivedAmount"],
                "type" => $data["paymentMethod"],
                "auth" => auth()->id()
            ]);



            if ($order->getTotalPrice() <= $order->amount_received()) {
                $order->update([
                    "status" => "paid"
                ]);
                $user_id = $order->user_id;
                User::where('id', $user_id)->update(["own_package" => "yes"]);
            }


            DB::commit();


            return redirect()->back()->with("success", "تم استلام" . " " . $data["receivedAmount"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return redirect()->back()->with("error", $th->getMessage());
            return  $th->getMessage();
        }
    }

    function picked(Request $request, order $order)
    {
        $sku = $request->input("sku");

        DB::beginTransaction();

        $vairant = variant::where("sku", $sku)->first();

        if ($vairant == null) {
            return json(["status" => "error", "message" => "كود المنتج غلط"]);
        }

        $orderDatail  =  orderDatail::where("order_id", $order->id)->where("variant_id", $vairant->id)->where("picked", "0")->first();


        if ($orderDatail == null) {

            $orderDatail  =  orderDatail::where("order_id", $order->id)->where("variant_id", $vairant->id)->where("picked", "1")->first();

            if ($orderDatail) {
                return json(data: ["status" => "error", "message" => "تم تسليم هذا المنتج من قبل"]);
            } else {
                return json(data: ["status" => "error", "message" => "كود المنتج غلط"]);
            }
        }

        if ($orderDatail->picked) {
            return json(["status" => "error", "message" => "تم تسليم هذا المنتج من قبل"]);
        }

        $vairant_in_warehouse = $orderDatail->variant->get_vairant_in_warehouse();



        if (is_null($vairant_in_warehouse)) {
            return json(["status" => "error", "message" => "هذا المنتج لا يوجد في اي مخزن فرعي"]);
        } else {
            if ($vairant_in_warehouse->stock == 0) {
                return json(["status" => "error", "message" => "لا يوجد استوك في المخزن الفرعي"]);
            }
        }


        if ($vairant_in_warehouse->stock  < $orderDatail->qnt) {
            return json(["status" => "error", "message" => "الكمية المتاحة في المخزن لا تساوي الكمية المطلوبة"]);
        }
        // if (isProductVariantOutOfStock($orderDatail->product_id,$vairant_in_warehouse)) {
        //     return json(["status" => "error", "message" => "الكمية المتاحة في المخزن لا تساوي الكمية المطلوبة"]);
        // }




        $orderDatail->update([
            "picked" => "1",
            "picked_at" => Carbon::now()
        ]);

        $vairant_in_warehouse->update([
            "stock" => $vairant_in_warehouse->stock - $orderDatail->qnt,
        ]);



        $existsPicked0 = orderDatail::where('order_id', $order->id)
            // ->where('variant_id', $vairant->id)
            ->where('picked', 0)
            ->exists();

        $existsPicked1 = orderDatail::where('order_id', $order->id)
            // ->where('variant_id', $vairant->id)
            ->where('picked', 1)
            ->exists();

        if (!$existsPicked0) {
            $order->update([
                "status" => "picked",
            ]);
        } elseif ($existsPicked0 && $existsPicked1) {
            $order->update([
                "status" => "partially_picked",
            ]);
        }



        DB::commit();


        return json(["status" => "success", "message" => "تم تسليم المنتج"]);
    }
    function return(Request $request, order $order)
    {
        $sku = $request->input("sku");

        DB::beginTransaction();

        $vairant = variant::where("sku", $sku)->first();

        if ($vairant == null) {
            return json(["status" => "error", "message" => "كود المنتج غلط"]);
        }

        $orderDatail  =  orderDatail::where("order_id", $order->id)->where("variant_id", $vairant->id)->where("picked", "1")->first();


        if ($orderDatail == null) {

            $orderDatail  =  orderDatail::where("order_id", $order->id)->where("variant_id", $vairant->id)->where("picked", "0")->first();

            if ($orderDatail) {
                return json(data: ["status" => "error", "message" => " لم يتم تسليم هذا المنتج من قبل"]);
            } else {
                return json(data: ["status" => "error", "message" => "كود المنتج غلط"]);
            }
        }

        if (!$orderDatail->picked) {
            return json(["status" => "error", "message" => " لم يتم تسليم هذا المنتج من قبل"]);
        }


        $orderDatail->update([
            "picked" => "0",
        ]);


        $vairant_in_warehouse = $orderDatail->variant->get_vairant_in_warehouse();

        if (is_null($vairant_in_warehouse)) {
            return json(["status" => "error", "message" => "هذا المنتج لا يوجد في اي مخزن فرعي"]);
        }

        $vairant_in_warehouse->update([
            "stock" => $vairant_in_warehouse->stock + $orderDatail->qnt,
        ]);








        $existsPicked1 = orderDatail::where('order_id', $order->id)
            ->where('variant_id', $vairant->id)
            ->where('picked', 1)
            ->exists();


        if ($existsPicked1) {
            $order->update([
                "status" => "partially_picked",
            ]);
        } elseif (!$existsPicked1) {
            $order->update([
                "status" => "pending",
            ]);
        }



        DB::commit();


        return json(["status" => "success", "message" => "تم استرجاع المنتج"]);
    }
}
