@extends('admin.layout')


@section('title')
    <title> {{ trans('words.المصاريف') }} </title>

    <style>
        .item-row {
            display: flex;
            column-gap: 10px;
            margin-bottom: 5px;
        }
    </style>
@endsection


@section('content')
    <audio id="errorSound" src="/assets/error.mp3"></audio>
    <audio id="successSound" src="/assets/success.mp3"></audio>


    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.المصاريف') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">

            <x-search_button></x-search_button>


            <x-cr_button title="{{ trans('words.اضافة') }}"></x-cr_button>


        </div>
    </div>

    <x-form.create_model id="createModel" title="{{ trans('words.اضافة') }}" path="admin/fees">
        <div class="row g-2">
            <x-form.input col="col-8" required label="{{ trans('words.العنوان') }}" name="name"></x-form.input>
            <x-form.input col="col-4" required type="number" min="1" label="{{ trans('words.السعر') }}"
                name="price"></x-form.input>

            <x-form.select col="col-4" reqiured name="year_id" label="{{ trans('words.السنة دراسية') }}">

                @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach

            </x-form.select>



            <x-form.select col="col-4" reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                @endforeach

            </x-form.select>





            <x-form.input col="col-4" dir="ltr" class="date" label="{{ trans('words.تاريخ الانتهاء') }}"
                name="end_at"></x-form.input>


            <div class="group col-12">

                <label for="students" class="mb-2"> {{ trans('words.الطلاب') }}</label>

                <select name="students[]" id="students" multiple></select>

            </div>


            <hr>

            <strong class="mb-2">{{ trans('words.البنود') }}</strong>

            <div id="itemsContainer">

            </div>

            <button type="button" id="addItem" class="es-btn-primary">{{ trans('words.إضافة بند') }}</button>

        </div>
    </x-form.create_model>


    <div class="tableSpace">
        <table id="sortable-table" class="mb-3">

            <thead>
                <tr>
                    <th>{{ trans('words.العنوان') }}</th>
                    <th>{{ trans('words.السنة دراسية') }}</th>
                    <th>{{ trans('words.السعر') }}</th>
                    <th>{{ trans('words.البنود') }}</th>
                    <th>{{ trans('words.المرحلة') }}</th>
                    <th>{{ trans('words.الحالة') }}</th>
                    <th>{{ trans('words.تاريخ الانتهاء') }}</th>
                    <th>{{ trans('words.actions') }}</th>
                </tr>
            </thead>

            <tbody class="clickable">

                @forelse ($fees as $fee)
                    <tr @class(['opacity-50' => $fee->deleted_at])>

                        <input type="hidden" value="{{ $fee->id }}" class="ids">


                        <td><x-icons.move></x-icons.move> {{ $fee->name }}</td>

                        <td>{{ $fee->year->name   ?? ''}}</td>
                        <td>{{ $fee->price }}</td>
                        <td>{{ $fee->items()->sum('price') }}</td>
                        <td>{{ $fee->stage->name ?? '' }}</td>

                        <td data-text='حالة المنتج '>
                            <div class="form-check form-switch">
                                <input value="{{ $fee->id }}" @checked($fee->enable) onchange="showHide(this)"
                                    class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                            </div>
                        </td>

                        <td>{{ fixdate($fee->end_at) }}</td>


                        <x-td_end normal id="{{ $fee->id }}" name="{{ $fee->name }}">

                            <x-form.link title="{{ trans('words.btn_update') }}" style=" height: 32px;"
                                path="admin/fees/{{ $fee->id }}/edit"></x-form.link>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="10">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>


        @if ($fees->isNotEmpty())
            <x-form.button onclick="UpdateOrder()" title="{{ trans('words.change') }}"></x-form.button>
        @endif

    </div>


    <x-form.delete title="" path="admin/fees/destroy"></x-form.delete>





    <x-form.search_model path="admin/fees/search">

        <x-form.input value="{{ $_GET['name'] ?? '' }}" type="search" label="{{ trans('words.العنوان') }}"
            name="name"></x-form.input>


        <div class="col-lg-6 col-12 group ">
            <label for="enable" class="mb-2"> {{ trans('words.الحالة') }}</label>
            <select name="enable" class="modelSelect w-100 ">
                <option value="">{{ trans('words.كل الحالات') }}</option>
                <option @selected(isset($_GET['enable']) && $_GET['enable'] == '1') value="1">{{ trans('words.نشط') }}</option>
                <option @selected(isset($_GET['enable']) && $_GET['enable'] == '0') value="0">{{ trans('words.غير نشط') }} </option>
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

        <div class="col-lg-6 col-12 group ">
            <label for="year_id" class="mb-2"> {{ trans('words.السنة دراسية') }}</label>
            <select name="year_id" class="modelSelect w-100 ">
                <option value="">{{ trans('words.الكل') }}</option>
                @foreach ($years as $year)
                    <option value="{{ $year->id }}" @selected(isset($_GET['year_id']) && $_GET['year_id'] == $year->id)> {{ $year->name }} </option>
                @endforeach
            </select>
        </div>




    </x-form.search_model>
@endsection


@section('js')
    <x-move model="fees"></x-move>


    <script>
        $('li.fees').addClass('active');

        $(document).ready(function() {

            $('#createModel .modelSelect').select2({
                dropdownParent: $('#createModel .modal-content')
            });

            $('#createModel #students').select2({
                dropdownParent: $('#createModel .modal-content'),
                multiple: true,
                tags: true
            });



            $('#searchModel .modelSelect').select2({
                dropdownParent: $('#searchModel .modal-content')
            });


            flatpickr('input.date', {
                enableTime: false,
                dateFormat: "Y-m-d"
            });

        });
    </script>


    <script>
        $(document).ready(function() {
            let counter = 0;

            $('#addItem').click(function() {
                counter++;
                $('#itemsContainer').append(`
                <div class="item-row" id="itemRow${counter}">
                    <input type="text" name="items[${counter}][name]" placeholder="{{ trans('words.العنوان') }}" required>
                    <input type="number" name="items[${counter}][price]" placeholder="{{ trans('words.السعر') }}" required>
                    <button type="button" class="removeitem es-btn-primary delete" data-id="${counter}">{{ trans('words.إزالة') }}</button>
                </div>
            `);
            });

            $('#itemsContainer').on('click', '.removeitem', function() {
                const id = $(this).data('id');
                $(`#itemRow${id}`).remove();
            });
        });

        document.getElementById('submitBtn').addEventListener('click', function() {
            this.type = 'button';


            $.ajax({
                url: $('#createModel form').attr('action'),
                type: 'POST',
                data: $('#createModel form').serialize(),
                beforeSend() {
                    $('#submitBtn').prop('disabled', true);
                },
                success: function(response) {
                    if (response.status == "error") {
                        error_with_sound(response.message)
                    } else if (response.status == "validation") {
                        error_with_sound(Object.values(response.message)[0][0])
                        validation
                    } else if (response.status == "success") {

                        Swal.fire({
                            title: "تم الاضافة بنجاج",
                            icon: "success"
                        }).then(() => {
                            window.location.reload()
                        });

                    }

                },
                error: function(xhr, status, error) {
                    console.log(error);

                    error_with_sound("هناك خطأ ما")
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false);
                }
            });
        });

        function showHide(e) {
            let id = e.value;

            let show = 0;

            if (e.checked) {
                show = 1;
            }

            $.ajax({
                url: '/admin/fees/showHide',
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
@endsection
