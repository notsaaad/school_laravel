@extends('admin.layout')



@section('title')
    <title> {{ $order->package->name ?? 'Show Order' }} </title>
@endsection


@section('css')
    <style>
        .status_div {
            display: flex;
            align-items: center;
            column-gap: 15px;
            margin-top: 10px;
            width: fit-content;
        }

        .status_div .title {
            font-weight: 700;
            font-size: 15px;
            margin: 0px;
            border: none;
            padding: 0px;

        }

        .status {
            font-weight: bold;
            text-align: center;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            /* لجعلها دائرية مثل لمبة */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin: 0px;
        }

        .paid {
            background-color: rgb(16 185 129);
            box-shadow: 0 0 12px 1px rgba(0, 255, 0, 0.7);
            /* توهج أخضر */
        }

        .unpaid {
            background-color: rgb(239 68 68 /1);
            box-shadow: 0 0 12px 1px rgba(255, 0, 0, 0.7);
            /* توهج أحمر */
        }


        body {
            background: white
        }

        table {
            background: #f8f8f8;
            border-collaps: unset;
            padding: 5px 10px;
            border-radius: 5px;

        }

        .history {
            color: #333;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            /* حجم الخط */
            flex-wrap: wrap;
            background-color: #f9f9f9;
            /* خلفية خفيفة */
            padding: 10px 15px;
            /* مسافة داخلية */
            margin-bottom: 10px;
            /* مسافة بين العناصر */
            border-radius: 8px;
            /* زوايا مستديرة */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            /* ظل خفيف */
        }




        .status_parant .select2-container {
            width: 200px !important;
        }


        .status_parant .select2-container--default .select2-selection--single {
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: ;
            border-color: rgb(67 118 109);
            outline-width: 0;
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(202 211 209 / var(--tw-ring-opacity));
        }

        .swal2-container {
            z-index: 999 !important
        }

        table {
            border-collapse: separate;
            border-spacing: 0 .5rem;
            width: 100%;
            font-weight: 700;
        }

        td {
            border-radius: 0px !important;
        }



        .firstRow {
            display: flex;
            align-items: center;
            padding: 0px;
            margin-top: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .id {
            font-size: 15px;
            font-weight: 600;
            color: var(--mainBg);
        }

        .firstRow div {
            margin-right: 5px;
        }

        .time {
            color: #7a7b97;
            font-size: 14px;
            font-weight: 700;
        }

        .total {
            margin: 5px 0px;
            font-size: 16px;
            font-weight: 600;
        }


        .total span {
            margin: 5px 0px;
            font-weight: 700;
            font-size: 15px;
            color: rgba(0, 0, 0, 0.8);
            color: var(--mainBg);
        }




        .sammery {
            background: #F8F1EB;
            padding: 25px 15px;
            border-radius: 20px;
        }

        .sammery_title {
            text-align: center;
            padding-bottom: 10px;
            margin-bottom: 10px;
            font-weight: 700;
            position: relative;
        }

        .sammery_title::after {
            position: absolute;
            content: "";
            border-bottom: 2px solid var(--mainBg);
            bottom: 0px;
            width: 120px;
            height: px;
            left: 50%;
            transform: translatex(-50%);
        }

        .d {
            display: flex;
            justify-content: space-between;
            padding-bottom: 8px;
        }

        .d .title {
            display: flex;
            flex-direction: column;
            color: black;
            font-weight: 600;
            border: none;


        }

        .d span {
            color: gray;
            font-size: 13px;
            margin-top: 2px;
        }


        .net_commation {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .net_commation .title {
            font-weight: 700;
            font-size: 17px;
            border: none;
            color: black;
            margin-bottom: 0px;
        }

        .net_commation .number {
            color: var(--mainBg);
            font-weight: 700;
            font-size: 25px;

        }
    </style>
@endsection

@section('content')


    <audio id="errorSound" src="/assets/error.mp3"></audio>
    <audio id="successSound" src="/assets/success.mp3"></audio>



    <div class="actions border-0">
        <x-admin.layout.back back="admin/orders" title="{{ $order->package->name ?? 'Show Order' }}">


        </x-admin.layout.back>
    </div>


    @can('has', 'show_sending_received')
        <div class="d-flex">

            <div class="status_div">
                <span @class([
                    'status',
                    'paid' => $order->sending,
                    'unpaid' => !$order->sending,
                ])></span>
                <span class="title"> دفع المستحقات </span>
            </div>


            <div class="status_div mx-5">
                <span @class([
                    'status',
                    'paid' => $order->received,
                    'unpaid' => !$order->received,
                ])></span>
                <span class="title">استلام المستحقات </span>
            </div>

        </div>
    @endcan




    <div class="mb-3">

        <div class="d-flex align-items-center justify-content-between mt-2 flex-wrap gap-2">
            <div>
                <div class="firstRow mx-0">
                    <div class="id"> <span class="copy" onclick="copy('{{ $order->reference }}')"><i
                                class="fa-regular fa-clipboard"></i></span> # {{ $order->reference }} </div>
                    <div class="orderStatus {{ $order->status }}">{{ $order->status }}</div>
                    <div class="time">
                        <span> {{ fixdate($order->created_at) }}
                        </span>
                    </div>


                </div>

                <div style="color: rgb(255, 90, 95);font-weight: 700;font-size: 15px;margin-top: 10px;">
                    {{ $order->reason }}</div>
            </div>

            <div class="actions">

                <x-form.link class="es-btn-primary" title="{{trans('words.اضافة منتج')}}"
                    path="admin/items/{{ $order->user_id }}?addTo={{ $order->reference }}"></x-form.link>


                {{-- تسوية --}}
                @if ($order->status == 'pending')
                    @can('has', 'order_payment')
                        <x-form.button type="button" id="openFormBtn" title="{{ trans('words.تسوية') }}"></x-form.button>
                        <form id="paymentForm" action="/admin/orders/{{ $order->id }}/payment" method="POST"
                            style="display:none;">
                            @csrf
                            <input type="hidden" id="amountInput" name="amount">
                            <input type="hidden" id="receivedAmountInput" name="receivedAmount">
                            <input type="hidden" id="paymentMethodInput" name="paymentMethod">
                        </form>
                    @endcan
                @endif

                {{-- تسليم --}}
                @if ($order->details()->where('picked', '0')->exists() == 'paid')
                    @can('has', 'picking_order')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orderModal">
                            {{ trans('words.تسليم الأوردر') }}
                        </button>
                    @endcan
                @endif


                {{-- استرجاع --}}
                @if ($order->status == 'picked' || $order->status == 'partially_picked')
                    @can('has', 'return_product')
                        <button type="button" class="delete" data-bs-toggle="modal" data-bs-target="#product_return_Modal">
                            {{ trans('words.استرجاع منتج') }}
                        </button>
                    @endcan
                @endif



                @if ($order->status == 'pending')
                    @can('has', 'cancel_order')
                        @if ($order->status == 'pending')
                            <x-form.button class="delete" id="delete-order" data-order-id="{{ $order->id }}">
                                {{ trans('words.الغاء الاوردر') }}
                            </x-form.button>

                            <form id="delete-order-form" action="/admin/orders/{{ $order->id }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endcan
                @endif


                @can('has', 'return_requested')
                    @if ($order->status == 'return_requested')
                        <div class="status_parant">
                            <x-form.select col="" required name="status_return" label="">

                                <option selected disabled value="{{ $order->status }}"> {{ $order->status }} </option>

                                <option value="paid">paid</option>
                                <option value="returned">returned</option>

                            </x-form.select>
                        </div>
                    @endif
                @endcan

                @can('has', 'return_requested')
                    @if ($order->status == 'pending')
                        <x-form.button class="delete" id="return_requested_btn" data-order-id="{{ $order->id }}">
                            {{ trans('words.طلب استرجاع') }}
                        </x-form.button>

                        <form id="return_requested-order-form" action="/admin/orders/{{ $order->id }}/return_requested"
                            method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                @endcan



            </div>


        </div>


        <div class="row">
            @can('has', 'show_in_order_in_show_page')



                <div class="col-lg-8 col-12 p-0">
                    <div class="tableSpace my-3">
                        <table>
                            <thead>

                                <tr>
                                    <th> {{ trans('words.صورة المنتج') }}</th>
                                    <th>{{ trans('words.اسم المنتج') }}</th>
                                    <th> {{ trans('words.الكمية') }}</th>
                                    <th>{{ trans('الكمية في المخزن') }}</th>

                                    @can('has', 'picking_order')
                                        <th>{{ trans('words.تم تسليمه') }}</th>
                                        <th>{{ trans('words.تاريخ التسليم') }}</th>
                                    @endcan



                                    @can('has', 'update_size')
                                        @if ($order->status == 'pending')
                                            <th> {{ trans('words.تعديل المقاس') }} </th>
                                        @endif
                                    @endcan
                                </tr>

                            </thead>

                            <tbody>


                                @foreach ($order->details as $detail)
                                    <tr>



                                        @php
                                            if (isset($detail->product->img)) {
                                                $img = str_replace('public', 'storage', $detail->product->img);
                                                $img = asset("$img");
                                            } else {
                                                $img = 'https://placehold.co/600x400?text=product+img';
                                            }

                                        @endphp

                                        <td><img width="80" height="60" style="object-fit: contain"
                                                src="{{ $img }}">
                                        </td>


                                        <td data-text='اسم المنتج'>{{ $detail->discription }}</td>

                                        <td> {{ $detail->qnt }} </td>



                                        <td>{{ $detail->variant->get_vairant_in_warehouse()->stock ?? 0 }}</td>

                                        @can('has', 'picking_order')
                                            @if ($detail->picked)
                                                <td><span class="Delivered">{{ trans('words.نعم') }}</span></td>
                                                <td>{{ fixdate($detail->picked_at) }}</td>
                                            @else
                                                <td><span class="orderStatus cancel">{{ trans('words.لا') }}</span></td>
                                                <td></td>
                                            @endif
                                        @endcan





                                        @can('has', 'update_size')
                                            @if ($order->status == 'pending' && count($detail->product->variants) > 1)
                                                <td>


                                                    <div data-bs-target="#editSizeModal{{ $detail->product->id }}" type="button"
                                                        data-bs-toggle="modal" class="square-btn ltr has-tip"><i
                                                            class="fa-regular fa-pen-to-square mr-2 icon "></i>
                                                    </div>



                                                    <!-- Modal -->
                                                    <div class="modal fade" id="editSizeModal{{ $detail->product->id }}"
                                                        tabindex="-1" aria-labelledby="editSizeLabel{{ $detail->product->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="editSizeLabel{{ $detail->product->id }}">
                                                                        {{ $detail->discription }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="POST"
                                                                        action="/admin/orders/{{ $order->reference }}">
                                                                        @csrf
                                                                        @method('put')

                                                                        <input type="hidden" name="id"
                                                                            value ="{{ $detail->id }}">

                                                                        <div class="mb-3">
                                                                            <label for="variant_id"
                                                                                class="form-label">Size</label>
                                                                            <select class="form-select" id="variant_id"
                                                                                name="variant_id">
                                                                                @foreach ($detail->product->variants as $vairnat)
                                                                                    <option @selected($vairnat->id == $detail->variant_id)
                                                                                        value="{{ $vairnat->id }}">
                                                                                        {{ $vairnat->value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <button type="submit" class="btn btn-success">
                                                                            {{ trans('words.تعديل المقاس') }}
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endcan






                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>

            @endcan


            @can('has', 'order_payment')
                <div class="col-lg-4 col-12 mt-2">
                    <div class="sammery">
                        <div class="sammery_title">{{ trans('words.الفاتورة') }}</div>

                        <div class="d">
                            <div class="title"> {{ trans('words.الباكيدج') }} </div>
                            <div class="number" id="order_price"> {{ $order->price() }} {{ trans('words.ج') }} </div>
                        </div>


                        <div class="d" style="border-bottom: 1px solid var(--mainBg)">
                            <div class="title"> {{ trans('words.مصاريف الخدمة') }} </div>
                            <div class="number" id="order_price"> {{ $order->fees() }} {{ trans('words.ج') }} </div>
                        </div>

                        <div class="d mt-2">
                            <div class="title"> {{ trans('words.الاجمالي') }} </div>
                            <div class="number" id="order_price"> {{ $order->sell_price() }} {{ trans('words.ج') }}</div>
                        </div>

                        <div class="d mb-0 pb-0">
                            <div class="title"> {{ trans('words.المدفوع') }} </div>
                            <div class="number" id="order_price"> {{ $order->amount_received() }} {{ trans('words.ج') }}
                            </div>
                        </div>

                        @foreach ($order->payment_history as $history)
                            <div class="history">
                                <span style="font-weight: bold;">{{ $history->amount }} {{ trans('words.ج') }}</span>
                                <span>{{ $history->type }}</span>
                                <span>{{ $history->auth_user->name  ?? ''}}</span>
                                <span style="color: #777;">{{ fixdate($history->created_at) }}</span>

                            </div>
                        @endforeach









                        <div class="net_commation">
                            <div class="title"> {{ trans('words.المطلوب دفعه') }}</div>

                            <div class="d-flex gap-1 align-items-center">
                                <div class="number" id="commation_price">
                                    {{ $order->sell_price() - $order->amount_received() }}</div>
                                <span>{{ trans('words.ج') }}</span>
                            </div>




                        </div>


                    </div>
                </div>
            @endcan

        </div>


    </div>


    <!-- المودال الخاص بتسليم الاوردر -->
    <div class="modal fade" id="orderModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="orderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel"> {{ trans('words.تسليم الأوردر') }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <x-form.input col="col-12" placeholder="Product Sku" type="search" name="sku"
                        label="{{ trans('words.ادخل كود المنتج') }}"></x-form.input>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('words.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- المودال الخاص باسترجاع الاوردر -->
    <div class="modal fade" id="product_return_Modal" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="product_return_Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="product_return_Label"> {{ trans('words.استرجاع منتج') }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <x-form.input col="col-12" placeholder="Product Sku" type="search" name="return_sku"
                        label="{{ trans('words.ادخل كود المنتج') }}"></x-form.input>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('words.close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $('li.orders').addClass('active');
    </script>

    @if ($order->status == 'pending')
        <script>
            document.getElementById('delete-order').addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'هل أنت متأكد من الغاء الاوردر ؟',
                    icon: 'warning',
                    input: 'textarea',
                    inputPlaceholder: "الرجاء إدخال سبب الإلغاء...",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "نعم متأكد",
                    cancelButtonText: 'الغاء',
                    inputValidator: (value) => {
                        if (!value) {
                            return "من فضلك اكتب سبب الالغاء"
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const cancellationReason = result.value;

                        let form = document.getElementById('delete-order-form');
                        let reasonInput = document.createElement('input');
                        reasonInput.type = 'hidden';
                        reasonInput.name = 'reason';
                        reasonInput.value = cancellationReason;
                        form.appendChild(reasonInput);

                        form.submit();
                    }
                });

            });
        </script>
        <script>
            document.getElementById('return_requested_btn').addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'هل أنت متأكد من عمل طلب استرجاع للاوردر ؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "نعم متأكد",
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const cancellationReason = result.value;

                        let form = document.getElementById('return_requested-order-form');
                        let reasonInput = document.createElement('input');
                        reasonInput.type = 'hidden';
                        reasonInput.name = 'reason';
                        reasonInput.value = cancellationReason;
                        form.appendChild(reasonInput);

                        form.submit();
                    }
                });

            });
        </script>
    @endif


    <script>
        document.getElementById('openFormBtn').addEventListener('click', function() {
            // عرض النموذج كنافذة منبثقة باستخدام SweetAlert2
            Swal.fire({
                title: 'إدخال تفاصيل الدفع',
                html: `
                    <div>
                        <label for="amount">المبلغ المراد استلامه:</label>
                        <x-form.input col="col-12" type="number" id="amount" name="" value="{{ $order->sell_price() - $order->amount_received() }}" readonly></x-form.input>
                    </div>
                    <div>
                        <label for="receivedAmount">المبلغ المستلم:</label>
                        <x-form.input col="col-12" type="number" id="receivedAmount" name=""></x-form.input>
                    </div>
                    <div>
                        <label for="remainingAmount">المبلغ المتبقي:</label>
                        <x-form.input col="col-12" type="number" id="remainingAmount" readonly name="" value="{{ $order->sell_price() - $order->amount_received() }}"></x-form.input>
                    </div>
                    <div>
                        <label for="paymentMethod">طريقة استلام النقود:</label>
                        <select id="paymentMethod" class="modelSelect">

                            <option value="" selected disabled>اختار طريقة استلام النقود</option>

                            <option value="visa">فيزا</option>
                            <option value="bank">بنك</option>
                            <option value="cash">كاش</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'إرسال',
                cancelButtonText: 'إلغاء',

                didOpen: () => {
                    // تفعيل Select2 على القائمة المختارة
                    $('.modelSelect').select2({
                        width: '100%'
                    });
                },

                preConfirm: () => {
                    // التأكد من صحة البيانات قبل الإرسال
                    let amount = document.getElementById('amount').value;
                    let receivedAmount = document.getElementById('receivedAmount').value;
                    let paymentMethod = document.getElementById('paymentMethod').value;

                    if (!amount || !receivedAmount || !paymentMethod) {
                        Swal.showValidationMessage('يرجى ملء جميع الحقول المطلوبة!');
                    } else {
                        // ملء القيم في الحقول المخفية في النموذج
                        document.getElementById('amountInput').value = amount;
                        document.getElementById('receivedAmountInput').value = receivedAmount;
                        document.getElementById('paymentMethodInput').value = paymentMethod;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // إرسال النموذج
                    document.getElementById('paymentForm').submit();
                }
            });
        });

        // حساب المبلغ المتبقي عند إدخال المبلغ المستلم
        document.addEventListener('input', function(event) {
            if (event.target.id === 'receivedAmount' || event.target.id === 'amount') {
                let amount = parseFloat(document.getElementById('amount').value) || 0;
                let receivedAmount = parseFloat(document.getElementById('receivedAmount').value) || 0;
                let remainingAmount = amount - receivedAmount;
                document.getElementById('remainingAmount').value = remainingAmount;
            }
        });
    </script>

    <script>
        $('#sku').keypress(function(e) {
            if (e.which == 13) {
                let orderData = $('#sku').val();

                if (orderData.trim() === "") {
                    toastr["error"]("من فضلك ادخل كود المنتج")
                    var audio = document.getElementById('errorSound');
                    audio.play();
                } else {
                    $.ajax({
                        url: '/admin/orders/{{ $order->id }}/picked',
                        method: 'POST',
                        data: {
                            sku: orderData,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == "error") {
                                error_with_sound(response.message)
                            } else if (response.status == "success") {
                                success_with_sound(response.message)

                            }
                        },
                        error: function() {

                            error_with_sound("هناك خطأ ما")

                        }
                    });
                }

                $('#sku').val("");

            }
        });

        $('#return_sku').keypress(function(e) {
            if (e.which == 13) {
                let orderData = $('#return_sku').val();

                if (orderData.trim() === "") {
                    toastr["error"]("من فضلك ادخل كود المنتج")
                    var audio = document.getElementById('errorSound');
                    audio.play();
                } else {
                    $.ajax({
                        url: '/admin/orders/{{ $order->id }}/return',
                        method: 'POST',
                        data: {
                            sku: orderData,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == "error") {
                                error_with_sound(response.message)
                            } else if (response.status == "success") {
                                success_with_sound(response.message)

                            }
                        },
                        error: function() {

                            error_with_sound("هناك خطأ ما")

                        }
                    });
                }

                $('#return_sku').val("");

            }
        });

        $('#orderModal').on('hidden.bs.modal', function() {
            location.reload();
        });
        $('#product_return_Modal').on('hidden.bs.modal', function() {
            location.reload();
        });

        $("#status_return").select2().on('change', function(e) {
            // حفظ القيمة المختارة
            var selectedValue = $(this).val();


            // إظهار التنبيه
            Swal.fire({
                title: 'هل أنت متأكد من تغير حالة الطلب',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // إنشاء نموذج وإرساله
                    var form = $('<form>', {
                        action: '/admin/orders/changeOrderStatus', // قم بتحديث الـ URL بالمسار الصحيح
                        method: 'POST'
                    }).append($('<input>', {
                        type: 'hidden',
                        name: 'status',
                        value: selectedValue
                    })).append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}' // إضافة رمز CSRF إذا كنت تستخدم Laravel
                    })).append($('<input>', {
                        type: 'hidden',
                        name: 'order_id',
                        value: '{{ $order->id }}' // إضافة رمز CSRF إذا كنت تستخدم Laravel
                    }));

                    // إرفاق النموذج بالجسم وإرساله
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection
