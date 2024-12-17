@extends('admin.layout')

@section('title')
    <title>{{ trans('words.المواد') }}</title>
@endsection


@section('content')
    @php
        $testName = $test->stage->name . ' ' . $test->year->name . ' ' . $test->study_type;
    @endphp

    <div class="actions border-0">

        <x-admin.layout.back back="admin/applications/tests"
            title="{{ trans('words.المواد') }} {{ $testName }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.اضافة') }}"></x-cr_button>
        </div>
    </div>
    <div class="tableSpace">
        <table class="mb-3">

            <thead>
                <tr>
                    <th>{{ trans('words.الاسم') }}</th>
                </tr>
            </thead>

            <tbody class="clickable">

                @forelse ($test->subjects as $place)
                    <tr>



                        <td>{{ $place->name }}</td>


                        <x-td_end id="{{ $place->id }}" name="{{ $place->name }}">

                            <x-update_button data-id="{{ $place->id }}" data-name="{{ $place->name }}"
                                data-code="{{ $place->code }}"></x-update_button>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="4">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>



    </div>


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة') }}" path="admin/applications/tests/subject">
        <div class="row g-2">

            <input type="hidden" name="test_id" value="{{ $test->id }}">

            <x-form.input col="col-12" reqiured label="{{ trans('words.اسم المادة') }}" name="name">
            </x-form.input>



        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.المادة') }}" path="admin/applications/tests/subject/destroy"></x-form.delete>

    <x-form.update_model path="admin/applications/tests/subject">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">

            <x-form.input col="col-12" reqiured label="{{ trans('words.اسم المادة') }}" name="nameInput">
            </x-form.input>

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
        $('aside .settings').addClass('active');

        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));
            $("#nameInput").val(e.getAttribute('data-name'));
            checkAllForms();
            $('#updateModel').modal('show');

        }
    </script>

@endsection
