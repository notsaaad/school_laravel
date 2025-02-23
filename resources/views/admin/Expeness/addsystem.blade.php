@extends('admin.layout')


@section('title')
    <title>اضافة نظام</title>

@endsection


@section('content')


    <div class="container">
        <form action="{{route('admin.Expenses.store.system')}}" method="POST">
            @csrf
            @method("POST")
            <x-form.input required col="col-8" label="اضف سنة دراسية" name="name">
            </x-form.input>

            <button class="es-btn-primary" id="submitBtn"> اضف نظام دراسي</button>
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
