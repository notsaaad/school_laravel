@extends('admin.layout')


@section('title')
    <title> {{ trans('words.btn_update') }} {{ $user->name }}</title>
@endsection



@if ($user->role != 'admin')
    @section('css')
        <style>
            #role_id {
                display: none;
            }
        </style>
    @endsection
@endif


@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/users"
            title=" {{ trans('words.btn_update') }} {{ $user->name }}"></x-admin.layout.back>

        @can('has', 'user_login')
            <x-form.link class="es-btn-primary" path="admin/users/{{ $user->id }}/login"
                title="تسجيل الدخول علي اكونت {{ $user->name }}"></x-form.link>
        @endcan
    </div>


    <form class="row form_style" action="/admin/users/{{ $user->id }}" method="post">

        @csrf
        @method('put')


        <x-form.input col="col-lg-3 col-12" required label="{{ trans('words.name') }}" name="name"
            placeHolder=" : اسلام احمد " value="{{ $user->name }}"></x-form.input>

        <x-form.input col="col-lg-3 col-12" type="email" disabled label="{{ trans('words.email') }}" name="email"
            placeHolder=" :eslam@gmail.com " value="{{ $user->email }}"></x-form.input>

        <x-form.input col="col-lg-3 col-12" type="password" label="{{ trans('words.password') }}"
            name="password"></x-form.input>

        <x-form.input col="col-lg-3 col-12" type="password" label="{{ trans('words.confirm_password') }}"
            name="password_confirmation">
        </x-form.input>

        <x-form.input col="col-lg-3 col-12" required label="{{ trans('words.phone') }}" value="{{ $user->mobile }}"
            name="mobile" placeholder="010********"></x-form.input>


        <div class="col-lg-3 col-12 group " id="role_id">
            <label for="role_id" class="mb-2">{{ trans('words.roles') }}<span class="text-danger">*</span>
            </label>
            <select name="role_id" class="js-example-basic-single">
                @foreach ($roles as $role)
                    <option @selected($user->role_id == $role->id) value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="col-lg-3 col-12 group ">
            <label for="active" class="mb-2"> {{ trans('words.account_status') }}<span class="text-danger">*</span>
            </label>
            <select name="active" class="js-example-basic-single">
                <option @selected($user->active == '1') value="1">نشط</option>
                <option @selected($user->active == '0') value="0">غير نشط</option>
            </select>
        </div>





        <div class="col-12 mt-3">
            <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>



        </div>
    </form>
@endsection


@section('js')
    <script>
        $('aside .users').addClass('active');
        $('.js-example-basic-single').select2();

        function check_role(e) {
            if (e.value == "admin") {
                $("#role_id").css("display", "flex");
            } else {
                $("#role_id").css("display", "none");
            }
        }
    </script>
@endsection
