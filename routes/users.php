<?php

use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\users\cartController;
use App\Http\Controllers\users\userController;
use App\Models\package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('home', function () {
    return view("users/home");
});

Route::get('packages', function () {
    $packages = package::where("stage_id", auth()->user()->stage_id)->where("gender", auth()->user()->gender)->get();
    return view("users/packages", get_defined_vars());
});

Route::get('packages/{package}', action: [userController::class, 'show_package']);


Route::get('packages/{package}', action: [userController::class, 'show_package']);

Route::post('storeOrder/{package}', action: [userController::class, 'storeOrder']);


Route::prefix("orders")->group(function () {


    Route::get('/', action: [userController::class, 'orders']);

    Route::get('{reference}', action: [userController::class, 'show_order']);
    Route::put('{reference}', action: [userController::class, 'update_order']);

    Route::delete('{order}', action: [userController::class, 'cancel_order']);

    Route::get('GetOrderDetailsAjax/{id}', [userController::class, "GetOrderDetailsAjax"]);



    Route::delete('details/destroy',  [userController::class, 'destroyDetails']);
});


Route::get('items', [userController::class, 'items']);
Route::post('items/makeOrder', [userController::class, 'items_makeOrder']);




Route::prefix("cart")->group(function () {

    Route::get('/',  [cartController::class, 'index']);
    Route::get('checkOut',  [cartController::class, 'checkOut']);
    Route::delete('destroy',  [cartController::class, 'destroy']);
});





Route::get('pending_payments', [userController::class, 'pending_payments']);


Route::post('/calculate-payment', [PaymentController::class, 'calculatePayment']);
Route::post('/process-payment', [PaymentController::class, 'processPayment']);




Route::get('/Buses',  function () {
    return view('users.buses');
});

Route::post('/subscribe_in_bus', [userController::class, 'subscribe_in_bus']);



Route::get('logout', [userController::class, 'logout']);



