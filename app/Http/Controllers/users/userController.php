<?php

namespace App\Http\Controllers\users;


use Carbon\Carbon;
use App\Models\fee;
use App\Models\cart;
use App\Models\User;
use App\Models\order;
use App\packageTriat;
use App\Models\region;
use App\Models\package;
use App\Models\product;
use App\Models\variant;
use App\Models\busOrder;
use App\Models\busSetting;
use App\Models\orderDatail;
use Illuminate\Http\Request;
use App\Models\studentDetail;
use App\Models\packageProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Mail\user\subscibeBusMailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\PackageValidationException;

class userController extends Controller
{
    function Hatem_handel_All_with_nat_id(){
        $code_el_mo7afza = "";
        $year            = "";
        $month           = "";
        $day             = "";
        $user_id = auth()->ID();
        $have_nat_id = "Not found For ID = $user_id";
        $studentDetail = studentDetail::where('student_id', $user_id)->get();
        // return $studentDetail;
        if(isset($studentDetail[0]->national_id)){
            if( $studentDetail[0]->national_id !=null){
              $nat_id = $studentDetail[0]->national_id;
              $count = strlen($nat_id);

              if($count == 14 && $studentDetail[0]->region_id ==null){
                  $have_nat_id = "Found For ID = $user_id";
                  $code_el_mo7afza = $nat_id[0];
                  if($code_el_mo7afza == 2){
                      $year[0] = "1";
                      $year[1] = "9";
                  }else{
                      $year[0] = "2";
                      $year[1] = "0";
                  }
                  $year[2]             = $nat_id[1];
                  $year[3]             = $nat_id[2];
                  $month[0]            = $nat_id[3];
                  $month[1]            = $nat_id[4];
                  $day[0]              = $nat_id[5];
                  $day[1]              = $nat_id[6];
                  $brithday            = "$year-$month-$day";
                  $country_id          = "$nat_id[7]$nat_id[8]";
                  $country  = (int) $this->getmo7afza($country_id);
                  studentDetail::where(["student_id"=> "$user_id"])->update(
                      ["birth_date"=> "$brithday","region_id" => "$country"],
                      );
              }
          }
        }


    }

    // function getmo7afza($number){

    //     $country = 1;
    //     $country_return  = region::where('id', $number)->get()->first();
    //     if(isset($country_return['id']))
    //     $country = $country_return['id'];
    //     return $country;
    // }
    use packageTriat;
    function login(Request $request)
    {
        $data = $request->validate([
            "id" => "required",
        ]);

        $user = User::where("code", $data["id"])->first();

        if (!$user) {
            return redirect()->back()->with("error", "id number is not correct")->withInput();
        }

        Auth::login($user);
        $this->Hatem_handel_All_with_nat_id();

        return redirect("students/home");
        // return redirect("students/hatem-check");
    }

    public function show_package(package $package)
    {
        $products = $package->student_products;


        return view("users.show_package", compact("products", "package"));
    }
    public function logout()
    {
        Auth::logout();

        return redirect(to: '/');
    }

    public function storeOrder(Request $request, package $package)
    {

        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return json(["status" => "ValidationError", "Errors" => $validator->errors()]);
        }


        $data = $validator->validated();
        $variantIds = $data["data"];



        try {
            $this->checkPackage($package, $variantIds);
        } catch (PackageValidationException $e) {
            return response()->json(['status' => 'CoustomErrors', 'message' => $e->getMessage()]);
        }




        try {
            DB::beginTransaction();

            $order = order::create([
                "reference" => generateUniqueReference(),
                "user_id" => auth()->id(),
                "package_id" => $package->id,
                "price" => $package->price,
                "service_expenses" => settings('service_expenses'),
                "type" => "package"
            ]);




            foreach ($data["data"] as $id) {
                $vairant  = variant::find($id);
                orderDatail::create([
                    "order_id" => $order->id,
                    "product_id" => $vairant->product_id,
                    "variant_id" => $vairant->id,
                    "discription" =>  " " . $vairant->product->name . " " . "[ "  . $vairant->value . " ]"  . " ",
                ]);
            }

            DB::commit();

            return json(["status" => "success", "message" => "Your order his been confirmed successfully . Please go to the school to process payment "]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return json(["status" => "CoustomErrors", "message" => "Error !"]);
        }
    }


    function orders()
    {
        $orders = order::where('user_id', auth()->id())->get();

        return view("users.orders.index", compact("orders"));
    }


    function show_order($reference)
    {
        $order = order::where("reference", $reference)->firstOrFail();

        return view("users.orders.show", compact("order"));
    }

