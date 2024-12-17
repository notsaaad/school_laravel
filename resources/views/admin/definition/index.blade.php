@extends('admin.layout')

@section('title')
    <title> {{$title}} </title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{$title}}"></x-admin.layout.back>

        <div class="d-flex gap-2">

            <x-cr_button title="{{ trans('words.اضافة') }}"></x-cr_button>
        </div>

    </div>
    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <tbody>

                @forelse ($all as $definition)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $definition->getName() }}
                        </td>

                        <input type="hidden" value="{{ $definition->id }}" class="ids">


                        <x-td_end id="{{ $definition->id }}" name="{{ $definition->getName() }}">

                            <x-update_button data-id="{{ $definition->id }}" data-ar_name="{{ $definition->ar_name }}"
                                data-en_name="{{ $definition->en_name }}"></x-update_button>

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


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة') }}" path="admin/definitions">
        <div class="row g-2">
            <x-form.input required label="{{ trans('words.الاسم بالعربي') }}" name="ar_name">
            </x-form.input>

            <x-form.input required label="{{ trans('words.الاسم بالانجليزي') }}" name="en_name">
            </x-form.input>

            <input type="hidden" value="{{ Request::segment(3) }}" name="type">
        </div>
    </x-form.create_model>

    <x-form.delete title="" path="admin/definitions/destroy"></x-form.delete>

    <x-form.update_model path="admin/definitions">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">

            <x-form.input required label="{{ trans('words.الاسم بالعربي') }}" name="ar_nameInput">
            </x-form.input>

            <x-form.input required label="{{ trans('words.الاسم بالانجليزي') }}" name="en_nameInput">
            </x-form.input>
        </div>
    </x-form.update_model>
@endsection


@section('js')

    <x-move model="definitions"></x-move>


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
        $('aside .definitions').addClass('active');

        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));
            $("#ar_nameInput").val(e.getAttribute('data-ar_name'));
            $("#en_nameInput").val(e.getAttribute('data-en_name'));
            checkAllForms();
            $('#updateModel').modal('show');

        }
    </script>

@endsection
