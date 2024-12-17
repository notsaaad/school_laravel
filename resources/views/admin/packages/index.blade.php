@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الحزم') }}</title>
@endsection

@section('content')
    <div class="actions">

        <x-admin.layout.back title="{{ trans('words.الحزم') }}"></x-admin.layout.back>
        @can('has', 'packages_action')
            <div class="d-flex gap-2 flex-wrap">

                <x-search_button></x-search_button>

                <x-cr_button title="{{ trans('words.إضافة حزمة') }}"></x-cr_button>
            </div>
        @endcan

    </div>

    <div class="tableSpace">
        <table id="sortable-table">
            <thead>

                <tr>
                    <th> {{ trans('words.صورة الحزمة') }}</th>
                    <th>{{ trans('words.اسم الحزمة') }}</th>
                    <th>{{ trans('words.المرحلة') }}</th>
                    <th>{{ trans('words.النوع') }}</th>
                    <th>{{ trans('words.سعر الحزمة') }} </th>
                    <th>{{ trans('عدد المنتجات') }}</th>
                    @can('has', 'packages_action')
                        <th> {{ trans('words.الحالة') }}</th>
                        <th> {{ trans('words.actions') }}</th>
                    @endcan
                </tr>

            </thead>

            <tbody>


                @forelse ($packages as $package)
                    <tr>



                        @php
                            if (isset($package->img)) {
                                $img = str_replace('public', 'storage', $package->img);
                                $img = asset("$img");
                            } else {
                                $img = 'https://placehold.co/600x400?text=package+img';
                            }

                        @endphp

                        <td><img width="80" height="60" style="object-fit: contain" src="{{ $img }}"></td>


                        <td data-text='اسم الحزمة'>
                            @can('has', 'packages_action')
                                <a class="package_link"
                                    href="/admin/packages/{{ $package->id }}/edit">{{ $package->name }}</a>
                            @else
                                {{ $package->name }}
                            @endcan


                        </td>

                        <td>{{ $package->stage->name }}</td>


                        @if ($package->gender == 'boy')
                            <td>{{ trans('words.ذكر') }}</td>
                        @elseif ($package->gender == 'girl')
                            <td>{{ trans('words.انثي') }}</td>
                        @else
                            <td>{{ trans('words.ذكر او انثي') }}</td>
                        @endif


                        <td> {{ $package->price }} </td>

                        <td>{{ $package->products_count }}</td>



                        @can('has', 'packages_action')
                            <td data-text='حالة الحزمة '>
                                <div class="form-check form-switch">
                                    <input value="{{ $package->id }}" @checked($package->show)
                                        onchange="showHidepackage(this)" class="form-check-input" type="checkbox"
                                        id="flexSwitchCheckChecked">
                                </div>
                            </td>

                            @if (!$package->deleted_at)
                                <x-td_end normal id="{{ $package->id }}" name="{{ $package->name }}">

                                    <x-form.link style="height: 32px" target="_self" title="{{ trans('words.btn_update') }}"
                                        path="admin/packages/{{ $package->id }}/edit"></x-form.link>



                                </x-td_end>
                            @else
                                <td style="text-align: right">
                                    <div type="button" data-id="{{ $package->id }}" data-name="{{ $package->name }}"
                                        onclick="show_restore_model(this)" data-tippy-content="استرجاع"
                                        class="square-btn ltr has-tip"><i style="color:#676565"
                                            class="fa-solid fa-trash-arrow-up mr-2 icon" aria-hidden="true"></i></div>
                                </td>
                            @endif
                        @endcan

                    </tr>

                @empty
                    <tr>

                        <td colspan="11">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>

    </div>

    <div class="pagnator">
        {{ $packages->appends(request()->query())->links() }}
    </div>



    <x-form.search_model path="admin/packages/search">

        <x-form.input value="{{ $_GET['name'] ?? '' }}" type="search" label="{{ trans('words.اسم الحزمة') }}"
            name="name"></x-form.input>


        <div class="col-lg-6 col-12 group ">
            <label for="show" class="mb-2"> {{ trans('words.الحالة') }}</label>
            <select name="show" class="modelSelect w-100 ">
                <option value="">{{ trans('words.كل الحالات') }}</option>
                <option @selected(isset($_GET['show']) && $_GET['show'] == '1') value="1">{{ trans('words.نشط') }}</option>
                <option @selected(isset($_GET['show']) && $_GET['show'] == '0') value="0">{{ trans('words.غير نشط') }} </option>
            </select>

        </div>

        <div class="col-lg-6 col-12 group ">
            <label for="gender" class="mb-2"> {{ trans('words.النوع') }}</label>
            <select name="gender" class="modelSelect w-100 ">
                <option @selected(isset($_GET['gender']) && $_GET['gender'] == 'both') value="">{{ trans('words.ذكر او انثي') }} </option>
                <option @selected(isset($_GET['gender']) && $_GET['gender'] == 'boy') value="boy">{{ trans('words.ذكر') }}</option>
                <option @selected(isset($_GET['gender']) && $_GET['gender'] == 'girl') value="girl">{{ trans('words.انثي') }} </option>
            </select>
        </div>


        <div class="col-lg-6 col-12 group ">
            <label for="stage_id" class="mb-2"> {{ trans('words.المرحلة') }}</label>
            <select name="stage_id" class="modelSelect w-100 ">
                <option value="">{{ trans('words.الكل') }}</option>
                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}" @selected(isset($_GET['stage_id']) && $_GET['stage_id'] == $stage->id)> {{ $stage->name }} </option>
                @endforeach
            </select>
        </div>


        <div data-title="محدوف" class="col-lg-6 col-12 group ">
            <label for="deleted" class="mb-2"> {{ trans('words.الحزم المحذوفة') }}</label>
            <select name="deleted" class="modelSelect w-100 ">
                <option value=""> {{ trans('words.all') }} </option>
                <option @selected(isset($_GET['deleted']) && $_GET['deleted'] == 'yes') value="yes"> {{ trans('words.محذوف فقط') }}</option>
                <option @selected(isset($_GET['deleted']) && $_GET['deleted'] == 'no') value="no"> {{ trans('words.غير محذوف فقط') }} </option>
            </select>

        </div>



    </x-form.search_model>



    @can('has', 'packages_action')
        <x-form.create_model img id="createModel" title="{{ trans('words.إضافة حزمة') }}" path="admin/packages">
            <div class="row g-2">



                <x-form.input col="col-6" required label="{{ trans('words.اسم الحزمة') }}" name="name">
                </x-form.input>

                <x-form.input col="col-6" accept="image/*" class="form-control" type="file"
                    label="{{ trans('words.صورة الحزمة') }}" name="img">
                </x-form.input>


                <x-form.select onchange="get_packages()" col="col-4" reqiured name="stage_id"
                    label="{{ trans('words.المرحلة') }}">

                    {{-- <option value="" disabled selected>{{ trans('words.select') }}</option> --}}

                    @foreach ($stages as $stage)
                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                    @endforeach

                </x-form.select>


                <x-form.select col="col-4" reqiured name="gender" label="{{ trans('words.النوع') }}">

                    {{-- <option value="" disabled selected>{{ trans('words.select') }}</option> --}}


                    <option value="boy">{{ trans('words.ذكر') }}</option>
                    <option value="girl">{{ trans('words.انثي') }}</option>
                    <option value="both">{{ trans('words.ذكر او انثي') }}</option>

                </x-form.select>

                <x-form.input col="col-4" required Type="number" label="{{ trans('words.سعر الحزمة') }}" name="price">
                </x-form.input>



                {{--
                <x-form.select col="col-12" multiple name="packages" label="{{ trans('words.الحزمةات') }}">


                </x-form.select> --}}




            </div>
        </x-form.create_model>

        <x-form.delete title="{{ trans('words.الحزمة') }}" path="admin/packages/destroy"></x-form.delete>
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
        $('aside .packages').addClass('active');

        function show_restore_model(e) {

            let element = e;


            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            if (confirm("هل انت متاكد من استرجاع " + data_name)) {
                window.location.href = `/admin/packages/restore/${data_id}`;
            }

        }

        function showHidepackage(e) {
            let id = e.value;

            let show = 0;

            if (e.checked) {
                show = 1;
            }

            $.ajax({
                url: '/admin/packages/showHidepackage',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    id: id,
                    show: show
                }),
                success: function(response) {
                    if (response.status == "error") {
                        Swal.fire({
                            text: response.message,
                            icon: "error"
                        });
                        $(e).prop('checked', false);
                    } else {
                        toastr["success"]("تم بنجاح");
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Network response was not ok', textStatus, errorThrown);
                }
            });

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
    </script>
@endsection
