@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الطلاب') }}</title>
@endsection

@section('css')
  <style>
    #searchModel  .delete{
      display:none !important;
    }
  </style>
@endsection

@section('content')


    <div class="actions">

        <x-admin.layout.back title="{{ trans('words.الطلاب') }}"></x-admin.layout.back>
        @can('has', 'students_action')
            <div class="d-flex gap-2 flex-wrap">

                <x-search_button></x-search_button>
                  @if(request()->has('name'))
                    <a href="{{ route('admin.student.index', request()->except('name')) }}" class="delete">ازالة الفلتر</a>
                  @endif
                <x-excel_button></x-excel_button>

                <x-upload_button></x-upload_button>


                <x-cr_button title="{{ trans('words.إضافة طالب') }}"></x-cr_button>
            </div>
        @endcan

    </div>

    <div class="tableSpace">
        <table>
            <thead>

                <tr>
                    <th>#</th>
                    <th>{{ trans('words.اسم الطالب') }}</th>
                    <th>{{ trans('words.كود الطالب') }}</< /th>
                    <th>{{ trans('words.المرحلة') }}</th>
                    <th>{{ trans('words.email') }}</< /th>
                    <th>{{ trans('words.phone') }}</< /th>
                    <th>{{ trans('words.account_status') }}</th>
                    <th>{{ trans('words.النوع') }}</th>
                    <th>{{ trans('words.يمتلك باكيدج') }}</th>

                    @can('has', 'students_action')
                        <th> {{ trans('words.actions') }}</th>
                    @endcan
                </tr>

            </thead>

            <tbody class="clickable">

                @php
                    $rowNumber = ($students->currentPage() - 1) * $students->perPage() + 1;
                @endphp

                @forelse ($students as $user)
                    <tr>
                        <td> {{ $rowNumber }} </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->code }}</td>
                        <td>{{ $user->stage->name ?? "" }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile }}</td>

                        @if ($user->active == 1)
                            <td data-text="حالة الحساب"><span class="Delivered">نشط</span></td>
                        @elseif($user->active == 0)
                            <td data-text="حالة الحساب"><span class="tryAgain">غير نشط</span></td>
                        @endif

                        @if ($user->gender == 'boy')
                            <td>{{ trans('words.ذكر') }}</td>
                        @else
                            <td>{{ trans('words.انثي') }}</td>
                        @endif

                        @if ($user->own_package == 'yes')
                            <td><span class="Delivered">{{ trans('words.نعم') }}</span></td>
                        @else
                            <td><span class="orderStatus cancel">{{ trans('words.لا') }}</span></td>
                        @endif





                        @can('has', 'students_action')
                            @if (!$user->deleted_at)
                                <x-td_end normal id="{{ $user->id }}" name="{{ $user->name }}">


                                    @can('has', 'fees_paid')
                                        <x-form.link style="height: 32px" target="_self" title="{{ trans('words.المصاريف') }}"
                                            path="admin/students/{{ $user->code }}/fees"></x-form.link>
                                    @endcan



                                    <x-form.link style="height: 32px" target="_self" title="{{ trans('words.btn_update') }}"
                                        path="admin/students/{{ $user->id }}/edit"></x-form.link>
                                </x-td_end>
                            @else
                                <td style="text-align: right">
                                    <div type="button" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                        onclick="show_restore_model(this)" data-tippy-content="{{ trans('words.recovery') }}"
                                        class="square-btn ltr has-tip"><i style="color:#676565"
                                            class="fa-solid fa-trash-arrow-up mr-2 icon" aria-hidden="true"></i></div>
                                </td>
                            @endif
                        @endcan


                    </tr>

                    @php
                        $rowNumber++;
                    @endphp
                @empty

                    <tr>
                        <td colspan="10">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>

    </div>

    <div class="pagnator">
        {{ $students->appends(request()->query())->links() }}
    </div>

    <x-form.search_model path="admin/students/search">

        <x-form.input value="{{ $_GET['name'] ?? '' }}" type="search" label="{{ trans('words.اسم الطالب') }}"
            name="name"></x-form.input>

        <x-form.input value="{{ $_GET['code'] ?? '' }}" type="search" label="{{ trans('words.كود الطالب') }}"
            name="code"></x-form.input>

        <input type="hidden" name="type" value={{ $_GET['type'] }}>

        <x-form.input value="{{ $_GET['email'] ?? '' }}" type="search" col="col-lg-6 col-12"
            label="{{ trans('words.email') }}" name="email"></x-form.input>
        <x-form.input value="{{ $_GET['mobile'] ?? '' }}" type="search" col="col-lg-6 col-12"
            label="{{ trans('words.phone') }}" name="mobile"></x-form.input>


        <div class="col-lg-6 col-12 group ">
            <label for="stage_id" class="mb-2"> {{ trans('words.المرحلة') }}</label>
            <select name="stage_id" class="modelSelect w-100 ">
                <option value="">{{ trans('words.الكل') }}</option>

                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}" @selected(isset($_GET['stage_id']) && $_GET['stage_id'] == $stage->id)> {{ $stage->name }} </option>
                @endforeach
            </select>
        </div>


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


    <x-form.upload_model path="admin/students/import">

        <x-form.input name="file" type="file" class="form-control" label="ملف الاكسيل" col="col-12"
            accept=".xlsx"></x-form.input>

        <a download class="es-btn-primary" href="/assets/excel/students.xlsx">تحميل نموذج</a>

    </x-form.upload_model>

    @can('has', 'students_action')
        <x-form.create_model id="createModel" title="{{ trans('words.إضافة طالب') }}" path="admin/students">
            <div class="row g-2">
                <x-form.input required label="{{ trans('words.اسم الطالب') }}" name="name">
                </x-form.input>
                <x-form.input required label="{{ trans('words.كود الطالب') }}" name="code">
                </x-form.input>

                <input type="hidden" name="type" value="{{ $_GET['type'] }}">
                <x-form.select reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

                    @foreach ($stages as $stage)
                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                    @endforeach


                </x-form.select>


                <x-form.select reqiured name="gender" label="{{ trans('words.النوع') }}">

                    <option value="boy">{{ trans('words.ذكر') }}</option>
                    <option value="girl">{{ trans('words.انثي') }}</option>

                </x-form.select>

                <x-form.select reqiured name="own_package" label="{{ trans('words.يمتلك باكيدج') }}">

                    <option value="no">{{ trans('words.لا') }}</option>
                    <option value="yes">{{ trans('words.نعم') }}</option>

                </x-form.select>

                <x-form.input type="email" label="{{ trans('words.email') }}" name="email"
                    placeHolder="eslam@gmail.com "></x-form.input>


                <x-form.input col="col-lg-6 col-12" label="{{ trans('words.phone') }}" name="mobile"
                    placeholder="010********">
                </x-form.input>

            </div>
        </x-form.create_model>

        <x-form.delete title="{{ trans('words.الطالب') }}" path="admin/students/destroy"></x-form.delete>
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
        $('aside .students').addClass('active');

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
