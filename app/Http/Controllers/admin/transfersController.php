<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\transfer;
use App\Models\transferOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class transfersController extends Controller
{

    public function index()
    {

        $all = transfer::get();
        return view('admin/transfer/index', compact('all'));
    }

    public function destroy(Request $request)
    {

        $transfer = transfer::findOrFail($request->delete_id);
        $transfer->delete();
        return Redirect::back()->with("success", "تم الازالة بنجاح");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string',
        ]);

        $data["code"] = generateCode(new transfer(), "TR");

        $data["enteredBy"] = auth()->id();



        transfer::create($data);
        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            "idInput" => "required|string",
            'nameInput' => 'nullable|string',
        ]);

        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [str_replace('Input', '', $key) => $value];
        })->toArray();


        $transfer = transfer::findOrFail($data["id"]);

        $transfer->update($data);
        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }


    function show($code)
    {
        $transfer = transfer::with("orders")->where("code", $code)->firstOrFail();

        $totalFees = order::withoutTransfers()->whereIn("status", ["paid", "picked"])->get()->sum(function ($order) {
            return $order->fees();
        });

        $totalFeesInTrans = $transfer->orders()->get()->sum(function ($order) {
            return $order->fees();
        });



        return view("admin/transfer/show", compact("transfer", "totalFees", "totalFeesInTrans"));
    }


    public function orders(transfer $transfer, Request $request)
    {


        if ($transfer->type != "pending") {
            return redirect()->back()->with("error", "لا يمكن اضافة اوردر لتحويل مدفوع")->withInput();
        }


        DB::beginTransaction();

        //


        $targetAmount = $request->amount;

        $orders = order::withoutTransfers()->whereIn("status", ["paid", "picked"])->get();

        $closestOrders = [];
        $total = 0;

        $orders = $orders->sortBy(function ($order) {
            return $order->fees();
        });

        foreach ($orders as $order) {
            if ($order->fees() <= $targetAmount) {
                $closestOrders[] = $order->id;

                $total += $order->fees();

                if ($total > $targetAmount) {
                    break;
                }
            }
        }


        //

        foreach ($closestOrders as $id) {
            $order = order::where("id", trim($id))->first();


            if ($order == null) {
                DB::rollBack();
                return redirect()->back()->with("error", "لاوردر" . " " . $id . " غير موجود ")->withInput();
            }

            if (!in_array($order->status, ["paid", "picked"])) {
                DB::rollBack();
                return redirect()->back()->with("error", "لا يمكن اضافة الاوردر" . " " . $id . " لانه غير مدفوع ")->withInput();
            }


            if (transferOrder::where("order_id", $order->id)->exists()) {
                DB::rollBack();
                return redirect()->back()->with("error", "الاوردر ده" . " " . $id . " مضاف قبل كده ")->withInput();
            }

            transferOrder::create([
                "order_id" => $order->id,
                "transfer_id" => $transfer->id
            ]);
        }

        DB::commit();

        return redirect()->back()->with("success", "تم الاضافة بنجاح");
    }

    public function paid(Transfer $transfer, Request $request)
    {
        DB::beginTransaction();

        try {


            $transfer->update([
                "type" => "paid",
                "paidBy" => auth()->id(),
                "paid_at" => Carbon::now()
            ]);

            $transfer->orders()->update(['sending' => 1]);

            DB::commit();


            return redirect()->back()->with('success', 'تم تحديث حالة التحويل إلى مدفوع بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'فشل في تحديث حالة التحويل أو الطلبات');
        }
    }
    public function confirm(Transfer $transfer, Request $request)
    {
        DB::beginTransaction();

        try {


            $transfer->update([
                "type" => "complete",
                "confirmBy" => auth()->id(),
                "confirmed_at" => Carbon::now()
            ]);

            $transfer->orders()->update(['received' => 1]);

            DB::commit();


            return redirect()->back()->with('success', 'تم تحديث حالة التحويل إلى مكتمل بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'فشل في تحديث حالة التحويل أو الطلبات');
        }
    }


    function checkAmount(Request $request)
    {

        $targetAmount = $request->amount;

        $orders = order::withoutTransfers()->whereIn("status", ["paid", "picked"])->get();

        $closestOrders = [];
        $total = 0;

        $orders = $orders->sortBy(function ($order) {
            return $order->fees(); // استخدم الدالة fees() لحساب الرسوم
        });

        foreach ($orders as $order) {
            if ($order->fees() <= $targetAmount) {
                $closestOrders[] = $order->id;

                $total += $order->fees();

                if ($total > $targetAmount) {
                    break;
                }
            }
        }


        return json(["status" => "success", "message" => "المبلغ المتاح" . " " . $total]);
    }
}
