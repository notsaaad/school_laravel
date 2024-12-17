@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الاختبارات') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.الاختبارات') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.اضافة') }}"></x-cr_button>
        </div>
    </div>

    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <thead>
                <tr>
                    <th>{{ trans('words.المرحلة') }}</th>
                    <th>{{ trans('words.السنة دراسية') }}</th>
                    <th>{{ trans('words.نوع الدراسة') }}</th>
                    <th>{{ trans('words.actions') }}</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($tests as $test)
                    <tr>

                        <td>{{ $test->stage->name }}</td>
                        <td>{{ $test->year->name }}</td>
                        <td>{{ $test->study_type }}</td>

                        @php
                            $name = $test->stage->name . ' ' . $test->year->name . ' ' . $test->study_type;
                        @endphp

                        <x-td_end normal id="{{ $test->id }}" name="{{ $name }}">


                            <x-form.link title="{{ trans('words.المواد') }}" style=" height: 32px;margin: 0px 5px"
                                path="admin/applications/tests/{{ $test->id }}/subjects"></x-form.link>


                            <x-update_button data-id="{{ $test->id }}" data-stage_id="{{ $test->stage_id }}"
                                data-study_type="{{ $test->study_type }}"
                                data-year_id="{{ $test->year_id }}"></x-update_button>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5"> {{ trans('words.no_data') }} </td>
                    </tr>
                @endforelse


            </tbody>
        </table>
    </div>


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة') }}" path="admin/applications/tests">
        <div class="row g-2">
            <x-form.select col="col-6" reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                @endforeach

            </x-form.select>


            <x-form.select col="col-6" reqiured name="study_type" label="{{ trans('words.نوع الدراسة') }}">

                <option value="national">National</option>
                <option value="international">International</option>

            </x-form.select>


            <x-form.select col="col-6" reqiured name="year_id" label="{{ trans('words.السنة دراسية') }}">

                @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach

            </x-form.select>


        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.الاختبار') }}" path="admin/applications/tests/destroy"></x-form.delete>

    <x-form.update_model path="admin/applications/tests">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">

            <x-form.select col="col-6" reqiured name="stage_idInput" label="{{ trans('words.المرحلة') }}">

                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                @endforeach

            </x-form.select>


            <x-form.select col="col-6" reqiured name="study_typeInput" label="{{ trans('words.نوع الدراسة') }}">

                <option value="national">National</option>
                <option value="international">International</option>

            </x-form.select>


            <x-form.select col="col-6" reqiured name="year_idInput" label="{{ trans('words.السنة دراسية') }}">

                @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach

            </x-form.select>



        </div>
    </x-form.update_model>
@endsection


@section('js')



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
        $('aside .applications').addClass('active');

        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));

            $("#stage_idInput").val(e.getAttribute('data-stage_id')).trigger('change');
            $("#year_idInput").val(e.getAttribute('data-year_id')).trigger('change');
            $("#study_typeInput").val(e.getAttribute('data-study_type')).trigger('change');
            checkAllForms();
            $('#updateModel').modal('show');

        }



        $(document).ready(function() {

            $('#createModel .modelSelect').select2({
                dropdownParent: $('#createModel .modal-content')
            });
            $('#updateModel .modelSelect').select2({
                dropdownParent: $('#updateModel .modal-content')
            });

            checkAllForms();

        });
    </script>



@endsection
