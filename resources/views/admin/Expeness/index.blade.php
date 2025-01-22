@extends('admin.layout')


@section('title')
    <title> المصاريف</title>

@endsection


@section('content')


    <div class="container">
        <form action="" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="busOrder_id" value="">
            <x-form.select col="col-lg-3 col-6"  name="year" label="اختر السنة">

                @foreach ($years as $year)
                    <option value="{{$year}}">{{$year}}</option>
                @endforeach


            </x-form.select>

            <button class="es-btn-primary" id="submitBtn"> اضافة مصاريف دراسية</button>
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
