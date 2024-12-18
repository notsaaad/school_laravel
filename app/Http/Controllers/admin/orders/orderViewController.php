<?php

namespace App\Http\Controllers\admin\orders;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\User;
use Illuminate\Http\Request;

class orderViewController extends Controller
{
    function index()
    {

        $orders = order::paginate(50);
        // return $orders[0]->user;
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
