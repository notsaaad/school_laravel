@extends('admin.layout')





@section('title')
    <title> {{ trans('words.الطلبات') }} </title>
@endsection


@section('content')
    <div class="actions">

        <x-admin.layout.back title="{{ trans('words.الطلبات') }}"></x-admin.layout.back>
        <div class="d-flex gap-2 flex-wrap">


            <x-search_button></x-search_button>

        </div>

    </div>


    <div class="tableSpace">
        <table>
            <thead>

                <tr>
                    <th>#</th>

                </tr>

            </thead>

            <tbody>

                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>



    <x-form.search_model path="admin/buses/orders/search">


    </x-form.search_model>
@endsection


@section('js')
    <script>
        $('li.buses').addClass('active');
    </script>
@endsection
