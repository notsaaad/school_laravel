@extends('admin.layout')


@section('title')
    <title>{{ $fee->name }}</title>


    <style>
        .item-row {
            display: flex;
            column-gap: 10px;
            margin-bottom: 5px;
        }
    </style>
@endsection




@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/fees" title="{{ $fee->name }}"></x-admin.layout.back>
    </div>


    <form class="row form_style" action="/admin/fees/{{ $fee->id }}" method="post">

        @csrf
        @method('put')

        <x-form.input col="col-lg-4 col-12" required label="{{ trans('words.العنوان') }}" name="name"
            value="{{ $fee->name }}"></x-form.input>

        <x-form.input value="{{ $fee->price }}" col="col-lg-2 col-6" required type="number" min="1"
            label="{{ trans('words.السعر') }}" name="price"></x-form.input>

        <x-form.select col="col-lg-2 col-6" reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

            <option value="" disabled selected>Select</option>
            @foreach ($stages as $stage)
                <option @selected($stage->id == $fee->stage_id) value="{{ $stage->id }}">{{ $stage->name }}</option>
            @endforeach

        </x-form.select>


        <x-form.select col="col-lg-2 col-6" reqiured name="year_id" label="{{ trans('words.السنة دراسية') }}">
            <option value="" disabled selected>Select</option>

            @foreach ($years as $year)
                <option @selected($year->id == $fee->year_id) value="{{ $year->id }}">{{ $year->name }}</option>
            @endforeach

        </x-form.select>

        <x-form.input col="col-lg-2 col-6" value="{{ $fee->end_at }}" dir="ltr" class="date"
            label="{{ trans('words.تاريخ الانتهاء') }}" name="end_at"></x-form.input>


        <div class="group col-12">

            <label for="students" class="mb-2"> {{ trans('words.الطلاب') }}</label>

            <select name="students[]" id="students" multiple>
                @foreach ($fee->students as $student)
                    <option selected value="{{ $student->code }}">{{$student->name}} [{{ $student->code }}]</option>
                @endforeach
            </select>

        </div>





        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>

    </form>

    {{-- fees items --}}


    <div class="actions border-0  mt-3">
        <x-admin.layout.back title="{{ trans('words.البنود') }}"></x-admin.layout.back>
        <x-cr_button title="{{ trans('words.إضافة بند') }}"></x-cr_button>

    </div>

    <div class="tableSpace">
        <table id="sortable-table" class="mb-3">

            <tbody class="clickable">

                @forelse ($fee->items as $item)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $item->name }}
                        </td>

                        <td>
                            {{ $item->price }}
                        </td>


                        <input type="hidden" value="{{ $item->id }}" class="ids">


                        <x-td_end id="{{ $item->id }}" name="{{ $item->name }}">

                            <div type="button" data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                data-price="{{ $item->price }}" onclick="show_new_value_model(this)"
                                data-bs-toggle="modal" data-bs-target="#exampleModal2"
                                data-tippy-content="{{ trans('words.btn_update') }}" class="square-btn ltr has-tip">
                                <i class="far fa-edit mr-2 icon" aria-hidden="true"></i>
                            </div>


                        </x-td_end>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>


        @if ($fee->items->isNotEmpty())
            <x-form.button onclick="UpdateOrder()" title="{{ trans('words.change') }}"></x-form.button>
        @endif

    </div>

    <x-form.delete title="" path="admin/fees/items/destroy"></x-form.delete>


    <form method="post" action="{{ url('admin/fees/items/update') }}" class="modal fade" id="exampleModal2" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        @csrf
        @method('put')
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ trans('words.btn_update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <input type="hidden" name="value_id">

                    <x-form.input name="new_name_input" label="{{ trans('words.العنوان') }}"></x-form.input>
                    <x-form.input type="number" min="1" name="new_price_input"
                        label="{{ trans('words.السعر') }}"></x-form.input>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary"> {{ trans('words.btn_update') }} </button>

                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('words.close') }}</button>
                </div>
            </div>
        </div>
    </form>


    <x-form.create_model id="createModel" title="{{ trans('words.إضافة بند') }}"
        path="admin/fees/{{ $fee->id }}/items">
        <div class="row g-2">


            <div id="itemsContainer">
                <div class="item-row" id="itemRow0">
                    <input type="text" name="items[0][name]" placeholder="{{ trans('words.العنوان') }}" required>
                    <input type="number" name="items[0][price]" placeholder="{{ trans('words.السعر') }}" required>
                    <button type="button" class="removeitem es-btn-primary delete"
                        data-id="0">{{ trans('words.إزالة') }}</button>
                </div>
            </div>

            <button type="button" id="addItem" class="es-btn-primary">{{ trans('words.إضافة حقل') }}</button>


        </div>
    </x-form.create_model>
@endsection


@section('js')
    <x-move model="fees/items"></x-move>


    <script>
        $('aside .fees').addClass('active');

        $('.modelSelect').select2();

        flatpickr('input.date', {
            enableTime: false,
            dateFormat: "Y-m-d"
        });

        $('#students').select2({
            multiple: true,
            tags: true
        });

        function show_new_value_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_price = element.getAttribute('data-price')
            let data_id = element.getAttribute('data-id')

            $("#new_name_input").val(data_name)
            $("#new_price_input").val(data_price)
            $("input[name='value_id']").val(data_id)
        }


        $(document).ready(function() {
            let counter = 1;

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
    </script>
@endsection
