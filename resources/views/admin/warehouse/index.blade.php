@extends('admin.layout')

@section('title')
    <title>{{ trans('words.warehouses') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.warehouses') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.add_warehouse') }}"></x-cr_button>
        </div>
    </div>

    <div class="tableSpace">
        <table id="sortable-table" class="mb-3">

            <tbody class="clickable">

                @forelse ($all as $warehouse)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $warehouse->name }} 
                        </td>


                        <input type="hidden" value="{{ $warehouse->id }}" class="ids">

                        <x-td_end id="{{ $warehouse->id }}" name="{{ $warehouse->name }}">

                            <x-form.link title="{{ trans('words.عرض') }}" style=" height: 32px;"
                                path="admin/warehouses/{{ $warehouse->id }}/edit"></x-form.link>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="2">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>


        @if ($all->isNotEmpty())
            <x-form.button onclick="UpdateOrder()" title="{{ trans('words.change') }}"></x-form.button>
        @endif

    </div>


    <x-form.create_model id="createModel" title="{{ trans('words.add_warehouse') }}" path="admin/warehouses">
        <div class="row g-2">

            <x-form.input label="اسم المخزن"  col="col-12" name="name" required>
            </x-form.input>

        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.warehouse') }}" path="admin/warehouses/destroy"></x-form.delete>
@endsection


@section('js')
    <x-move model="warehouses"></x-move>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#createModel').modal('show');
            });
        </script>
    @endif


    <script>
        $('aside .warehouses').addClass('active');
    </script>
@endsection
