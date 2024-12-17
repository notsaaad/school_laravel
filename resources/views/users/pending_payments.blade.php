@extends('users.layout')



@section('title')
    <title> Pending payments </title>
@endsection



@section('content')
    @php
        $user = auth()->user();
    @endphp

    <div class="content container mt-3">

        <div class="sliderHeader w-100 px-1">

            <div class="d-flex">
                <h2 class="slide_h2"> Pending payments </h2>
            </div>
        </div>

        @foreach ($user->fees()->get() as $fee)
            <div class="user my-3">
                <div class="title"> {{ $fee->name }} </div>
                <div class="user_parant" style="  display: flex;justify-content: space-between;align-items: center">
                    <div class="user_content m-0 mt-2">

                        @php
                            $totalPrice = $fee->items()->sum('price');
                            $paidAmount = $fee->userPayments($user->id)->sum('amount');
                            $remainingAmount = $totalPrice - $paidAmount;
                        @endphp



                        @foreach ($fee->items as $item)
                            <div class="user_group">
                                <div class="user_group_title"> {{ $item->name }}</div>
                                <div class="data">{{ $item->price }} {{ trans('words.ج') }}</div>
                            </div>
                        @endforeach

                        <div class="user_group">
                            <div class="user_group_title">المبلغ كامل</div>
                            <div class="data">{{ $fee->price }} {{ trans('words.ج') }}</div>
                        </div>

                        <div class="user_group">
                            <div class="user_group_title"> اجمالي البنود </div>
                            <div class="data">{{ $totalPrice }} {{ trans('words.ج') }}</div>
                        </div>



                        @if ($fee->userPayments($user->id)->exists())
                            <div class="user_group">
                                <div class="user_group_title"> المبلغ المدفوع </div>
                                <div class="data">{{ $paidAmount }} {{ trans('words.ج') }}</div>
                            </div>


                            <div class="user_group">
                                <div class="user_group_title"> المبلغ المتبقي </div>
                                <div class="data">0</div>
                            </div>
                        @else
                            @php

                                $totalPrice = $fee->items->sum('price');
                                $totalPayments = $fee->items->sum(function ($item) use ($user) {
                                    return $item->userPayments($user->id)->sum('amount');
                                });
                                $remainingAmount = $totalPrice - $totalPayments;

                            @endphp
                            <div class="user_group">
                                <div class="user_group_title"> المبلغ المدفوع </div>
                                <div class="data">{{ $totalPayments }}
                                    {{ trans('words.ج') }}</div>
                            </div>

                            <div class="user_group">
                                <div class="user_group_title"> المبلغ المتبقي </div>
                                <div class="data">{{ $remainingAmount }} {{ trans('words.ج') }}</div>
                            </div>
                        @endif


                    </div>







                </div>
            </div>
        @endforeach



        {{-- <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Full Price</th>
                    <th>Items Price</th>
                    <th>Items Count</th>
                    <th>Paid Amount</th>
                    <th>Remaining Amount</th>
                </tr>
            </thead>

            <tbody>

                @php
                    $user = auth()->user();
                @endphp
                @foreach ($user->fees()->get() as $fee)
                    @if ($fee->userPayments($user->id)->exists())
                        @php

                            $totalPrice = $fee->price;
                            $totalPayments = $fee->userPayments($user->id)->sum('amount');
                            $remainingAmount = $totalPrice - $totalPayments;

                        @endphp
                    @else
                        @php

                            $totalPrice = $fee->items->sum('price');
                            $totalPayments = $fee->items->sum(function ($item) use ($user) {
                                return $item->userPayments($user->id)->sum('amount');
                            });
                            $remainingAmount = $totalPrice - $totalPayments;

                        @endphp
                    @endif

                    <tr>
                        <td data-title="Title"><strong>{{ $fee->name }}</strong></td>
                        <td data-title="Full">{{ $fee->price . ' ' . trans('words.ج') }}</td>
                        <td data-title="Items_Price">{{ $totalPrice . ' ' . trans('words.ج') }}</td>
                        <td data-title="Items_count">{{ $fee->items()->count() }}</td>
                        <td data-title="Paid Amount"><span
                                class="Delivered">{{ $totalPayments . ' ' . trans('words.ج') }}</span>
                        </td>
                        <td data-title="Remaining"><span
                                class="tryAgain">{{ $remainingAmount . ' ' . trans('words.ج') }}</span></td>
                    </tr>
                @endforeach

            </tbody>
        </table> --}}


        <div class="sliderHeader w-100 px-1 my-3">

            <h2 class="slide_h2"> Payment Logs </h2>

        </div>


        <table>
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Fee</th>
                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->payments as $payment)
                    <tr>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ fixdate($payment->created_at) }}</td>
                        <td>{{ $payment->payable->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
