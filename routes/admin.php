<?php

use App\Models\User;
use App\Models\year;
use App\Models\order;
use App\Models\stage;
use App\Models\product;
use App\Models\application;
use Illuminate\Http\Request;
use App\Models\applicationFee;
use App\Models\packageProduct;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\YearController;
use App\Http\Controllers\settingsController;
use App\Http\Controllers\admin\busController;
use App\Http\Controllers\admin\feesController;
use App\Http\Controllers\admin\userController;
use App\Http\Controllers\admin\rolesController;
use App\Http\Controllers\admin\stageController;
use App\Http\Controllers\admin\testsController;
use App\Http\Controllers\definitionsController;
use App\Http\Controllers\admin\packageController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\regionsController;
use App\Http\Controllers\admin\invoicesController;
use App\Http\Controllers\admin\productsController;
use App\Http\Controllers\admin\studentsController;
use App\Http\Controllers\admin\transfersController;
use App\Http\Controllers\admin\warehouseController;
use App\Http\Controllers\admin\applicationsController;
use App\Http\Controllers\admin\customStatusController;
use App\Http\Controllers\admin\dynamicFieldsController;
use App\Http\Controllers\admin\orders\orderViewController;
use App\Http\Controllers\admin\orders\orderLogicController;
use App\Http\Controllers\admin\statistics\ApplicationSatiController;
use App\Http\Controllers\users\userController as UsersUserController;

Route::get('home', function () {
    return view("admin/home");
});


Route::post('upload', function (Request $request) {
    $file = Storage::put("public/other", $request->file('file'));
    return response()->json([
        'location' => path($file)
    ]);
})->name('admin.upload');


Route::prefix("users")->group(function () {

    Route::middleware('checkRole:users_show')->group(function () {
        Route::get('/', [userController::class, 'index']);
        Route::get('search', [userController::class, 'search']);
    });

    Route::middleware('checkRole:users_action')->group(function () {
        Route::post('/', [userController::class, 'store']);

        Route::get('{user}/edit', [userController::class, 'edit']);

        Route::middleware('checkRole:user_login')->get('{user}/login', [userController::class, 'login']);




        Route::put('{user}', [userController::class, 'update']);
        Route::DELETE('destroy', [userController::class, 'destroy']);
        Route::get('restore/{id}', [userController::class, 'restore']);
    });
});

Route::prefix("roles")->middleware("checkRole:roles")->group(function () {
    Route::get('/', [rolesController::class, "index"]);
    Route::get('create', [rolesController::class, "create"]);
    Route::post('/', [rolesController::class, "store"]);
    Route::delete('destroy', [rolesController::class, "destroy"]);
    Route::get('{role}/edit', [rolesController::class, "edit"]);
    Route::put('{role}', [rolesController::class, "update"]);
});

Route::prefix("students")->group(function () {

    Route::middleware('checkRole:students_show')->group(function () {
        Route::get('/', [studentsController::class, 'index']);
        Route::get('search', [studentsController::class, 'search']);
    });

    Route::middleware('checkRole:students_action')->group(function () {
        Route::post('/', [studentsController::class, 'store']);
        Route::post('import', [studentsController::class, 'import']);

        Route::get('{user}/edit', [studentsController::class, 'edit']);

        Route::middleware('checkRole:students_login')->get('{user}/login', [userController::class, 'login']);

        Route::put('{user}', [studentsController::class, 'update']);
        Route::put('{user}/updateOther', [studentsController::class, 'updateOther']);

        Route::DELETE('destroy', [studentsController::class, 'destroy']);
        Route::get('restore/{id}', [studentsController::class, 'restore']);
    });

    Route::middleware('checkRole:fees_show')->group(function () {

        Route::get('{code}/fees', [studentsController::class, 'fees']);
        Route::get('{studentCode}/fees/{fee}', [studentsController::class, 'show_student_fee']);
    });

    Route::middleware('checkRole:fees_paid')->post('{user}/fees/{fee}/paid', [PaymentController::class, 'paid']);
});



Route::prefix("stages")->group(function () {

    Route::get('/', [stageController::class, 'index']);
    Route::get('create', [stageController::class, 'create']);
    Route::post('/', [stageController::class, 'store']);
    Route::put('/', [stageController::class, 'update']);
    Route::DELETE('destroy', [stageController::class, 'destroy']);
    Route::get('changeOrder', [stageController::class, 'changeOrder']);
});


