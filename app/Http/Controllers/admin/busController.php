<?php

namespace App\Http\Controllers\admin;

use App\Models\bus;
use App\Models\User;
use App\Models\region;
use App\Models\busOrder;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\BusOrders\Canceld;
use App\Mail\Admin\BusOrders\Confrim;
use Illuminate\Support\Facades\Redirect;

class busController extends Controller
{

public function index(){
    $status_order = 'pending';
    $AllData = array();
    $all = bus::get();
    $regions = region::get();
    $bus_count = count($all);




    for($i = 0; $i<$bus_count; $i++){
        $bus_id =$all[$i]['id'];

        array_push($AllData , $all[$i]);

        $orders =  DB::table('bus_orders')
        ->where('bus_id' , $bus_id )
        ->where('status', $status_order)
        ->get();

        $userOrders = DB::table('bus_orders')
        ->where('bus_id' , $bus_id )
        ->where('status', $status_order)
        ->pluck('user_id');

        $user_count = count($userOrders);



        $going_char = DB::table('bus_orders')
        ->where('bus_id' , $bus_id )
        ->where('status', $status_order)
        ->sum('go_chairs_count');

        $return_charis = DB::table('bus_orders')
        ->where('bus_id' , $bus_id )
        ->where('status', $status_order)
        ->sum('return_chairs_count');



        $bus_going_charis_counter = $all[$i]['go_chairs_count'];
        $bus_return_charis_counter = $all[$i]['return_chairs_count'];

        $emptyGoingChairs = (int)$bus_going_charis_counter  - (int)$going_char;
        $emptyreturnChairs = (int)$bus_return_charis_counter  - (int)$return_charis;


        $AllData[$i]['order_meta'] = array(
            'bus_users_count' => $user_count,
            'empty_going_chairs' => $emptyGoingChairs,
            'empty_return_chairs' => $emptyreturnChairs,
            'going_chair_counter' => $bus_going_charis_counter,
            'return_chair_counter' => $bus_return_charis_counter
        );

    }

    // return $AllData;

    return view('admin/bus/index', compact('all', "regions", 'AllData'));
}

public function destroy(Request $request){


    $bus = bus::findOrFail($request->delete_id);
    $bus->delete();
    return Redirect::back()->with("success", "تم الازالة بنجاح");
}


public function store(Request $request){

    $data = $request->validate([
        'name' => 'required|string',
        'go_chairs_count' => 'required|integer|min:1',
        'return_chairs_count' => 'required|integer|min:1',
        'area_ids' => 'nullable|array',
        'area_ids.*' => 'exists:places,id',
    ]);


    DB::beginTransaction();

    $busData = Arr::except($data, ['area_ids']);

    $bus = bus::create($busData);

    if (isset($data['area_ids'])) {
        $bus->places()->sync($data['area_ids']);
    }


    DB::commit();
    return Redirect::back()->with("success", "تم الاضافة بنجاح");
}

public function update(Request $request)
{

    $data = $request->validate([
        "idInput" => "required|string",
        'nameInput' => 'required|string',
        'go_chairs_countInput' => 'required|integer|min:1',
        'return_chairs_countInput' => 'required|integer|min:1',
        'area_ids' => 'nullable|array',
        'area_ids.*' => 'exists:places,id',
    ]);

    $data = collect($data)->mapWithKeys(function ($value, $key) {
        return [str_replace('Input', '', $key) => $value];
    })->toArray();


    $bus = bus::findOrFail($data["id"]);


    DB::beginTransaction();

    $busData = Arr::except($data, ['area_ids']);

    $bus->update($busData);

    if (isset($data['area_ids'])) {
        $bus->places()->sync($data['area_ids']);
    }


    DB::commit();

    return Redirect::back()->with("success", "تم التعديل بنجاح");
}


public function changeOrder(Request $request)
{


    bus::where('id', $request->id)->update([
        'order' => $request->order + 1
    ]);

    return true;
}

function settings(bus $bus)
{
    return view("admin.bus.settings", get_defined_vars());
}


function orders()
{
    $orders = busOrder::with(['user', 'bus'])->simplePaginate(50);

    return view("admin.bus.orders", get_defined_vars());
}


    function OrderEdit(busOrder $order){

        $order = busOrder::with(['user', 'bus'])->where('id', $order->id)->get();
        $status = ['pending','not answer','cancelled','confirmed','paid'];
        // return $order;
        return view('admin.bus.orderEditForm', compact('order', 'status'));

    }

    function ChangeStatue(Request $request){

        DB::table('bus_orders')
        ->where('id',$request->busOrder_id)
        ->update(['status'=>$request->status]);
        $Order_id = $request->busOrder_id;
        $user_id = DB::table('bus_orders')
        ->select('user_id')
        ->where('id', $Order_id)
        ->get();
        $user_id =  $user_id[0]->user_id;
        $user = User::where('id', $user_id)->first();
        $user_email =  $user->email;


        if($request->status == "confirmed"){
            Mail::to($user_email)->send(new Confrim);
        }elseif($request->status == "cancelled"){
            Mail::to($user_email)->send(new Canceld);
        }

        return redirect()->route('bus.orders')->with(['success'=>'تم تغير حالة الطلب بنجاح']);
    }

    function DeleteBusOrder(busOrder $order){
        DB::table('bus_orders')
        ->where('id', $order->id)
        ->delete();

        return redirect()->route('bus.orders')->with(['success'=> 'تم مسح الطلب بنجاح']);
    }
}
