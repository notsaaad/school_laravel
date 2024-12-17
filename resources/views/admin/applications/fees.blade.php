@extends('admin.layout')

@section('title')
    <title>{{ trans('words.مصاريف التقديم') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.مصاريف التقديم') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">

            @can('has', 'fees_actions')
                <x-cr_button title="{{ trans('words.اضافة') }}"></x-cr_button>
            @endcan

        </div>
    </div>

    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <thead>
                <tr>
                    <th>{{ trans('words.المرحلة') }}</th>
                    <th>{{ trans('words.السنة دراسية') }}</th>
                    <th>{{ trans('words.نوع الدراسة') }}</th>
                    <th>{{ trans('words.المبلغ') }}</th>
                    @can('has', 'fees_actions')
                        <th>{{ trans('words.actions') }}</th>
                    @endcan
                </tr>
            </thead>

            <tbody>

                @forelse ($fees as $fee)
                    <tr>

                        <td>{{ $fee->stage->name }}</td>
                        <td>{{ $fee->year->name }}</td>
                        <td>{{ $fee->study_type }}</td>
                        <td>{{ $fee->amount }}</td>


                        @can('has', 'fees_actions')
                            @php
                                $name = $fee->stage->name . ' ' . $fee->year->name . ' ' . $fee->study_type;
                            @endphp

                            <x-td_end normal id="{{ $fee->id }}" name="{{ $name }}">

                                <x-update_button data-id="{{ $fee->id }}" data-stage_id="{{ $fee->stage_id }}"
                                    data-study_type="{{ $fee->study_type }}" data-year_id="{{ $fee->year_id }}"
                                    data-amount="{{ $fee->amount }}"></x-update_button>

                            </x-td_end>
                        @endcan

                    </tr>

                @empty
                    <tr>
                        <td colspan="5"> {{ trans('words.no_data') }} </td>
                    </tr>
                @endforelse


            </tbody>
        </table>
    </div>

    @can('has', 'fees_actions')
        <x-form.create_model id="createModel" title="{{ trans('words.اضافة') }}" path="admin/applications/fees">
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



                <x-form.input col="col-6" required type="number" min="1" label="{{ trans('words.المبلغ') }}"
                    name="amount"></x-form.input>




            </div>
        </x-form.create_model>

        <x-form.delete title="{{ trans('words.مصاريف التقديم') }}" path="admin/applications/fees/destroy"></x-form.delete>

        <x-form.update_model path="admin/applications/fees">
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



                <x-form.input col="col-6" required type="number" min="1" label="{{ trans('words.المبلغ') }}"
                    name="amountInput"></x-form.input>



            </div>
        </x-form.update_model>
    @endcan

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

            $("#amountInput").val(e.getAttribute('data-amount'));

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
