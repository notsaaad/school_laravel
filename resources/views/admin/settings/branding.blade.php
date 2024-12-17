@extends('admin.layout')



@section('title')
    <title>{{ trans('words.settings') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="admin/settings" :title="trans('words.settings')"></x-admin.layout.back>
    </div>

    <form class="row form_style" action="/admin/settings" method="post" enctype="multipart/form-data">

        @csrf
        @method('put')


        <x-form.input required col="col-lg-3 col-12" name="website_ar_name" label="{{ trans('words.website_ar_name') }}"
            :value="settings('website_ar_name')"></x-form.input>

        <x-form.input required col="col-lg-3 col-12" name="website_en_name" label="{{ trans('words.website_en_name') }}"
            :value="settings('website_en_name')"></x-form.input>

        <x-form.input class="form-control" col="col-lg-3 col-12" onchange="checkAllForms()" type="file" accept="image/*"
            label="{{ trans('words.settings_logo') }}" name="logo"></x-form.input>

        <x-form.input class="form-control" col="col-lg-3 col-12" onchange="checkAllForms()" type="file" accept="image/*"
            label="{{ trans('words.settings_fav') }}" name="fav"></x-form.input>

        <x-form.input name="meta_description_ar" label="{{ trans('words.meta_description_ar') }}"
            :value="settings('meta_description_ar')"></x-form.input>

        <x-form.input name="meta_description_en" label="{{ trans('words.meta_description_en') }}"
            :value="settings('meta_description_en')"></x-form.input>

        <x-form.input col="col-lg-3 col-12" name="facebook" label="{{ trans('words.settings_facebook') }}"
            :value="settings('facebook')"></x-form.input>

        <x-form.input col="col-lg-3 col-12" name="instagram" label="{{ trans('words.settings_instagram') }}"
            :value="settings('instagram')"></x-form.input>

        <x-form.input col="col-lg-3 col-12" name="twitter" label="{{ trans('words.settings_twitter') }}"
            :value="settings('twitter')"></x-form.input>

        <x-form.input col="col-lg-3 col-12" name="tiktok" label="{{ trans('words.settings_tiktok') }}"
            :value="settings('tiktok')"></x-form.input>


            <x-form.input col="col-lg-3 col-12" name="linkedin" label="Linked In"
            :value="settings('linkedin')"></x-form.input>

            <x-form.input col="col-lg-3 col-12" name="youtube" label="Youtube"
            :value="settings('youtube')"></x-form.input>



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
