@extends('admin.layout')


@section('title')
    <title> المصاريف</title>

@endsection


@section('content')


    <div class="container">
        <form action="{{route('admin.Expenses.storeintallment')}}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="year_id" value={{$year_id}}>
            @for ($i = 0;$i< $installment_count; $i++ )

            <x-form.input required col="col-4" label="القسط {{$i + 1}}" @old name="per[{{$i}}]"></x-form.input>

            @endfor
            <input type="hidden" name="yearcost_id" value="{{$yearcost_id}}">
            <button class="es-btn-primary" id="submitBtn"> اضافة نسب الاقساط</button>
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
