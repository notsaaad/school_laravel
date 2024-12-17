@extends('users.layout')



@section('title')
    <title> {{ $order->package->name ?? 'show Order' }} </title>
@endsection


@section('css')
    <style>
        body {
            background: white
        }

        .modal-body {
            text-align: left
        }

        table {
            background: #f8f8f8;
            border-collaps: unset;
            padding: 5px 10px;
            border-radius: 5px;

        }

        td {
            border-radius: 0px !important;
        }

        .td_end {
            display: flex;
            align-items: center;
            justify-content: center;
            column-gap: 10px;

        }

        div.square-btn {
            height: 40px;
        }

        div.square-btn i {
            font-size: 16px;
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
    </style>
@endsection

@section('content')
    <div class="content container mt-3">

        <div class="actions border-0">
            <x-admin.layout.back back="students/orders"
                title="{{ $order->package->name ?? 'show Order' }}"></x-admin.layout.back>
        </div>

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

                @if ($order->status == 'pending')
                    <div class="d-flex gap-2">
                        <x-form.link class="es-btn-primary" title=" Add Item"
                            path="students/items?addTo={{ $order->reference }}"></x-form.link>

                        <x-form.button class="delete" id="delete-order" data-order-id="{{ $order->id }}"> Cancel This
                            Order
                        </x-form.button>

                        <form id="delete-order-form" action="/students/orders/{{ $order->id }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                @endif




            </div>


            <div class="tableSpace my-3">
                <table>
                    <thead>

                        <tr>
                            <th> {{ trans('words.صورة المنتج') }}</th>
                            <th>{{ trans('words.اسم المنتج') }}</th>
                            <th> {{ trans('words.الكمية') }}</th>

                            @if ($order->status == 'pending')
                                <th>Actions</th>
                            @endif



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
                                        src="{{ $img }}"></td>


                                <td data-text='اسم المنتج'>{{ $detail->discription }}</td>

                                <td> {{ $detail->qnt }} </td>

                                @if ($order->status == 'pending')
                                    <td>
                                        <div class="td_end">
                                            <button type="button" class="es-btn-primary d-inline-block"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editSizeModal{{ $detail->product->id }}">
                                                Change Size
                                            </button>

                                            @if ($order->type == 'items')
                                                <div data-name="{{ $detail->discription }}" data-id="{{ $detail->id }}"
                                                    data-bs-target="#deleteModel" type="button" data-bs-toggle="modal"
                                                    onclick="delete_model(this)"
                                                    data-tippy-content="{{ trans('words.delete') }}"
                                                    class="square-btn ltr has-tip"><i
                                                        class="far fa-trash-alt mr-2 icon "></i>
                                                </div>

                                                <x-form.delete title="product"
                                                    path="students/orders/details/destroy"></x-form.delete>
                                            @endif

                                            <!-- Modal -->
                                            <div class="modal fade" id="editSizeModal{{ $detail->product->id }}"
                                                tabindex="-1" aria-labelledby="editSizeLabel{{ $detail->product->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editSizeLabel{{ $detail->product->id }}">
                                                                Edit Size
                                                                for {{ $detail->discription }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                action="/students/orders/{{ $order->reference }}">
                                                                @csrf
                                                                @method('put')

                                                                <input type="hidden" name="id"
                                                                    value ="{{ $detail->id }}">

                                                                <div class="mb-3">
                                                                    <label for="variant_id" class="form-label">Size</label>
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

                                                                <button type="submit" class="btn btn-success">Save
                                                                    changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif







                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>



            <p class="total"> Package : <span>{{ $order->price() }} EGP</span></p>
            <p class="total"> Service Fee : <span>{{ $order->fees() }} EGP</span></p>
            <p class="total"> Total Package : <span>{{ $order->sell_price() }} EGP</span></p>





        </div>

    </div>
@endsection

@section('js')
    <script>
        $('li.orders').addClass('active');
    </script>

    <script>
        document.getElementById('delete-order').addEventListener('click', function(event) {
            event.preventDefault(); // منع الحدث الافتراضي للنقر على الزر

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this order after deleting it!",
                icon: 'warning',
                input: 'textarea', // إدخال نصي متعدد الأسطر لسبب الإلغاء
                inputPlaceholder: 'Please enter the reason for cancellation...',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to provide a reason for cancellation!' // التحقق من إدخال السبب
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // الحصول على السبب من نافذة الإدخال
                    const cancellationReason = result.value;

                    // إضافة السبب إلى النموذج المخفي كحقل مخفي
                    let form = document.getElementById('delete-order-form');
                    let reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'reason';
                    reasonInput.value = cancellationReason;
                    form.appendChild(reasonInput);

                    // إرسال النموذج
                    form.submit();
                }
            });

        });
    </script>
@endsection
