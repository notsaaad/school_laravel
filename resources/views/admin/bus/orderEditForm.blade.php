@extends('admin.layout')





@section('title')
    <title> تعديل طلب اتوبيس  </title>
@endsection


@section('content')

    <h2>الطالب {{$order[0]->user->name ?? ""}}</h2>
    <div class="container">
        <form action="{{route('bus.order.ChangeStatue')}}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="busOrder_id" value="{{$order[0]->id}}">
            <x-form.select col="col-lg-3 col-6"  name="status" label="الحالة">

                @foreach ($status as $statu)
                    <option @selected($order[0]->status == $statu) value="{{$statu}}">{{ $statu }}</option>
                @endforeach


            </x-form.select>

            <button class="es-btn-primary" id="submitBtn"> تغير حالة الطلب</button>
        </form>
    </div>
@endsection
