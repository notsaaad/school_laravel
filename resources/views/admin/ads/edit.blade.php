@extends('admin.layout')


@section('title')
    <title>  {{ trans('words.btn_update') }}  {{ trans('words.advertisement_banner') }}</title>
@endsection



@section('content')

        <div class="actions">
            <x-admin.layout.back back="admin/ads" title="{{ trans('words.btn_update') }}"></x-admin.layout.back>
        </div>


        <form class="row form_style pt-0 " action="/admin/ads/{{ $ads->id }}" method="post" enctype="multipart/form-data">

            <div><img style="width: 100% ; margin: 10px 0px ; height: 200px ; object-fit: contain"
                    src="{{ path("$ads->img") }}"></div>


            @csrf
            @method('put')


            <x-form.input col="col-lg-4 col-12" label="{{ trans('words.link') }}" name="link" value="{{ $ads->link }}">
            </x-form.input>

            <x-form.select col="col-lg-4 col-12" required name="show" label="{{ trans('words.status') }}">
                <option @selected($ads->show == '1') value="1">اظهار</option>
                <option @selected($ads->show == '0') value="0">اخفاء</option>
            </x-form.select>


            @foreach (config('app.languages') as $code => $name)
                <x-form.input type="file" accept="image/*" onchange="checkAllForms()"
                    label="ً{{ trans('words.advertisement_banner_image') }} ( {{ $name }} )" name="img" :code="$code">
                </x-form.input>
            @endforeach


            <div class="mt-2">
                <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>
            </div>
        </form>

@endsection

@section('js')
    <script>
        $('aside .ads').addClass('active');
        $('.modelSelect').select2()
    </script>
@endsection
