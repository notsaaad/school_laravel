@extends('admin.layout')

@section('title')
    <title>{{ $transfer->name ?? $transfer->code }}</title>
@endsection


@section('content')
    <div class="actions border-0">



        <x-admin.layout.back back="admin/transfers" title="{{ $transfer->name ?? $transfer->code }}"> [
            المتبقي {{ $totalFees }} ] [
            اجمالي المضاف {{ $totalFeesInTrans }} ] </x-admin.layout.back>


        @if ($transfer->type == 'pending')
            <div class="d-flex gap-2">

                @can('has', 'actions_transfers')
                    <x-cr_button title="{{ trans('words.اضافة اوردرات') }}"></x-cr_button>

                    <form id="paid-form" action="/admin/transfers/{{ $transfer->id }}/paid" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endcan

                @if (count($transfer->orders) > 1)
                    @can('has', 'paid_transfers')
                        <x-form.button class="es-btn-primary" style="background:rgb(16 185 129);" id="paid_btn"
                            data-order-id="{{ $transfer->id }}">
                            {{ trans('words.دفع المستحقات') }}
                        </x-form.button>
                    @endcan
                @endif



            </div>
        @elseif($transfer->type == 'paid' && count($transfer->orders) > 1)
            @can('has', 'confirm_transfers')
                <x-form.button class="es-btn-primary" style="background:rgb(16 185 129);" id="confirm_btn"
                    data-order-id="{{ $transfer->id }}">
                    {{ trans('words.استلام المسحقات') }}
                </x-form.button>


                <form id="confirm-form" action="/admin/transfers/{{ $transfer->id }}/confirm" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            @endcan
        @endif




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
                        <th>{{ trans('words.كود الطلب') }}</th>
                        <th>{{ trans('words.الوصف') }}</th>
                        <th>{{ trans('words.السعر') }}</th>
                        <th>{{ trans('words.العمولة المستحقة') }}</th>
                        <th>{{ trans('words.الحالة') }}</th>
                        <th>{{ trans('words.تاريخ الشراء') }} </th>

                        @can('has', 'show_in_order')
                            <th>{{ trans('words.actions') }}</th>
                        @endcan
                    </tr>

                </thead>

                <tbody class="clickable">

                    @foreach ($transfer->orders as $order)
                        <tr>


                            <td>{{ $loop->index + 1 }}</td>
                            <td> <span class="copy" onclick="copy('{{ $order->reference }}')"><i
                                        class="fa-regular fa-clipboard"></i></span>{{ $order->reference }} </td>


                            <td>{{ $order->package->name ?? 'Items' }}</td>
                            <td>{{ $order->sell_price() }} EGP</td>

                            <td>{{ $order->fees() }} EGP</td>


                            <td><span class="orderStatus {{ $order->status }} ">{{ $order->status }} </span>
                            </td>
                            <td>{{ fixDate($order->created_at) }}</td>


                            @can('has', 'show_in_order')
                                <td>
                                    <div onclick="GetOrderDetails({{ $order->id }} , 'admin')"
                                        data-tippy-content="{{ trans('words.عرض المنتجات') }}" class="square-btn ltr has-tip">
                                        <i class="fa-regular fa-eye mr-2 icon fa-fw"></i>
                                    </div>
                                </td>
                            @endcan



                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <x-orderFrame></x-orderFrame>


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة اوردرات') }}"
        path="admin/transfers/{{ $transfer->id }}/orders">
        <div class="row g-2">

            <x-form.input col="col-12" type="number" min="0" label="المبلغ" name="amount"></x-form.input>

            <p id="messageContent" class="text-danger"></p>



        </div>
    </x-form.create_model>
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
        $('aside .transfers').addClass('active');
    </script>



    @can('has', 'paid_transfers')
        @if ($transfer->type == 'pending')
            <script>
                document.getElementById('paid_btn').addEventListener('click', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'هل أنت متأكد من دفع المستحقات ؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "نعم متأكد",
                        cancelButtonText: 'الغاء',

                    }).then((result) => {
                        if (result.isConfirmed) {
                            let form = document.getElementById('paid-form');
                            form.submit();
                        }
                    });

                });
            </script>
        @endif
    @endcan


    @can('has', 'confirm_transfers')
        @if ($transfer->type == 'paid')
            <script>
                document.getElementById('confirm_btn').addEventListener('click', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'هل أنت متأكد من استلام المستحقات ؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "نعم متأكد",
                        cancelButtonText: 'الغاء',

                    }).then((result) => {
                        if (result.isConfirmed) {
                            let form = document.getElementById('confirm-form');
                            form.submit();
                        }
                    });

                });
            </script>
        @endif
    @endcan

    <script>
        let typingTimer;
        const doneTypingInterval = 250;

        $("#submitBtn").hide();



        $('#amount').on('keyup', function() {
            clearTimeout(typingTimer); // إلغاء أي تايمر سابق
            typingTimer = setTimeout(() => {
                var amount = $('#amount').val();

                if (amount > 0) {
                    $.ajax({
                        url: '/admin/transfers/checkAmount',
                        data: {
                            amount: amount,
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
                } else {

                    $('#messageContent').html("");
                    $("#submitBtn").hide();
                }


            }, doneTypingInterval);
        });
    </script>

@endsection
