@extends('admin.layout')


@section('title')
    <title> المصاريف</title>

@endsection


@section('content')


    <div class="container">
        <form action="{{route('admin.Expenses.store.yearcost_stage')}}" method="POST">
            @csrf
            @method('POST')

            <table>
                <thead>
                    <tr>
                        <th>المرحلة الدارسية</th>
                        <th>مصاريف السنة</th>
                        <th>مصاريف الكتب</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i <count($yearcost_stages); $i++)
                        <tr>
                            <td>
                                <input type="hidden" name="row[{{$i}}][id]" value="{{$yearcost_stages[$i]->id}}">
                                {{$yearcost_stages[$i]->stage_name}}
                            </td>
                            <td><input type="number" name="row[{{$i}}][cash]" value="{{$yearcost_stages[$i]->cash}}"></td>
                            <td><input type="number" name="row[{{$i}}][book]" value="{{$yearcost_stages[$i]->book}}"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>

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
