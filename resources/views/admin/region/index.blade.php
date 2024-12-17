@extends('admin.layout')

@section('title')
    <title>{{ trans('words.المحافظات') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="admin/settings" :title="trans('words.settings')"></x-admin.layout.back>
    </div>


    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.المحافظات') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.اضافة محافظة') }}"></x-cr_button>
        </div>
    </div>
    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <tbody>

                @forelse ($all as $region)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $region->name }}
                        </td>

                        <input type="hidden" value="{{ $region->id }}" class="ids">


                        <x-td_end id="{{ $region->id }}" name="{{ $region->name }}">

                            <x-form.link title="{{ trans('words.places') }}" style=" height: 32px;margin: 0px 5px"
                            path="admin/regions/{{ $region->id }}/places"></x-form.link>

                            <x-update_button data-id="{{ $region->id }}"
                                data-name="{{ $region->name }}"></x-update_button>

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


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة محافظة') }}" path="admin/regions">
        <div class="row g-2">
            <x-form.input reqiured col="col-12" label="{{ trans('words.اسم المحافظة') }}" name="name">
            </x-form.input>

        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.المحافظة') }}" path="admin/regions/destroy"></x-form.delete>

    <x-form.update_model path="admin/regions">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">
            <x-form.input id="nameInput" col="col-12" reqiured label="{{ trans('words.اسم المحافظة') }}"
                name="nameInput">
            </x-form.input>
        </div>
    </x-form.update_model>
@endsection


@section('js')

    <x-move model="regions"></x-move>


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