Route::prefix("products")->group(function () {

    Route::middleware('checkRole:products_show')->group(function () {
        Route::get('/', [productsController::class, 'index']);
        Route::get('search', [productsController::class, 'search']);
    });

    Route::middleware('checkRole:products_action')->group(function () {
        Route::post('/', [productsController::class, 'store']);
        Route::post('import', [productsController::class, 'import']);

        Route::put('variants/edit', [productsController::class, 'update_vairant']);



        Route::post('/{product}/variants', [productsController::class, 'variants']);
        Route::DELETE('variants/destroy', [productsController::class, 'variants_destroy']);
        Route::get('/variants/changeOrder', [productsController::class, 'changeOrder']);

        Route::get('{product}/edit', [productsController::class, 'edit']);
        Route::put('{product}', [productsController::class, 'update']);
        Route::DELETE('destroy', [productsController::class, 'destroy']);
        Route::get('restore/{id}', [productsController::class, 'restore']);
        Route::post('showHideProduct', [productsController::class, 'showHideProduct']);
    });


    Route::get('{product}/qr', [productsController::class, 'qr']);
    Route::get('{id}/qrVairant', [productsController::class, 'qrVairant']);
});


Route::prefix("packages")->group(function () {

    Route::middleware('checkRole:packages_show')->group(function () {
        Route::get('/', [packageController::class, 'index']);
        Route::get('search', [packageController::class, 'search']);
    });

    Route::middleware('checkRole:packages_action')->group(function () {
        Route::post('/', [packageController::class, 'store']);
        Route::post('products', [packageController::class, 'products']);
        Route::get('{package}/edit', [packageController::class, 'edit']);
        Route::put('{package}', [packageController::class, 'update']);
        Route::DELETE('destroy', [packageController::class, 'destroy']);
        Route::DELETE('{package}/products/destroy', [packageController::class, 'products_destroy']);

        Route::get('restore/{id}', [packageController::class, 'restore']);
        Route::post('showHidepackage', [packageController::class, 'showHidepackage']);
    });
});




Route::prefix("orders")->group(function () {

    Route::middleware("checkRole:show_orders")->get('/', [orderViewController::class, 'index']);
    Route::middleware("checkRole:show_orders")->get('search', [orderViewController::class, 'search']);

    Route::get('{reference}',  [orderViewController::class, 'show']);



    Route::middleware("checkRole:return_requested")->post('changeOrderStatus',  [orderLogicController::class, 'changeOrderStatus']);


    Route::middleware("checkRole:return_requested")->post('{order}/return_requested',  [orderLogicController::class, 'return_requested']);





    Route::middleware("checkRole:picking_order")->post('{order}/picked',  [orderLogicController::class, 'picked']);

    Route::middleware("checkRole:return_product")->post('{order}/return',  [orderLogicController::class, 'return']);

    Route::get('GetOrderDetailsAjax/{id}', [orderViewController::class, "GetOrderDetailsAjax"]);

    Route::middleware("checkRole:cancel_order")->delete('{order}', action: [orderLogicController::class, 'cancel_order']);
    Route::middleware("checkRole:order_payment")->post('{order}/payment', action: [orderLogicController::class, 'payment']);
    Route::middleware("checkRole:update_size")->put('{reference}', action: [orderLogicController::class, 'update_order']);
});



Route::prefix("settings")->group(function () {

    Route::middleware('checkRole:year')->get('/years', action: [YearController::class, 'index']);
    Route::middleware('checkRole:regions')->get('/regions', action: [regionsController::class, 'index']);
    Route::middleware('checkRole:custom_status')->get('/custom_status', action: [customStatusController::class, 'index']);
    Route::middleware('checkRole:dynamic_fields')->get('/dynamic_fields', action: [dynamicFieldsController::class, 'index']);



    Route::get('/', [settingsController::class, 'index']);
    Route::middleware('checkRole:branding')->get('branding', function () {
        return view("admin.settings.branding");
    });
    Route::put('/', [settingsController::class, 'update']);

    Route::middleware('checkRole:other')->get('financial', function () {
        return view("admin.settings.financial");
    });

    Route::middleware('checkRole:other')->put('financial', [settingsController::class, 'update_financial']);
});



