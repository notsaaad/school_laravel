@extends('admin.layout')

@section('title')
    <title>{{ trans('words.advertisement_banners') }}</title>
@endsection


@section('content')
    <div class="actions ">

        <x-admin.layout.back title="{{ trans('words.advertisement_banners') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.add_advertisement_banner') }}"></x-cr_button>
        </div>
    </div>

    <div class="tableSpace">
        <table id="sortable-table" class="mb-3">
            <thead>
                <tr>
                    <th> {{ trans('words.advertisement_banner') }}</th>
                    <th>{{ trans('words.status') }}</th>
                    <th>{{ trans('words.actions') }}</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($all as $ads)
                    <tr>

                        <td><img width="200" src="{{ path("$ads->img") }}"></td>

                        @if ($ads->show)
                            <td><span class="Delivered">مفعل</span></td>
                        @else
                            <td><span class="tryAgain">غير مفعل</span></td>
                        @endif


                        <x-td_end  id="{{ $ads->id }}" name="{{ $ads->link }}">

                            <x-form.link title="{{trans('words.btn_update')}}" style=" height: 32px;"
                                path="admin/ads/{{ $ads->id }}/edit"></x-form.link>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="3">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>


    </div>


    <x-form.create_model img id="createModel" title="{{ trans('words.add') }}" path="admin/ads">
        <div class="row g-2">


            @foreach (config('app.languages') as $code => $name)
                <x-form.input required type="file" accept="image/*" onchange="checkAllForms()"
                    label="  {{ trans('words.advertisement_banner_image') }}  ( {{ $name }} )" name="img"
                    :code="$code">
                </x-form.input>
            @endforeach


            <x-form.input col="col-12" label="{{ trans('words.link') }}" name="link"></x-form.input>


        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.advertisement_banner') }}" path="admin/ads/destroy"></x-form.delete>
@endsection


@section('js')
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#createModel').modal('show');
            });
        </script>
    @endif


    <script>
        $('aside .ads').addClass('active');
    </script>
@endsection
