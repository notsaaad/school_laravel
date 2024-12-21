<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\bus;
use App\Models\busOrder;
use App\Models\region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Arr;

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
    $orders = busOrder::simplePaginate(50);

    return view("admin.bus.orders", get_defined_vars());
}
}
