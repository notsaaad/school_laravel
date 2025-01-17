<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\warehouse;
use App\Models\invoice;
use App\Models\product;
use Illuminate\Support\Facades\DB;
use App\Models\invoiceItem;
use App\Models\variant;
use App\Models\WarehouseProduct;
use App\Models\WarehouseProductVariant;
use Carbon\Carbon;

class invoicesController extends Controller
{

    function index()
    {
        $warehouses = warehouse::get();
        $invoices = invoice::with("warehouse")->orderBy("id", "desc")->get();


        return view('admin.invoices.index', get_defined_vars());
    }

    function search(Request $request)
    {

        $invoices = invoice::with("warehouse")->orderBy("id", "desc");

        !empty($request->InvoiceName) ?  $invoices = $invoices->where("InvoiceName", "like", "%{$request->InvoiceName}%") : "";
        !empty($request->warehouse_Id) ? $invoices =  $invoices->where("warehouseId",  "{$request->warehouse_Id}") : "";
        !empty($request->invoice_type) ? $invoices =  $invoices->where("type",  "{$request->invoice_type}") : "";

        $date = '';

        if (!empty($request->searchDate)) {

            $date = $request->searchDate;
            $dates = explode("to",   $date);
            $startDate = $dates[0];
            $endDate = $dates[1] ?? Carbon::now();
        }

        $date != ""  ? $invoices =  $invoices->whereBetween("date", [$startDate, $endDate]) : "";


        $invoices =   $invoices->get();

        $warehouses = warehouse::get();

        return view('admin.invoices.index', get_defined_vars());
    }


    function store(Request $request)
    {
        $data = $request->validate([
            "InvoiceName" => "nullable|string",
            'warehouseId' => 'nullable|integer',
            'date' => 'required|date',
            "type" => 'required|in:مشتريات,مرتجعات|string'
        ], [
            "type.in" => "يجب ان تكون نوع الفاتورة مشتريات او مرتجعات"
        ]);


        invoice::create($data);

        return Redirect::to("admin/invoices")->with("success", "تم الاضافة بنجاح");
    }

    function edit(invoice $invoice)
    {

        if ($invoice->warehouseId == null) {
            $products = product::get();
        } else {
            $products = WarehouseProduct::with(['product', 'variants.variant'])
                ->where('warehouse_id', $invoice->warehouse->id)
                ->get();
        }





        return view("admin.invoices.edit", get_defined_vars());
    }

    function storeItem(invoice $invoice, Request $request)
    {

        $data = $request->validate([
            "product_id" => "required|integer",
            "price" => "required|integer|min:0",
            "stock" => "required|integer|min:1",
            "vairants" => "nullable"
        ], [
            "price.min" => "لا يمكن ان يقل السعر عن 0 ",
            "stock.min" => "لا يمكن ان يقل الكمية عن 1 ",
        ]);


        $product = product::findOrFail($data["product_id"]);

        if (!isset($data["vairants"])) {
            return redirect()->back()->with("error", "يرحي اختيار خصائص المنتج")->withInput();
        }

        DB::beginTransaction();


        foreach ($data["vairants"] as $vairant) {

            $variant = variant::find($vairant);


            if ($invoice->warehouseId == null) {

                if ($invoice->type == "مشتريات") {

                    $variant->update(["stock" => $variant->stock + $data["stock"]]);
                    $product->update([
                        "stock" => $product->stock + $data["stock"]
                    ]);
                } else {
                    $variant->update(["stock" => $variant->stock - $data["stock"]]);
                    $product->update([
                        "stock" => $product->stock - $data["stock"]
                    ]);
                }
            } else {
                $all = WarehouseProductVariant::where("variant_id", $variant->id)->get();
                foreach ($all as  $item) {

                    if ($invoice->type == "مشتريات") {

                        $item->update(["stock" => $item->stock + $data["stock"]]);
                    } else {
                        $item->update(["stock" => $item->stock - $data["stock"]]);
                    }
                }
            }


            invoiceItem::create([
                "invoice_id" => $invoice->id,
                "product_id" => $data["product_id"],
                'variant_id' => $variant->id,
                "price" => $data["price"],
                "qnt" => $data["stock"],
            ]);
        }


        DB::commit();

        return redirect()->back()->with("success", "تم الاضافة بنجاح");
    }

    function destroyItem(Request $request)
    {

        $item = invoiceItem::findOrFail($request->delete_id);
        $invoice = invoice::findOrFail($item->invoice_id);


        DB::beginTransaction();


        if ($request->refreshStock == "yes") {


            if ($invoice->warehouseId == null) {
                if ($invoice->type == "مشتريات") {

                    if (!is_null($item->variant_id)) {
                        $variant = variant::findOrFail($item->variant_id);
                        $variant->update(["stock" => $variant->stock - $item->qnt]);
                    }
                    $product = product::findOrFail($item->product_id);
                    $product->update([
                        "stock" => $product->stock - $item->qnt
                    ]);
                } else {
                    if (!is_null($item->variant_id)) {
                        $variant = variant::findOrFail($item->variant_id);
                        $variant->update(["stock" => $variant->stock + $item->qnt]);
                    }
                    $product = product::findOrFail($item->product_id);
                    $product->update([
                        "stock" => $product->stock + $item->qnt
                    ]);
                }
            } else {

                $all = WarehouseProductVariant::where("variant_id", $item->variant_id)->get();

                if ($invoice->type == "مشتريات") {

                    foreach ($all as  $item2) {
                        $item2->update(["stock" => $item2->stock -  $item->qnt]);
                    }
                } else {
                    foreach ($all as  $item2) {
                        $item2->update(["stock" => $item2->stock +  $item->qnt]);
                    }
                }
            }
        }

        $item->delete();
        DB::commit();
        return redirect()->back()->with("success", "تم الازالة بنجاح");
    }

    function destroy(Request $request)
    {


        $invoice = invoice::find($request->delete_id);

        $items = invoiceItem::where("invoice_id", $invoice->id)->get();


        DB::beginTransaction();

        if ($request->refreshStock == "yes") {
            foreach ($items as $item) {

                if ($invoice->warehouseId == null) {


                    if ($invoice->type == "مشتريات") {

                        if (!is_null($item->variant_id)) {
                            $variant = variant::findOrFail($item->variant_id);
                            $variant->update(["stock" => $variant->stock - $item->qnt]);
                        }
                        $product = product::findOrFail($item->product_id);
                        $product->update([
                            "stock" => $product->stock - $item->qnt
                        ]);
                    } else {
                        if (!is_null($item->variant_id)) {
                            $variant = variant::findOrFail($item->variant_id);
                            $variant->update(["stock" => $variant->stock + $item->qnt]);
                        }
                        $product = product::findOrFail($item->product_id);
                        $product->update([
                            "stock" => $product->stock + $item->qnt
                        ]);
                    }
                } else {
                    $all = WarehouseProductVariant::where("variant_id", $item->variant_id)->get();

                    if ($invoice->type == "مشتريات") {

                        foreach ($all as  $item2) {
                            $item2->update(["stock" => $item2->stock -  $item->qnt]);
                        }
                    } else {
                        foreach ($all as  $item2) {
                            $item2->update(["stock" => $item2->stock +  $item->qnt]);
                        }
                    }
                }
            }
        }

        $invoice->delete();


        DB::commit();
        return redirect()->back()->with("success", "تم الازالة بنجاح");
    }
}