    public function GetOrderDetailsAjax($id)
    {
        try {
            $data = GetOrderDetailsAjax($id);
            return json(["status" => "success", "data" => $data]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "message" => "هناك خطأ ما"]);
        }
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

    function cancel_order(Request $request, order $order)
    {

        if ($order->status != "pending") {
            return redirect()->back()->with("error", "You can not do this");
        }


        $order->update(attributes: [
            "status" => "canceled",
            "reason" => $request->input("reason")
        ]);

        return redirect()->back()->with("success", "The order has been canceled successfully");
    }



    function items()
    {

        if (auth()->user()->own_package == "no") {
            $packages_ids = order::where("status", "picked")->where("user_id", auth()->id())->distinct()->pluck("package_id")->unique("package_id")->toArray();


            $products_ids = packageProduct::whereIn("package_id", $packages_ids)->distinct()->pluck("product_id")->toArray();


            $products = product::where(function ($query) {
                $query->where('gender', auth()->user()->gender)
                    ->orWhere('gender', 'both');
            })->where("stage_id", auth()->user()->stage_id)->where("show", "1")->whereIn("id", $products_ids)->get();
        } else {

            $products = product::where(function ($query) {
                $query->where('gender', auth()->user()->gender)
                    ->orWhere('gender', 'both');
            })->where("stage_id", auth()->user()->stage_id)->where("show", "1")->get();
        }






        return view("users.items", compact("products"));
    }

    function items_makeOrder(Request $request)
    {


        $data = $request->validate([
            "product_id" => "required|integer",
            "stock" => "required|integer|min:1",
            "variant_id" => "required|integer",
            "addTo" => "nullable",
        ]);

        $data["qnt"] = $data["stock"];
        $data["user_id"] = auth()->id();
        $data["type"] = "items";


        DB::beginTransaction();

        if (cart::where("variant_id", $data["variant_id"])->where("user_id", auth()->id())->exists()) {
            DB::rollBack();
            return  redirect()->back()->with("error", 'This product is already added to the cart.');
        }


        $variant = variant::where("product_id", $data["product_id"])->findOrFail($data["variant_id"]);


        if (auth()->user()->role == "user" && !$variant->canMakeOrderInThisVairant()) {
            DB::rollBack();
            return  redirect()->back()->with("error", 'You cannot make an order on this product Now .');
        }



        if ($data["addTo"] == null) {
            cart::create($data);
            DB::commit();

            return redirect()->back()->with("success", "The product has been added to the cart successfully");
        } else {

            cart::where("user_id", auth()->user()->id)->delete();

            cart::create($data);

            $order = order::where("reference", $data["addTo"])->where("status", "pending")->firstOrFail();



            $carts = cart::with("product", "variant")->where("user_id", auth()->user()->id)->get();


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

            return redirect()->back()->with("success", "The product has been added to Your Order successfully");
        }
    }

    public function destroyDetails(Request $request)
    {

        $data = $request->validate([
            "delete_id" => "required|string",
        ]);

        $details = orderDatail::with("product", "variant")->with(["order" => function ($q) {
            $q->withCount("details");
        }])->findOrFail($data['delete_id']);

        if ($details->order->details_count == 1) {
            return redirect()->back()->with("error", "The order cannot be left empty");
        }

        $details->delete();

        return redirect()->back()->with("success", "The product was removed successfully");
    }

    function pending_payments()
    {

        return view("users.pending_payments", get_defined_vars());
    }



    function subscribe_in_bus(Request $request)
    {
        $data = $request->validate([
            "address" => "required|string",
            "region_id" => "required|integer",
            "place_id" => "required|integer",
            "bus_setting_id" => "required|integer",
        ]);

        DB::beginTransaction();


        $old = busOrder::where("date", ">", Carbon::now())->first();


        if ($old) {
            DB::rollBack();
            return redirect()->back()->with("error", "You are already subscribed In Bus");
        }

        $bus_setting = busSetting::findOrFail($data["bus_setting_id"]);

        $user_email = Auth()->user()->email;

        Mail::to($user_email)->send(new subscibeBusMailer);
        busOrder::create([
            "user_id" => auth()->id(),
            "bus_id" => $bus_setting->bus_id,
            "address" => $data["address"],
            "title" => $bus_setting->name,
            "price" => $bus_setting->price,
            "go_chairs_count" => $bus_setting->go_chairs_count,
            "return_chairs_count" => $bus_setting->return_chairs_count,
            "date" => $bus_setting->date,
            "region_id" => $data["region_id"],
            "place_id" => $data["place_id"],
        ]);

        DB::commit();


        return redirect()->back()->with(
            "success",
            "The bus subscription has been completed successfully. We will  Call You soon."
        );
    }
}
