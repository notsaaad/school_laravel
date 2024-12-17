@extends('admin.layout')


@section('title')
    <title>تعديل {{ ModelName->name }}</title>
@endsection

@section('nav_title')
    <x-layout.back back="admin/ModelName_s" title="المنتجات"></x-layout.back>
@endsection



@section('content')
    <div class="content">

        <div class="actions border-0">
            <x-layout.back back="admin/roles" title=" تعديل {{ ModelName->name }}"></x-layout.back>
        </div>

        <form class="row form_style" action="/admin/ModelName_s/{{ ModelName->id }}" method="post">

            @csrf
            @method('put')

            {{--put content Here--}}


        </form>
    </div>
@endsection


@section('js')
    <script>
        $('aside .ModelName_s').addClass('active');
    </script>
@endsection
