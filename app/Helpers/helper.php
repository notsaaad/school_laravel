<?php

use App\Models\cart;
use App\Models\test;
use App\Models\User;
use App\Models\order;
use App\Models\product;
use App\Models\setting;
use App\Models\warehouse;
use App\Models\orderDatail;
use Illuminate\Support\Str;
use App\Models\applicationFee;
use App\Models\WarehouseProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\orderResource;
use App\Models\WarehouseProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;


if (!function_exists('generateSlug')) {
    function generateSlug($productName)
    {
        $slug = strtolower($productName);
        $slug = preg_replace('/[^a-z0-9\p{L}]+/u', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
if (!function_exists("path")) {
    function path($img)
    {
        $img = str_replace("public", "storage", $img);

        $img = asset("$img");

        return $img;
    }
}

if (!function_exists("json")) {
    function json($data)
    {
        return response()->json($data);
    }
}

if (!function_exists("check_Email")) {
    function check_Email($email)
    {
        $email = User::withTrashed()
            ->whereNotNull('email')
            ->where('email', $email)
            ->first();


        if ($email != null) {
            return redirect()->back()
                ->withErrors("هذا البريد مسجل به من قبل")
                ->withInput();
        }

        return false;
    }
}


if (!function_exists('check_Email_Phone')) {
    function check_Email_Phone($email, $mobile)
    {
        $email = User::withTrashed()->where("email", $email)->first();
        $mobile = User::withTrashed()->where("mobile", $mobile)->first();

        if ($email != null) {
            return Redirect::back()->withErrors("هذا البريد مسجل به من قبل")->withInput();
        }
        if ($mobile != null) {
            return Redirect::back()->withErrors("هذا الرقم مسجل به من قبل")->withInput();
        }

        return false;
    }
}



function getLocale()
{
    if (session()->has('locale')) {
        return session('locale');
    } else {
        return app()->getLocale();
    }
}


if (!function_exists('settings')) {
    function settings($column)
    {

        return setting::where("key", $column)->first()?->value;
    }
}

function get_logo()
{

    return path(setting::where("key", "logo")->first()?->value);
}

function get_fav()
{

    return path(setting::where("key", "fav")->first()?->value);
}

function generateComplexPassword()
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+{}[]|;:,.<>?';
    $charactersLength = strlen($characters);
    $password = '';

    for ($i = 0; $i < 16; $i++) {
        $password .= $characters[rand(0, $charactersLength - 1)];
    }

    return $password;
}



if (!function_exists('userName')) {
    function userName($id)
    {
        return User::find($id) ?? "";
    }
}


function fixDate($date)
{

    if ($date == "") {
        return "";
    }

    $dateTimeObj = new DateTime($date);
    $date = $dateTimeObj->format('Y-m-d h:i:s A');

    return $date;
}


function generateUniqueReference()
{

    $UniqueReference =  "RE" . mt_rand(10000000, 99999999);



    while (order::where('reference', $UniqueReference)->exists()) {
        $UniqueReference =  "RE"  . mt_rand(10000000, 99999999);
    }

    return $UniqueReference;
}


if (!function_exists('GetOrderDetailsAjax')) {
    function GetOrderDetailsAjax($id)
    {

        $order = order::with(["details.product" => function ($q) {
            $q->withTrashed();
        }])->find($id);



        return new orderResource($order);
    }
}




if (!function_exists('generateProductSKU')) {
    function generateProductSKU($length = 8)
    {
        $sku = strtoupper(Str::random($length));

        while (DB::table("products")->where('sku', $sku)->exists() || DB::table("variants")->where('sku', $sku)->exists()) {
            $sku = strtoupper(Str::random($length));
        }

        return $sku;
    }
}


function get_warehouses_product_stock($product)
{
    $warehouseProduct = WarehouseProduct::whereHas("warehouse")->with(['product', 'variants.variant'])
        ->where('product_id', $product->id)
        ->first();

    return $warehouseProduct?->variants->sum('stock') ?? 0;
}




if (!function_exists('cartCount')) {
    function cartCount()
    {
        return cart::where('user_id', auth()->user()->id)->count();
    }
}





function generateCode(Model $model, $word)
{
    do {
        $UniqueReference = $word . mt_rand(10000000, 99999999);
    } while ($model->where('code', $UniqueReference)->exists());

    return $UniqueReference;
}


function can_paid($fee, $user)
{
    $firstUnpaidFee = $user->fees()
        ->whereDoesntHave('payments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->orderBy('order', 'asc')
        ->first();

    if ($firstUnpaidFee && $firstUnpaidFee->id != $fee->id) {
        return false;
    }

    $totalPrice = $fee->items->sum('price');
    $totalPayments = $fee->items->sum(function ($item) use ($user) {
        return $item->userPayments($user->id)->sum('amount');
    });
    $remainingAmount = $totalPrice - $totalPayments;

    if ($fee->items()->exists()) {
        return !$fee->userPayments($user->id)->exists() && $remainingAmount != 0;
    } else {
        return !$fee->userPayments($user->id)->exists();
    }
}


function ApplicationFee($stageId, $studyType, $yearId)
{
    return applicationFee::where('stage_id', $stageId)
        ->where('study_type', $studyType)
        ->where('year_id', $yearId)
        ->whereNull('deleted_at');
}
function testCheck($stageId, $studyType, $yearId)
{
    return test::where('stage_id', $stageId)
        ->where('study_type', $studyType)
        ->where('year_id', $yearId)
        ->whereNull('deleted_at');
}


function canEdit($application)
{
    return in_array($application->status, ['new', 'paid']);
}


  if (!function_exists('updateWarehouseStock')) {
      function updateWarehouseStock($productId, $variantId, $quantityChange)
      {
          if ($quantityChange == 0) {
              return false;
          }

          // جلب كل المخازن غير "مخزن رئيسي"
          $variantStocks = WarehouseProductVariant::where('variant_id', $variantId)
              ->whereHas('warehouseProduct', function ($query) {
                  $query->whereHas('warehouse', function ($q) {
                      $q->where('name', '!=', 'مخزن رئيسي');
                  });
              })
              ->with('warehouseProduct.warehouse')
              ->get();

          if ($variantStocks->isEmpty()) {
              return false;
          }

          // تحقق من التوفر في حالة الخصم
          if ($quantityChange < 0) {
              $totalAvailableStock = $variantStocks->sum('stock');
              $requiredQty = abs($quantityChange);

              if ($totalAvailableStock < $requiredQty) {
                  return false;
              }
          }

          // ✅ الإضافة
          if ($quantityChange > 0) {
              $stock = $variantStocks->first();
              $stock->stock += $quantityChange;
              $stock->save();

              Log::channel('stock')->info('Stock added', [
                  'action'       => 'increase',
                  'warehouse_id' => $stock->warehouseProduct->warehouse->id,
                  'product_id'   => $productId,
                  'variant_id'   => $variantId,
                  'quantity'     => $quantityChange,
                  'new_stock'    => $stock->stock,
                  'timestamp'    => now()->toDateTimeString(),
                  'initiator'    => auth()->id() ?? 'system',
              ]);
          }

          // ❌ الخصم
          else {
              $requiredQty = abs($quantityChange);

              foreach ($variantStocks as $stock) {
                  if ($stock->stock <= 0) {
                      continue;
                  }

                  $deduct = min($stock->stock, $requiredQty);
                  $stock->stock -= $deduct;
                  $stock->save();

                  Log::channel('stock')->info('Stock deducted', [
                      'action'       => 'deduct',
                      'warehouse_id' => $stock->warehouseProduct->warehouse->id,
                      'product_id'   => $productId,
                      'variant_id'   => $variantId,
                      'quantity'     => $deduct,
                      'remaining'    => $stock->stock,
                      'timestamp'    => now()->toDateTimeString(),
                      'initiator'    => auth()->id() ?? 'system',
                  ]);

                  $requiredQty -= $deduct;
                  if ($requiredQty <= 0) {
                      break;
                  }
              }
          }

          return true;
      }
  }



  if (!function_exists('isProductVariantOutOfStock')) {
      function isProductVariantOutOfStock($productId, $variantId)
      {
          $totalStock = WarehouseProductVariant::whereHas('warehouseProduct', function ($query) use ($productId) {
              $query->where('product_id', $productId)
                    ->whereHas('warehouse', function ($q) {
                        $q->where('name', '!=', 'مخزن رئيسي');
                    });
          })->where('variant_id', $variantId)
            ->with('warehouseProduct.warehouse')
            ->get()
            ->sum('stock');

          return $totalStock <= 0;
      }
  }


if (!function_exists('recalculateOrderTotal')) {
    function recalculateOrderTotal($orderReference)
    {
        $order = order::where('reference', $orderReference)->firstOrFail();

        // السعر القديم
        $oldPrice = $order->price;

        // جلب تفاصيل الأوردر
        $details = orderDatail::where('order_id', $order->id)->get();

        $newPrice = 0;

        foreach ($details as $detail) {
            $price = $detail->sell_price;

            // إذا مفيش سعر، نحاول نجيبه من المنتج
            // if (!$price || $price == 0) {
                $product = product::find($detail->product_id);

                if ($product && $product->sell_price > 0) {
                    $price = $product->sell_price;

                    // نحدّث السعر في order_details
                    $detail->sell_price = $price;
                    $detail->save();
                }
            // }

            $newPrice += $price * $detail->qnt;
        }

        // تحديث حالة الطلب لو السعر زاد
        if ($newPrice > $oldPrice) {
            $order->status = 'pending';
        }

        $order->price = $newPrice;
        $order->save();

        return [
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'status'    => $order->status,
        ];
    }
}
