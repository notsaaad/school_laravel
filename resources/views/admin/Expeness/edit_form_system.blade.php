@extends('admin.layout')


@section('title')
    <title>تغديل نظام</title>

@endsection


@section('content')


    <div class="container">
        <form action="{{route('admin.Expenses.edit.system')}}" method="POST">
            @csrf
            @method("POST")
            <input type="hidden" name="id" value="{{$systems[0]->id}}">
            <x-form.input required col="col-8" value="" label="اضف سنة دراسية" value="{{$systems[0]->name}}" name="name">
            </x-form.input>

            <button class="es-btn-primary" id="submitBtn"> تعديل النظام دراسي</button>
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
