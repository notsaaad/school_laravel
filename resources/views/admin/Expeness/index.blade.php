@extends('admin.layout')


@section('title')
    <title> المصاريف</title>

@endsection


@section('content')


    <div class="container">
        <form action="{{route('admin.Expenses.store.yearcost')}}" method="POST">
            @csrf
            @method('POST')
            <x-form.select col="col-lg-3 col-6"  name="year" label="اختر السنة">
                @foreach ($years as $year)
                    <option value="{{$year->id}}">{{$year->name}}</option>
                @endforeach


            </x-form.select>
            <div class="row g-2">
                <div class="col-4 mb-2">
                    <input type="number" required  name="installment_count" placeholder="عدد الاقساط" >
                </div>
            </div>

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
