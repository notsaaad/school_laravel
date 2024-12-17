<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\cart;
use App\Models\order;
use App\Models\orderDatail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class cartController extends Controller
{
    function index()
    {

        $carts = cart::where("user_id", auth()->user()->id)->get();


        // DeleteFromCartAfterCheck($carts);


        // $carts = cart::where("user_id", auth()->user()->id)->get();


        return view("users/cart", compact("carts"));
    }

    public function destroy(Request $request)
    {

        $cart = cart::where("user_id", auth()->user()->id)->findOrFail($request->delete_id);


        try {
            $cart->delete();

            return Redirect::back()->with("success", "Removed successfully");
        } catch (\Exception $e) {
            return Redirect::back()->with("error", "Failed to remove");
        }
    }

    function checkOut(Request $request)
    {

        $carts = cart::with("product", "variant")->where("user_id", auth()->user()->id)->get();

        try {
            DB::beginTransaction();

            $order = order::create([
                "reference" => generateUniqueReference(),
                "user_id" => auth()->id(),
                "price" => 0,
                "service_expenses" => settings('service_expenses')
            ]);


            foreach ($carts as $cart) {

                $product =  $cart->product;
                $vairant =   $cart->variant;


                for ($i = 1; $i <=  $cart->qnt; $i++) {
                    orderDatail::create([
                        "order_id" => $order->id,
                        "product_id" => $product->id,
                        "variant_id" => $vairant->id,
                        "discription" =>  " " . $vairant->product->name . " " . "[ "  . $vairant->value . " ]"  . " ",
                        "price" => $product->price,
                        "sell_price" => $product->sell_price,
                        "qnt" => 1,
                    ]);
                }
            }


            cart::where("user_id", auth()->user()->id)->delete();


            DB::commit();



            return Redirect::to("students/orders")->with("success", "The Order has been purchased successfully");
        } catch (\Throwable $th) {
            DB::rollBack();

            return Redirect::back()->with("error", $th->getMessage());
        }
    }
}
