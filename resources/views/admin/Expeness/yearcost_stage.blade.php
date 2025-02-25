@extends('admin.layout')


@section('title')
    <title> المصاريف</title>

@endsection


@section('content')
    <style>
        .table-installment-container{
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
            padding:20px;
            margin: 20px 0;
        }
        .table-installment-container h4{
            text-align: center;
        }
    </style>

    <div class="container">
        <form action="{{route('admin.Expenses.store.yearcost_stage')}}" method="POST">
            @csrf
            @method('POST')
            @foreach ($data as $row )
            <div class="table-installment-container">
                <h4>اضافة مصاريف لنظام {{$row['system_name']}}</h4>
                <table>
                    @php
                        $i = 0;
                    @endphp
                    <thead>
                        <tr>
                            <th>المرحلة الدارسية</th>
                            <th>مصاريف السنة</th>
                            <th>الاقساط</th>
                            <th>مصاريف الكتب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($row['stages'] as  $stage)
                        <tr>
                            <td>
                                <input type="hidden" name="row[{{$i}}][id]" value="{{$stage->id}}">
                                {{$stage->stage_name}}
                            </td>
                            <td><input type="number" name="row[{{$i}}][cash]" value="{{$stage->cash}}"></td>
                            <td><input type="number" name="row[{{$i}}][installment]" value="{{$stage->installment}}"></td>
                            <td><input type="number" name="row[{{$i}}][book]" value="{{$stage->book}}"></td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                        @endforeach

                    </tbody>
                </table>
            </div>
            @endforeach



            <button class="es-btn-primary" id="submitBtn">حفظ</button>
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
