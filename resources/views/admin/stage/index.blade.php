@extends('admin.layout')

@section('title')
    <title>{{ trans('words.المراحل') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.المراحل') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.اضافة مرحلة') }}"></x-cr_button>
        </div>
    </div>
    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <tbody>

                @forelse ($all as $stage)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $stage->name }}
                        </td>

                        <input type="hidden" value="{{ $stage->id }}" class="ids">


                        <x-td_end id="{{ $stage->id }}" name="{{ $stage->name }}">

                            <x-update_button data-id="{{ $stage->id }}"
                                data-name="{{ $stage->name }}"></x-update_button>

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


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة مرحلة') }}" path="admin/stages">
        <div class="row g-2">
            <x-form.input reqiured col="col-12" label="{{ trans('words.اسم المرحلة') }}" name="name">
            </x-form.input>

        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.المرحلة') }}" path="admin/stages/destroy"></x-form.delete>

    <x-form.update_model path="admin/stages">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">
            <x-form.input id="nameInput" col="col-12" reqiured label="{{ trans('words.اسم المرحلة') }}" name="nameInput">
            </x-form.input>
        </div>
    </x-form.update_model>
@endsection


@section('js')


    <x-move model="stages"></x-move>



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
        $('aside .stages').addClass('active');

        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));
            $("#nameInput").val(e.getAttribute('data-name'));
            checkAllForms();
            $('#updateModel').modal('show');

        }
    </script>

@endsection
