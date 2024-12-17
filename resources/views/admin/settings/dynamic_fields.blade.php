@extends('admin.layout')

@section('title')
    <title>الحقول المخصصة</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="admin/settings" :title="trans('words.settings')"></x-admin.layout.back>
    </div>


    <div class="actions border-0">

        <x-admin.layout.back title="الحقول المخصصة"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="اضافة حقل جديد"></x-cr_button>
        </div>
    </div>


    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <tbody>

                @forelse ($all as $field)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $field->name }}
                        </td>

                        <input type="hidden" value="{{ $field->id }}" class="ids">


                        <x-td_end id="{{ $field->id }}" name="{{ $field->name }}">

                            <x-update_button data-id="{{ $field->id }}" data-name="{{ $field->name }}"
                                data-options="{{ $field->options }}"
                                data-required="{{ $field->is_required }}"></x-update_button>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="2"> {{ trans('words.no_data') }} </td>
                    </tr>
                @endforelse


            </tbody>
        </table>


        @if ($all->isNotEmpty())
            <x-form.button onclick="UpdateOrder()" title="{{ trans('words.change') }}"></x-form.button>
        @endif

    </div>


    <x-form.create_model id="createModel" title="اضافة حقل" path="admin/dynamic_fields">
        <div class="row g-2">

            <x-form.input required label="اسم الحقل" name="name">
            </x-form.input>

            <x-form.select reqiured label="نوع الحقل" name="type">
                <option value="text">نص</option>
                <option value="number">رقم</option>
                <option value="checkbox">اختيار عدة اختيارات</option>
                <option value="select">قائمة منسدلة</option>
            </x-form.select>

            <x-form.textarea col="col-12" name="options" label="اختيارات"></x-form.textarea>


            <label>
                <input type="checkbox" name="is_required"> مطلوب
            </label>



        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.السنة دراسية') }}" path="admin/dynamic_fields/destroy"></x-form.delete>

    <x-form.update_model path="admin/dynamic_fields">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">
            <x-form.input id="nameInput" col="col-12" required label="{{ trans('words.اسم السنة دراسية') }}"
                name="nameInput">
            </x-form.input>

            <x-form.textarea col="col-12" name="optionsInput" label="اختيارات"></x-form.textarea>


            <label>
                <input type="checkbox" name="is_requiredInput" id="is_requiredInput"> مطلوب
            </label>





        </div>
    </x-form.update_model>
@endsection


@section('js')

    <x-move model="dynamic_fields"></x-move>


    @if ($errors->any())
        @if ($errors->has('idInput'))
            <script>
                $(document).ready(function() {
                    $('#updateModel').modal('show');
                });
            </script>
        @else
            <script>
                $(document).ready(function() {
                    $('#createModel').modal('show');
                });
            </script>
        @endif
    @endif


    <script>
        $('aside .settings').addClass('active');

        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));
            $("#nameInput").val(e.getAttribute('data-name'));
            $("#optionsInput").val(e.getAttribute('data-options'));


            console.log(e.getAttribute('data-required'));


            if (e.getAttribute('data-required') == true) {
                $("#is_requiredInput").prop('checked', true)
            } else {
                $("#is_requiredInput").prop('checked', false)
            }



            checkAllForms();
            $('#updateModel').modal('show');

        }



        $(document).ready(function() {

            $('#createModel .modelSelect').select2({
                dropdownParent: $('#createModel .modal-content')
            });
        });
    </script>

@endsection