Route::prefix("warehouses")->middleware('checkRole:warehouses')->group(function () {

    Route::get('/', [warehouseController::class, 'index']);
    Route::get('create', [warehouseController::class, 'create']);
    Route::post('/', [warehouseController::class, 'store']);
    Route::post('products', [warehouseController::class, 'products']);
    Route::DELETE('{warehouse}/products/destroy', [warehouseController::class, 'products_destroy']);

    Route::get('{warehouse}/edit', [warehouseController::class, 'edit']);
    Route::put('{warehouse}', [warehouseController::class, 'update']);
    Route::put('/', [warehouseController::class, 'update']);
    Route::delete('destroy', [warehouseController::class, 'destroy']);
    Route::get('changeOrder', [warehouseController::class, 'changeOrder']);
});



Route::get('products/ajaxVariant', [productsController::class, 'ajaxVariant']);




Route::prefix("invoices")->middleware('checkRole:invoices')->group(function () {
    Route::get('/', [invoicesController::class, 'index']);
    Route::get('search', [invoicesController::class, 'search']);
    Route::post('/', [invoicesController::class, 'store']);
    Route::get('{invoice}/edit', [invoicesController::class, 'edit']);
    Route::post('{invoice}/storeItem', [invoicesController::class, 'storeItem']);
    Route::delete('items/destroy', [invoicesController::class, 'destroyItem']);
    Route::delete('destroy', [invoicesController::class, 'destroy']);
});


Route::prefix("transfers")->group(function () {


    Route::get('checkAmount', [transfersController::class, 'checkAmount']);



    Route::middleware('checkRole:show_transfers')->get('/', [transfersController::class, 'index']);
    Route::middleware('checkRole:show_transfers')->get('{code}', [transfersController::class, 'show']);



    Route::middleware('checkRole:actions_transfers')->get('create', [transfersController::class, 'create']);
    Route::middleware('checkRole:actions_transfers')->post('/', action: [transfersController::class, 'store']);
    Route::middleware('checkRole:actions_transfers')->post('{transfer}/orders', [transfersController::class, 'orders']);


    Route::middleware('checkRole:paid_transfers')->post('{transfer}/paid', [transfersController::class, 'paid']);
    Route::middleware('checkRole:confirm_transfers')->post('{transfer}/confirm', [transfersController::class, 'confirm']);

    Route::middleware('checkRole:actions_transfers')->put('/', [transfersController::class, 'update']);
    Route::middleware('checkRole:actions_transfers')->delete('destroy', [transfersController::class, 'destroy']);
});




Route::prefix("fees")->middleware('checkRole:fees_show')->group(function () {

    Route::get('{user}/{fee}/calculate-payment', [PaymentController::class, 'calculatePayment']);

    Route::get('/', [feesController::class, 'index']);
    Route::get('search', [feesController::class, 'search']);
    Route::post('/', [feesController::class, 'store']);
    Route::get('changeOrder', [feesController::class, 'changeOrder']);
    Route::delete('destroy', [feesController::class, 'destroy']);
    Route::post('showHide', [feesController::class, 'showHide']);
    Route::get('{fee}/edit', [feesController::class, 'edit']);
    Route::put('{fee}', [feesController::class, 'update']);
    Route::delete('items/destroy', [feesController::class, 'destroy_item']);
    Route::get('items/changeOrder', [feesController::class, 'changeOrder_items']);
    Route::put('items/update', [feesController::class, 'update_item']);
    Route::post('{fee}/items', [feesController::class, 'store_items']);
});


Route::get('items/{user}', function (User $user) {


    $packages_ids = order::where("status", "picked")->where("user_id", $user->id)->distinct()->pluck("package_id")->unique("package_id")->toArray();


    $products_ids = packageProduct::whereIn("package_id", $packages_ids)->distinct()->pluck("product_id")->toArray();


    $products = product::where(function ($query) use ($user) {
        $query->where('gender', $user->gender)
            ->orWhere('gender', 'both');
    })->where("stage_id", $user->stage_id)->where("show", "1")->whereIn("id", $products_ids)->get();

    return view("users.items", compact("products"));
});

Route::post('items/makeOrder', [UsersUserController::class, 'items_makeOrder']);





Route::middleware('checkRole:years')->prefix("years")->group(function () {

    Route::post('/', [YearController::class, 'store']);
    Route::put('/', [YearController::class, 'update']);
    Route::DELETE('destroy', [YearController::class, 'destroy']);
    Route::get('changeOrder', [YearController::class, 'changeOrder']);
});


