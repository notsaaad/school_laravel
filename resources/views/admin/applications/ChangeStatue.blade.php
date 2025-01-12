@extends('admin.layout')



@section('title')
    <title> تغير حالة {{ $application[0]->name  ?? ''}} </title>
@endsection


@php
    $statues = ['new','paid','complate','returned','canceled'];
@endphp

@section('content')

    <div class="actions border-0">
        <x-admin.layout.back back="admin/applications/{{$application[0]->code}}" title="تغير حالة الطلب ل{{ $application[0]->name  ?? ''}}">
        </x-admin.layout.back></div>

        <div class="container">
            <form action="{{route('application.handelStatue')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="code" value="{{$application[0]->code}}">
                <x-form.select col="col-lg-3 col-6"  name="statue" label="اختر الحالة التي تريدها">
                    @foreach ( $statues as $statue )
                        <option @if($application[0]->status == $statue) selected  @endif value="{{$statue}}">{{$statue}}</option>
                    @endforeach
                </x-form.select>
                <button class="es-btn-primary default">تغير الحالة</button>
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
