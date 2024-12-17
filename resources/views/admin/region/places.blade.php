@extends('admin.layout')

@section('title')
    <title>{{ trans('words.places') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="admin/settings/regions"
            title="{{ trans('words.places') }} {{ $region->name }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.add_place') }}"></x-cr_button>
        </div>
    </div>
    <div class="tableSpace">
        <table class="mb-3">

            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('words.place_name') }}</th>
                </tr>
            </thead>

            <tbody class="clickable">

                @forelse ($region->places as $place)
                    <tr>


                        <td>{{ $loop->index += 1 }}</td>

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


    <x-form.create_model id="createModel" title="{{ trans('words.add_place') }}" path="admin/regions/places">
        <div class="row g-2">

            <input type="hidden" name="region_id" value="{{ $region->id }}">

            <x-form.input col="col-12" reqiured label="{{ trans('words.place_name') }}" name="name">
            </x-form.input>



        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.place') }}" path="admin/regions/places/destroy"></x-form.delete>

    <x-form.update_model path="admin/regions/places">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">

            <x-form.input col="col-12" reqiured label="{{ trans('words.place_name') }}" name="nameInput">
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
