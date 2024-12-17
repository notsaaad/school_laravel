@extends('admin.layout')



@section('title')
    <title>{{ trans('words.settings') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="admin/settings" :title="trans('words.settings')"></x-admin.layout.back>
    </div>

    <form class="row form_style" action="/admin/settings/financial" method="post">

        @csrf
        @method('put')


        <x-form.input col="col-lg-3 col-12" min="0" type="number" name="service_expenses"
            label="{{ trans('words.مصاريف الخدمة') }}" :value="settings('service_expenses')"></x-form.input>



        <div class="mt-2">
            <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>
        </div>


    </form>
@endsection


@section('js')
    <script>
        $('aside .settings').addClass('active');
    </script>
@endsection
