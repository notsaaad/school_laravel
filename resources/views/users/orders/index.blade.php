@extends('users.layout')



@section('title')
    <title> Orders </title>
@endsection

@section('content')
    <div class="content container mt-3">
        <div class="sliderHeader w-100 px-1">

            <div class="d-flex">
                <h2 class="slide_h2"> All Orders </h2> <span class="result">(
                    {{ count($orders) }} result )
                </span>
            </div>

        </div>


        <div class="position-relative">

            <div id="loader">
                <i class="fa-solid fa-spinner fa-spin"></i>
            </div>

            <div class="tableSpace">
                <table>
                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Reference</th>
                            <th>Discription</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Purchase date </th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody class="clickable">

                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td> <span class="copy" onclick="copy('{{ $order->reference }}')"><i
                                            class="fa-regular fa-clipboard"></i></span> <a
                                        href="/students/orders/{{ $order->reference }}">{{ $order->reference }}</a> </td>
                                <td>{{ $order->package->name ?? 'items' }}</td>
                                <td>{{ $order->getTotalPrice() }} EGP</td>
                                <td><span class="orderStatus {{ $order->status }} ">{{ $order->status }} </span>
                                </td>
                                <td>{{ fixDate($order->created_at) }}</td>


                                <td>
                                    <div onclick="GetOrderDetails({{ $order->id }} , 'students')"
                                        data-tippy-content="Show Products" class="square-btn ltr has-tip">
                                        <i class="fa-regular fa-eye mr-2 icon fa-fw"></i>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <x-orderFrame></x-orderFrame>
@endsection

@section('js')
    <script>
        $('li.orders').addClass('active');
    </script>
@endsection
