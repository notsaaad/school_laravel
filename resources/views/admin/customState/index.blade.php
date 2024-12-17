@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الحالات الخاصة') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="admin/settings" :title="trans('words.الحالات الخاصة')"></x-admin.layout.back>
    </div>


    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.settings') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.اضافة') }}"></x-cr_button>
        </div>
    </div>
    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <tbody>

                @forelse ($all as $custom_status)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $custom_status->name }}
                        </td>

                        <td>
                            {{ $custom_status->status }}
                        </td>

                        <input type="hidden" value="{{ $custom_status->id }}" class="ids">


                        <x-td_end id="{{ $custom_status->id }}" name="{{ $custom_status->name }}">

                            <x-update_button data-id="{{ $custom_status->id }}"
                                data-name="{{ $custom_status->name }}"></x-update_button>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="3"> {{ trans('words.no_data') }} </td>
                    </tr>
                @endforelse


            </tbody>
        </table>


        @if ($all->isNotEmpty())
            <x-form.button onclick="UpdateOrder()" title="{{ trans('words.change') }}"></x-form.button>
        @endif

    </div>


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة') }}" path="admin/custom_status">
        <div class="row g-2">
            <x-form.input required label="{{ trans('words.اسم الحالة') }}" name="name">
            </x-form.input>

            <x-form.select reqiured name="status" label="{{ trans('words.الحالة') }}">

                @php
                    $status = ['new', 'paid', 'complate', 'returned', 'canceled'];
                @endphp

                <option value="" disabled selected>Select</option>

                @foreach ($status as $state)
                    <option value="{{ $state }}"> {{ $state }} </option>
                @endforeach

            </x-form.select>




        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.الحالة') }}" path="admin/custom_status/destroy"></x-form.delete>

    <x-form.update_model path="admin/custom_status">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">
            <x-form.input id="nameInput" col="col-12" required label="{{ trans('words.اسم الحالة') }}" name="nameInput">
            </x-form.input>
        </div>
    </x-form.update_model>
@endsection


@section('js')

    <x-move model="custom_status"></x-move>


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


        $(document).ready(function() {

            $('#createModel .modelSelect').select2({
                dropdownParent: $('#createModel .modal-content')
            });

        });
    </script>

@endsection
