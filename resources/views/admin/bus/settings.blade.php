@extends('admin.layout')

@section('title')
    <title>{{ $bus->name }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="admin/buses" title="{{ $bus->name }}"></x-admin.layout.back>

        <div class="d-flex gap-2">

            <x-cr_button title="{{ trans('words.اضافة رحلة') }}"></x-cr_button>
        </div>
    </div>

    <div class="tableSpace">
        <table class="mb-3">

            <thead>
                <tr>
                    <th>{{ trans('words.عنوان الرحلة') }}</th>
                    <th>{{ trans('words.السعر') }}</th>
                    <th>{{ trans('words.عدد كراسي الذهاب') }}</th>
                    <th>{{ trans('words.عدد كراسي العودة') }}</th>
                    <th>{{ trans('words.تاريخ الانتهاء') }}</th>
                    <th>{{ trans('words.actions') }}</th>

                </tr>
            </thead>

            <tbody class="clickable">

                @forelse ($bus->settings as $setting)
                    <tr>


                        <td>{{ $setting->name }}</td>
                        <td>{{ $setting->price }}</td>
                        <td>{{ $setting->go_chairs_count }}</td>
                        <td>{{ $setting->return_chairs_count }}</td>
                        <td>{{ $setting->date }}</td>


                        <x-td_end id="{{ $setting->id }}" name="{{ $setting->name }}">

                            <x-update_button data-id="{{ $setting->id }}" data-name="{{ $setting->name }}"
                                data-go_chairs_count="{{ $setting->go_chairs_count }}"
                                data-return_chairs_count="{{ $setting->return_chairs_count }}"
                                data-price="{{ $setting->price }}" data-date="{{ $setting->date }}"></x-update_button>

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


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة رحلة') }}" path="admin/buses/{{ $bus->id }}/settings">
        <div class="row g-2">

            <input type="hidden" name="bus_id" value="{{ $bus->id }}">

            <x-form.input required col="col-8" label="{{ trans('words.عنوان الرحلة') }}" name="name">
            </x-form.input>


            <x-form.input required col="col-4" label="{{ trans('words.السعر') }}" name="price" type="number"
                min="0"></x-form.input>

            <x-form.input required col="col-4" type="number" min="0"
                label="{{ trans('words.عدد كراسي الذهاب') }}" name="go_chairs_count">
            </x-form.input>
            <x-form.input required col="col-4" type="number" min="0"
                label="{{ trans('words.عدد كراسي العودة') }}" name="return_chairs_count">
            </x-form.input>
            <x-form.input required col="col-4" label="{{ trans('words.تاريخ الانتهاء') }}" name="date"
                class="date">
            </x-form.input>




        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.الرحلة') }}" path="admin/buses/settings/destroy"></x-form.delete>

    <x-form.update_model path="admin/buses/settings">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">
            <x-form.input required col="col-8" label="{{ trans('words.عنوان الرحلة') }}" name="nameInput">
            </x-form.input>


            <x-form.input required col="col-4" label="{{ trans('words.السعر') }}" name="priceInput" type="number"
                min="0"></x-form.input>

            <x-form.input required col="col-4" type="number" min="0"
                label="{{ trans('words.عدد كراسي الذهاب') }}" name="go_chairs_countInput">
            </x-form.input>
            <x-form.input required col="col-4" type="number" min="0"
                label="{{ trans('words.عدد كراسي العودة') }}" name="return_chairs_countInput">
            </x-form.input>
            <x-form.input required col="col-4" label="{{ trans('words.تاريخ الانتهاء') }}" name="dateInput"
                class="date">
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
        $('aside .buses').addClass('active');


        flatpickr('input.date', {
            enableTime: false,
            dateFormat: "Y-m-d"
        });



        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));
            $("#nameInput").val(e.getAttribute('data-name'));
            $("#priceInput").val(e.getAttribute('data-price'));
            $("#go_chairs_countInput").val(e.getAttribute('data-go_chairs_count'));
            $("#return_chairs_countInput").val(e.getAttribute('data-return_chairs_count'));
            $("#dateInput").val(e.getAttribute('data-date'));
            checkAllForms();
            $('#updateModel').modal('show');

        }
    </script>

@endsection
