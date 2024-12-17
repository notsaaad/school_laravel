<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\fee;
use App\Models\feesPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function calculatePayment(Request $request, User $user, fee $fee)
    {

        $amountReceived = $request->input('amount');
        $message = '';

        $old_fee_paid = $fee->userPayments($user->id)->sum('amount');

        $hasUserPayments = $fee->items()->whereHas('payments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();


        if ($old_fee_paid) {
            return response()->json(['message' => "تم دفع هذه المصاريف من قبل"]);
        }

        if ($amountReceived >= $fee->price && $old_fee_paid == 0 && !$hasUserPayments) {

            $message = "ستدفع المبلغ كاملاً وقدره {$fee->price} جنيه، " . "<br>" . "وسيتم تسديد جميع اقساط ( {$fee->name} ).";

            if ($amountReceived != $fee->price) {
                $amountReceived -= $fee->price;
                $message .=  "<br>" . "والمبلغ المتبقي هو {$amountReceived}.";
            }
        } else {


            foreach ($fee->items as $item) {
                if ($amountReceived > 0) {
                    $amountToPay = min($item->price - $item->payments()->where('user_id', $user->id)->sum('amount'), $amountReceived);
                    $message .= "سيتم دفع مبلغ {$amountToPay} من ( {$item->name} ) ." . "<br>";
                    $amountReceived -= $amountToPay;
                }
            }

            if ($amountReceived > 0) {
                $message .= "والمبلغ المتبقي هو {$amountReceived}.";
            }
        }

        return response()->json(['message' => $message]);
    }
    public function paid(Request $request, User $user, fee $fee)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);


        DB::beginTransaction();

        $receive_amount = $data["amount"];

        $hasUserPayments = $fee->items()->whereHas('payments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();


        if ($fee->payments()->sum('amount') == 0 && $receive_amount >= $fee->price && !$hasUserPayments) {


            if ($receive_amount >  $fee->price) {
                DB::rollBack();
                return back()->with("error", "من فضلك قم استلم مبلغ {$fee->price} فقط");
            }


            $fee->payments()->create([
                'user_id' => $user->id,
                'amount' => $fee->price,
                "auth_id" => auth()->id()

            ]);
        } else {

            $totalPrice = $fee->items->sum('price');
            $totalPayments = $fee->items->sum(function ($item) use ($user) {
                return $item->userPayments($user->id)->sum('amount');
            });
            $remainingAmount = $totalPrice - $totalPayments;

            if ($remainingAmount == 0) {
                DB::rollBack();
                return back()->with("error", "لا يوجد مبالغ متبقية");
            }


            if ($receive_amount >  $remainingAmount) {
                DB::rollBack();
                return back()->with("error", "من فضلك قم استلم مبلغ {$remainingAmount} فقط");
            }



            foreach ($fee->items as $item) {

                $old = $item->userPayments($user->id)->sum('amount');

                if ($receive_amount > 0 &&  $item->price > $old) {

                    $amount_to_pay = min($item->price - $old,  $receive_amount);

                    $item->payments()->create([
                        'user_id' => $user->id,
                        'amount' => $amount_to_pay,
                        "auth_id" => auth()->id()
                    ]);

                    $receive_amount -= $amount_to_pay;
                }
            }
        }

        DB::commit();


        return back()->with("success", "تم السداد");
    }
}
