@extends('admin.layout')

@section('title')
    <title>تسوية التقديمات </title>
@endsection


@section('content')
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
@endsection
