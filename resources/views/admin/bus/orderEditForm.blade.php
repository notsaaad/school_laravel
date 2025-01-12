@extends('admin.layout')





@section('title')
    <title> تعديل طلب اتوبيس  </title>
@endsection


@section('content')
    <x-admin.layout.back back="admin/buses/orders" title="الطالب {{$order[0]->user->name ??  ' '}}"></x-admin.layout.back>
    <h2</h2>
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

    @section('js')
        <script>

        $('.modelSelect').select2();


        function show_new_value_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_stock = element.getAttribute('data-stock')
            let data_id = element.getAttribute('data-id')

            $("#new_value_input").val(data_name)
            $("#new_stock_input").val(data_stock)
            $("input[name='value_id']").val(data_id)
        }
    </script>
    @endsection

@endsection
