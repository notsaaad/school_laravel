@extends('admin.layout')

@section('title')
    <title>تسوية التقديمات </title>
@endsection


@section('content')
    <x-admin.layout.back back="admin/applications/{{$application->code}}"  title="{{$application->name}}"></x-admin.layout.back>

    <div class="container">
        <form action="{{route('application.ChangeStatue')}}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="application_id" value="{{$application->id}}">
            <input type="hidden" name="amout" value="{{$application->fees->amount}}">
            <input type="hidden" name="code" value="{{$application->code}}">
            <x-form.select col="col-lg-3 col-6"  name="method" label="طريقة الدفع">
                @foreach ( $methods as $method )
                    <option value="{{$method}}">{{$method}}</option>
                @endforeach
            </x-form.select>
            <button class="es-btn-primary default">تسوية المدفوعات</button>
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
