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
                    <th>اسم الطالب</th>
                    <th>ايميل الطالب</th>
                    <th>هاتف الطالب</th>
                    <th>العنوان</th>
                    <th>الاتوبيس</th>
                    <th>الرحلة</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th>تعديل</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{$order->user->name ?? ''}}</td>
                        <td>{{$order->user->email ?? ''}}</td>
                        <td>{{$order->user->mobile ?? ''}}</td>
                        <td>{{$order->address ?? ''}}</td>
                        <td>{{$order->bus->name ?? ''}}</td>
                        <td>{{$order->title ?? ''}}</td>
                        <td>{{$order->price ?? ''}}</td>
                        <td>{{$order->status ?? ''}}</td>
                        <td>
                            <div class="table-action">
                                <a href="{{route('bus.order.edit', $order->id)}}" class="btn btn-primary">تعديل</a>
                                <a href="{{route('bus.order.Delete', $order->id)}}" class="btn btn-danger">مسح</a>
                            </div>
                        </td>
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
