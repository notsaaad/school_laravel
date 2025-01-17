<?php

namespace App\Http\Controllers\admin\orders;

use App\Models\User;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class orderViewController extends Controller
{
    function index()
    {

        $orders = order::paginate(50);

        return view("admin/orders/index", compact("orders"));
    }

    function show($reference)
    {
        $order = order::where("reference", $reference)->firstOrFail();

        return view("admin.orders.show", compact("order"));
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
    function DeliverdAll(Request $request){
        $id = $request->id;
        $reference = $request->code;

        $orderDetails = DB::table('order_datails')->where('order_id', $id)->update(['picked'=> 1]);
        DB::table('orders')->where('id', $id)->update(['status' => 'picked', 'sending'=> 1, 'received'=>1]);
        return redirect()->route('order.single_order', $reference)->with(['success'=>'تم تسليم جميع المنتجات بنجاح']);

    }
    function search(Request $request)
    {

        $filters = [
            // 'reference' => $request->reference ?? '',
            'status' => $request->status ?? '',
        ];


        $orders = new order();

        $orders = order::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    $query->where($key, 'LIKE', "%{$value}%");
                }
            }
        });

        if (!empty($request->reference)) {

            // Split the string by commas or spaces
            $filteredArray = preg_split('/[\s,]+/', trim($request->reference));

            // Filter out empty values
            $filteredArray = array_filter($filteredArray, function ($value) {
                return $value !== "";
            });

            // Trim each element in the array
            $filteredArray = array_map('trim', $filteredArray);



            // Apply the filtered array to the query
            $orders = $orders->whereIn("reference", $filteredArray);
        }



        if (isset($request->code)) {
            $user = User::where("code", $request->code)->first();
            $orders = $orders->where("user_id", $user->id);
        }


        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ",   $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";

            $date != ""  ? $orders = $orders->whereDate("created_at", '>=', $startDate) : "";
            isset($endDate) && !empty($endDate)  ? $orders = $orders->whereDate("created_at", '<=', $endDate) :  $orders = $orders->whereDate("created_at", '<=', $startDate);
        }



        $orders = $orders->paginate(50);

        return view("admin/orders/index", compact("orders"));
    }
}
