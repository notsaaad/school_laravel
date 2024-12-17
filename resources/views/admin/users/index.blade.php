@extends('admin.layout')

@section('title')
    <title>{{ trans('words.users') }}</title>
@endsection


@section('content')


    <div class="actions">

        <x-admin.layout.back title="{{ trans('words.users') }}"></x-admin.layout.back>
        @can('has', 'users_action')
            <div class="d-flex gap-2">

                <x-search_button></x-search_button>

                <x-excel_button></x-excel_button>

                <x-cr_button title="{{ trans('words.add_user') }}"></x-cr_button>
            </div>
        @endcan

    </div>

    <div class="position-relative">


        <div class="tableSpace">
            <table>
                <thead>

                    <tr>
                        <th>#</th>
                        <th>{{ trans('words.name') }}</th>
                        <th>{{ trans('words.email') }}</< /th>
                        <th>{{ trans('words.phone') }}</< /th>
                        <th>{{ trans('words.account_status') }}</th>

                        @can('has', 'users_action')
                            <th> {{ trans('words.actions') }}</th>
                        @endcan
                    </tr>

                </thead>

                <tbody class="clickable">

                    @php
                        $rowNumber = ($users->currentPage() - 1) * $users->perPage() + 1;
                    @endphp

                    @forelse ($users as $user)
                        <tr>
                            <td> {{ $rowNumber }} </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile }}</td>

                            @if ($user->active == 1)
                                <td data-text="حالة الحساب"><span class="Delivered">نشط</span></td>
                            @elseif($user->active == 0)
                                <td data-text="حالة الحساب"><span class="tryAgain">غير نشط</span></td>
                            @endif


                            @can('has', 'users_action')
                                @if (!$user->deleted_at)
                                    <x-td_end normal id="{{ $user->id }}" name="{{ $user->name }}">



                                        <x-form.link style="height: 32px" target="_self"
                                            title="{{ trans('words.btn_update') }}"
                                            path="admin/users/{{ $user->id }}/edit"></x-form.link>
                                    </x-td_end>
                                @else
                                    <td style="text-align: right">
                                        <div type="button" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            onclick="show_restore_model(this)"
                                            data-tippy-content="{{ trans('words.recovery') }}" class="square-btn ltr has-tip">
                                            <i style="color:#676565" class="fa-solid fa-trash-arrow-up mr-2 icon"
                                                aria-hidden="true"></i>
                                        </div>
                                    </td>
                                @endif
                            @endcan


                        </tr>

                        @php
                            $rowNumber++;
                        @endphp
                    @empty

                        <tr>
                            <td colspan="8">لا يوجد مستخدمين</td>
                        </tr>
                    @endforelse


                </tbody>
            </table>

        </div>
    </div>

    <div class="pagnator">
        {{ $users->appends(request()->query())->links() }}
    </div>

    <x-form.search_model path="admin/users/search">

        <x-form.input value="{{ $_GET['name'] ?? '' }}" type="search" col="col-lg-6 col-12"
            label="{{ trans('words.name') }}" name="name"></x-form.input>
        <x-form.input value="{{ $_GET['email'] ?? '' }}" type="search" col="col-lg-6 col-12"
            label="{{ trans('words.email') }}" name="email"></x-form.input>
        <x-form.input value="{{ $_GET['mobile'] ?? '' }}" type="search" col="col-lg-6 col-12"
            label="{{ trans('words.phone') }}" name="mobile"></x-form.input>


        <div data-title="حالة الحساب" class="col-lg-6 col-12 group ">
            <label for="active" class="mb-2"> {{ trans('words.account_status') }}</label>
            <select name="active" class="modelSelect w-100 ">
                <option value="">كل الحالات</option>
                <option @selected(isset($_GET['active']) && $_GET['active'] == '1') value="1">نشط</option>
                <option @selected(isset($_GET['active']) && $_GET['active'] == '0') value="0">غير نشط</option>
            </select>

        </div>

        <div data-title="محدوف" class="col-lg-6 col-12 group ">
            <label for="deleted" class="mb-2"> الحسابات المحذوفة </label>
            <select name="deleted" class="modelSelect w-100 ">
                <option value="">كل الحسابات</option>
                <option @selected(isset($_GET['deleted']) && $_GET['deleted'] == 'yes') value="yes">محذوف فقط</option>
                <option @selected(isset($_GET['deleted']) && $_GET['deleted'] == 'no') value="no">غير محذوف فقط</option>
            </select>

        </div>



    </x-form.search_model>

    @can('has', 'users_action')
        <x-form.create_model id="createModel" title="{{ trans('words.add_user') }}" path="admin/users">
            <div class="row g-2">
                <x-form.input col="col-lg-6 col-12" required label="{{ trans('words.name') }}" name="name"
                    placeHolder=" : اسلام احمد ">
                </x-form.input>

                <x-form.input col="col-lg-6 col-12" type="email" required label="{{ trans('words.email') }}" name="email"
                    placeHolder=" :eslam@gmail.com "></x-form.input>

                <x-form.input col="col-lg-6 col-12" required type="password" label="{{ trans('words.password') }}"
                    name="password"></x-form.input>

                <x-form.input col="col-lg-6 col-12" required type="password" label="{{ trans('words.confirm_password') }}"
                    name="password_confirmation"></x-form.input>

                <x-form.input col="col-lg-6 col-12" required label="{{ trans('words.phone') }}" name="mobile"
                    placeholder="010********">
                </x-form.input>



                <div class="col-lg-6 col-12" id="role_id">
                    <label for="role_id" class="mb-2">{{ trans('words.roles') }}<span class="text-danger">*</span>
                    </label>
                    <select name="role_id" class="modelSelect w-100 ">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-form.create_model>

        <x-form.delete title="{{ trans('words.user') }}" path="admin/users/destroy"></x-form.delete>
    @endcan

@endsection


@section('js')
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#createModel').modal('show');
            });
        </script>
    @endif

    <script>
        $('aside .users').addClass('active');

        function show_restore_model(e) {

            let element = e;


            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            if (confirm("هل انت متاكد من استرجاع " + data_name)) {
                window.location.href = `/admin/users/restore/${data_id}`;
            }

        }

        function check_role(e) {
            if (e.value == "admin") {
                $("#role_id").css("display", "block");
            } else {
                $("#role_id").css("display", "none");
            }
        }
    </script>

    <script>
        $(document).ready(function() {

            $('#createModel .modelSelect').select2({
                dropdownParent: $('#createModel .modal-content')
            });

            $('#searchModel .modelSelect').select2({
                dropdownParent: $('#searchModel .modal-content')
            });


        });
        $('.js-example-basic-single').select2();
    </script>
@endsection
