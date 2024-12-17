@extends('admin.layout')


@section('title')
    <title> {{ $fee->name }}</title>
@endsection





@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/students/{{ $user->code }}/fees" title="رجوع"></x-admin.layout.back>
    </div>

    <x-user :user="$user"></x-user>



    <div class="user">
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



            @can('has', 'fees_paid')
                @if (can_paid($fee, $user))
                    <x-cr_button title="استلام نقدية"></x-cr_button>
                @endif
            @endcan




        </div>
    </div>


    <x-form.create_model id="createModel" title="استلام نقدية"
        path="admin/students/{{ $user->id }}/fees/{{ $fee->id }}/paid">
        <div class="row g-2">

            <x-form.input required col="col-12" type="number" min="1" label="المبلغ المستلم"
                name="amount"></x-form.input>

            <p id="messageContent" class="text-danger"></p>

        </div>
    </x-form.create_model>


    <div class="my-3">
        <x-admin.layout.back title="سجل المدفوعات"></x-admin.layout.back>

    </div>



    <table>
        <thead>
            <tr>
                <th>المبلغ</th>
                <th>تاريخ الاستلام</th>
                <th>المستلم</th>
                <th>البيان</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user->payments as $payment)
                <tr>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ fixdate($payment->created_at) }}</td>
                    <td>{{ $payment->auth->name }}</td>
                    <td>{{ $payment->payable->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


@section('js')
    <script>
        $('aside .students').addClass('active');
        $('.modelSelect').select2();
    </script>

    <script>
        let typingTimer;
        const doneTypingInterval = 250;


        $('#amount').on('keyup', function() {
            clearTimeout(typingTimer); // إلغاء أي تايمر سابق
            typingTimer = setTimeout(() => {
                var amountReceived = $('#amount').val();

                if (!amountReceived || amountReceived <= 0) {
                    $('#messageContent').text('يرجى إدخال مبلغ صحيح.');
                    return;
                }

                $.ajax({
                    url: '/admin/fees/{{ $user->id }}/{{ $fee->id }}/calculate-payment',
                    data: {
                        amount: amountReceived,
                    },
                    beforeSend: function() {
                        $("#submitBtn").hide();
                    },
                    success: function(response) {
                        $('#messageContent').html(response.message);
                        $("#submitBtn").show();

                    },
                    error: function() {
                        $('#messageContent').text("'حدث خطأ أثناء معالجة الطلب.'");
                    }
                });
            }, doneTypingInterval);
        });
    </script>
@endsection