Route::middleware('checkRole:regions')->prefix("regions")->group(function () {
    Route::post('/', [regionsController::class, 'store']);
    Route::put('/', [regionsController::class, 'update']);
    Route::DELETE('destroy', [regionsController::class, 'destroy']);
    Route::get('changeOrder', [regionsController::class, 'changeOrder']);


    Route::get('{region}/places', [regionsController::class, 'places_index']);
    Route::post('places', [regionsController::class, 'places']);
    Route::put('places', [regionsController::class, 'places_update']);
    Route::delete('places/destroy', [regionsController::class, 'places_destroy']);
});


Route::prefix("buses")->group(function () {
    Route::get('/', [busController::class, 'index']);
    Route::post('/', [busController::class, 'store']);
    Route::put('/', [busController::class, 'update']);
    Route::DELETE('destroy', [busController::class, 'destroy']);
    Route::get('changeOrder', [busController::class, 'changeOrder']);
    Route::get('{bus}/settings', [busController::class, 'settings']);



    Route::post('{bus}/settings', [regionsController::class, 'settings_store']);
    Route::put('settings', [regionsController::class, 'settings_update']);
    Route::delete('settings/destroy', [regionsController::class, 'settings_destroy']);

    Route::get('orders', [busController::class, 'orders']);
});


Route::middleware('checkRole:definitions')->prefix("definitions")->group(function () {
    Route::post('/', [definitionsController::class, 'store']);
    Route::put('/', [definitionsController::class, 'update']);
    Route::DELETE('destroy', [definitionsController::class, 'destroy']);
    Route::get('changeOrder', [definitionsController::class, 'changeOrder']);
    Route::get('{type}', [definitionsController::class, 'index']);
});


Route::prefix("applications")->group(function () {

    Route::prefix("fees")->group(function () {

        Route::middleware('checkRole:fees_index')->get('/', function () {

            $stages = stage::get();
            $years = year::get();
            $fees = applicationFee::get();
            return view('admin.applications.fees', compact('stages', 'years', "fees"));
        });
        Route::middleware('checkRole:fees_actions')->group(function () {

            Route::post('/', [applicationsController::class, 'fees']);
            Route::put('/', [applicationsController::class, 'fees_update']);
            Route::DELETE('destroy', [applicationsController::class, 'destroy_fees']);
        });
    });


    Route::middleware('checkRole:tests')->prefix("tests")->group(function () {
        Route::get('/', [testsController::class, 'index']);
        Route::post('/', [testsController::class, 'store']);
        Route::put('/', [testsController::class, 'update']);
        Route::DELETE('destroy', [testsController::class, 'destroy']);

        Route::get('{test}/subjects', [testsController::class, 'subject_index']);
        Route::post('subject', [testsController::class, 'subject']);
        Route::put('subject', [testsController::class, 'subject_update']);
        Route::delete('subject/destroy', [testsController::class, 'subject_destroy']);
    });

    Route::middleware('checkRole:applications_show')->get('/', [applicationsController::class, 'index']);


    Route::post('enableToggle', [applicationsController::class, 'enableToggle']);
    Route::get('{code}', [applicationsController::class, 'show']);





    Route::post('{application}/updateFields', [applicationsController::class, 'updateFields']);
    Route::post('/', [applicationsController::class, 'store']);
    Route::post('update_subject', [applicationsController::class, 'update_subject']);
    Route::put('/', [applicationsController::class, 'update']);
    Route::DELETE('destroy', [applicationsController::class, 'destroy']);
    Route::post('changeStatus', [applicationsController::class, 'changeStatus']);
    Route::post('changeCustomStatus', [applicationsController::class, 'changeCustomStatus']);

    Route::post('complate', [applicationsController::class, 'complate']);
});




Route::middleware('checkRole:custom_status')->prefix("custom_status")->group(function () {

    Route::post('/', [customStatusController::class, 'store']);
    Route::put('/', [customStatusController::class, 'update']);
    Route::DELETE('destroy', [customStatusController::class, 'destroy']);
    Route::get('changeOrder', [customStatusController::class, 'changeOrder']);
});


Route::middleware('checkRole:dynamic_fields')->prefix("dynamic_fields")->group(function () {

    Route::post('/', [dynamicFieldsController::class, 'store']);
    Route::put('/', [dynamicFieldsController::class, 'update']);
    Route::DELETE('destroy', [dynamicFieldsController::class, 'destroy']);
    Route::get('changeOrder', [dynamicFieldsController::class, 'changeOrder']);

});



Route::prefix("statistics")->group(function(){
    Route::controller(ApplicationSatiController::class)->group(function(){
        Route::get('/', 'index')->name('indexStatic');
        Route::get('/Application-Char', 'applicationChar');
    });
});
