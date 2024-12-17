@extends('admin.layout')


@section('title')
    <title> {{ trans('words.btn_update') }} {{ $warehouse->name }}</title>
@endsection


@section('css')
    <style>
        /*/product*/

        .swal2-container.swal2-center>.swal2-popup {
            background: #f8f8f8;
        }


        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product {
            border-radius: .375rem;
            overflow: hidden;
            text-align: center;
            box-shadow: rgb(0 0 0 / 8%) 0px 2px 4px;
            padding: 5px;
            width: calc(25% - 10px);
            cursor: pointer
        }

        .product.selected {
            border: 1px solid green;
            /* Highlight selected products */
        }

        .modal-footer {
            border: 0px;
        }


        .product img {
            object-fit: contain;
            width: 100%;
            height: 80px;
            border-radius: .375rem;
        }

        .product_name {
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            display: -webkit-box;
            overflow: hidden;
            margin-top: 2px;
            font-weight: 700;
            font-size: 12px;
            color: #37474f;

        }

        .product_name p {
            line-height: 1.4rem;
            padding: 0px;
        }
    </style>
@endsection





@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/warehouses"
            title=" {{ trans('words.btn_update') }} {{ $warehouse->name }}"></x-admin.layout.back>
    </div>

    <form class="row form_style" action="/admin/warehouses/{{ $warehouse->id }}" method="post">

        @csrf
        @method('put')

        <x-form.input col="col-12" label=" {{ trans('words.warehouse_name') }}" name="name" required
            value="{{ $warehouse->name }}">
        </x-form.input>



        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>

    </form>


    <div class="actions border-0 mt-3">
        <x-admin.layout.back title="{{ trans('words.المنتجات') }}"></x-admin.layout.back>
        <x-cr_button title="{{ trans('words.إضافة منتج') }}"></x-cr_button>

    </div>

    <div class="tableSpace">
        <table id="sortable-table">
            <thead>

                <tr>
                    <th> {{ trans('words.صورة المنتج') }}</th>
                    <th>{{ trans('words.اسم المنتج') }}</th>
                    <th> {{ trans('words.الكمية') }}</th>
                    <th> {{ trans('words.actions') }}</th>
                </tr>

            </thead>

            <tbody>


                @forelse ($warehouseProducts as $warehouseProduct)
                    <tr>

                        @php

                            $product = $warehouseProduct->product;
                            if (isset($product->img)) {
                                $img = str_replace('public', 'storage', $product->img);
                                $img = asset("$img");
                            } else {
                                $img = 'https://placehold.co/600x400?text=product+img';
                            }

                        @endphp

                        <td><img width="80" height="60" style="object-fit: contain" src="{{ $img }}"></td>


                        <td data-text='اسم المنتج'>
                            {{ $product->name }}
                        </td>

                        <td>{{ $warehouseProduct->variants->sum('stock') }}</td>








                        <x-td_end normal id="{{ $product->id }}" name="{{ $product->name }}">
                            <button class="es-btn-primary btn-show-stock" style="height: 32px;"
                                data-id="{{ $warehouseProduct->id }}" data-variants='@json($warehouseProduct->variants)'>
                                عرض الاستوك
                            </button>
                        </x-td_end>

                    </tr>

                @empty
                    <tr>

                        <td colspan="4">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>

    </div>



    <x-form.create_model img id="createModel" title="{{ trans('words.إضافة منتج') }}" path="admin/warehouses/products">
        <div class="products">

            <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">

            @foreach ($products as $product)
                <div class="product">
                    <img src="{{ path("$product->img") }}">

                    <div class="product_body">
                        <div class="product_name">
                            <p>{{ $product->name }}</p>
                        </div>
                    </div>

                    <input type="checkbox" name="products[]" value="{{ $product->id }}" hidden>

                </div>
            @endforeach

        </div>
    </x-form.create_model>


    <x-form.delete title="{{ trans('words.المنتج') }}"
        path="admin/warehouses/{{ $warehouse->id }}/products/destroy"></x-form.delete>
@endsection


@section('js')
    <script>
        $('aside .warehouses').addClass('active');

        $('.modelSelect').select2();


        document.querySelectorAll('.product').forEach(product => {
            product.addEventListener('click', function() {
                const checkbox = this.querySelector('input[type="checkbox"]');

                // Toggle selected class and checkbox status
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    checkbox.checked = false;
                } else {
                    this.classList.add('selected');
                    checkbox.checked = true;
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('.btn-show-stock').forEach(button => {
            button.addEventListener('click', function() {
                // جمع بيانات الـ variants من الزر
                const variants = JSON.parse(this.getAttribute('data-variants'));

                // إعداد HTML لعرض المخزون
                let stockHtml =
                    '<table class="table"><thead><tr><th>المقاس</th><th>المخزون</th></tr></thead><tbody>';
                variants.forEach(item => {
                    stockHtml += `<tr><td>${item.variant.value}</td><td>${item.stock}</td></tr>`;
                });
                stockHtml += '</tbody></table>';

                // فتح نافذة SweetAlert2
                Swal.fire({
                    title: 'مخزون المنتج',
                    html: stockHtml,
                    showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'إغلاق',
                    confirmButtonAriaLabel: 'إغلاق',
                });
            });
        });
    </script>
@endsection
