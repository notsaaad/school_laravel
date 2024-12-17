@extends('admin.layout')

@section('title')
    <title>{{ trans('words.المنتجات') }}</title>
@endsection


@section('content')


    <div class="actions">

        <x-admin.layout.back title="{{ trans('words.المنتجات') }}"></x-admin.layout.back>
        @can('has', 'products_action')
            <div class="d-flex gap-2 flex-wrap">

                <x-search_button></x-search_button>

                <x-excel_button></x-excel_button>

                <x-upload_button></x-upload_button>


                <x-cr_button title="{{ trans('words.إضافة منتج') }}"></x-cr_button>
            </div>
        @endcan

    </div>

    <div class="tableSpace">
        <table id="sortable-table">
            <thead>

                <tr>
                    <th> {{ trans('words.صورة المنتج') }}</th>
                    <th>{{ trans('words.اسم المنتج') }}</th>
                    <th>{{ trans('words.المرحلة') }}</th>
                    <th>{{ trans('words.النوع') }}</th>
                    <th>{{ trans('words.سعر المنتج') }} </th>
                    <th> {{ trans('words.سعر البيع') }}</th>
                    <th> {{ trans('words.مخزن رئيسي') }}</th>
                    <th>{{ trans('words.مخازن فرعية') }}</th>
                    @can('has', 'products_action')
                        <th> {{ trans('words.حالة المنتج') }}</th>
                        <th> {{ trans('words.actions') }}</th>
                    @endcan
                </tr>

            </thead>

            <tbody>


                @forelse ($products as $product)
                    <tr>



                        @php
                            if (isset($product->img)) {
                                $img = str_replace('public', 'storage', $product->img);
                                $img = asset("$img");
                            } else {
                                $img = 'https://placehold.co/600x400?text=product+img';
                            }

                        @endphp

                        <td><img width="80" height="60" style="object-fit: contain" src="{{ $img }}"></td>


                        <td data-text='اسم المنتج'>
                            @can('has', 'products_action')
                                <a class="product_link"
                                    href="/admin/products/{{ $product->id }}/edit">{{ $product->name }}</a>
                            @else
                                {{ $product->name }}
                            @endcan


                        </td>

                        <td>{{ $product->stage->name }}</td>


                        @if ($product->gender == 'boy')
                            <td>{{ trans('words.ذكر') }}</td>
                        @elseif ($product->gender == 'girl')
                            <td>{{ trans('words.انثي') }}</td>
                        @else
                            <td>{{ trans('words.ذكر او انثي') }}</td>
                        @endif


                        <td> {{ $product->price }} </td>

                        <td> {{ $product->sell_price }} </td>

                        <td> {{ $product->stock }} </td>

                        <td>{{ get_warehouses_product_stock($product) }}</td>


                        @can('has', 'products_action')
                            <td data-text='حالة المنتج '>
                                <div class="form-check form-switch">
                                    <input value="{{ $product->id }}" @checked($product->show)
                                        onchange="showHideProduct(this)" class="form-check-input" type="checkbox"
                                        id="flexSwitchCheckChecked">
                                </div>
                            </td>



                            @if (!$product->deleted_at)
                                <x-td_end normal id="{{ $product->id }}" name="{{ $product->name }}">



                                    <x-form.link style="height: 32px" target="_self" title="{{ trans('words.btn_update') }}"
                                        path="admin/products/{{ $product->id }}/edit"></x-form.link>

                                    {{-- <div onclick='qr({{ $product->id }})' data-tippy-content="طباعة الباركود"
                                        class="square-btn ltr has-tip ">
                                        <i class="fa-solid fa-qrcode mr-2  icon fa-fw" aria-hidden="true"></i>
                                    </div> --}}

                                </x-td_end>
                            @else
                                <td style="text-align: right">
                                    <div type="button" data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        onclick="show_restore_model(this)" data-tippy-content="استرجاع"
                                        class="square-btn ltr has-tip"><i style="color:#676565"
                                            class="fa-solid fa-trash-arrow-up mr-2 icon" aria-hidden="true"></i></div>
                                </td>
                            @endif
                        @endcan

                    </tr>

                @empty
                    <tr>

                        <td colspan="11">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>

    </div>

    <div class="pagnator">
        {{ $products->appends(request()->query())->links() }}
    </div>

    <x-form.search_model path="admin/products/search">

        <x-form.input value="{{ $_GET['name'] ?? '' }}" type="search" label="{{ trans('words.اسم المنتج') }}"
            name="name"></x-form.input>


        <div class="col-lg-6 col-12 group ">
            <label for="show" class="mb-2"> {{ trans('words.حالة المنتج') }}</label>
            <select name="show" class="modelSelect w-100 ">
                <option value="">{{ trans('words.كل الحالات') }}</option>
                <option @selected(isset($_GET['show']) && $_GET['show'] == '1') value="1">{{ trans('words.نشط') }}</option>
                <option @selected(isset($_GET['show']) && $_GET['show'] == '0') value="0">{{ trans('words.غير نشط') }} </option>
            </select>

        </div>

        <div class="col-lg-6 col-12 group ">
            <label for="gender" class="mb-2"> {{ trans('words.النوع') }}</label>
            <select name="gender" class="modelSelect w-100 ">
                <option @selected(isset($_GET['gender']) && $_GET['gender'] == 'both') value="">{{ trans('words.ذكر او انثي') }} </option>
                <option @selected(isset($_GET['gender']) && $_GET['gender'] == 'boy') value="boy">{{ trans('words.ذكر') }}</option>
                <option @selected(isset($_GET['gender']) && $_GET['gender'] == 'girl') value="girl">{{ trans('words.انثي') }} </option>
            </select>
        </div>


        <div class="col-lg-6 col-12 group ">
            <label for="stage_id" class="mb-2"> {{ trans('words.المرحلة') }}</label>
            <select name="stage_id" class="modelSelect w-100 ">
                <option value="">{{ trans('words.الكل') }}</option>
                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}" @selected(isset($_GET['stage_id']) && $_GET['stage_id'] == $stage->id)> {{ $stage->name }} </option>
                @endforeach
            </select>
        </div>


        <div data-title="محدوف" class="col-lg-6 col-12 group ">
            <label for="deleted" class="mb-2"> {{ trans('words.المنتجات المحذوفة') }}</label>
            <select name="deleted" class="modelSelect w-100 ">
                <option value=""> {{ trans('words.كل المنتجات') }} </option>
                <option @selected(isset($_GET['deleted']) && $_GET['deleted'] == 'yes') value="yes"> {{ trans('words.محذوف فقط') }}</option>
                <option @selected(isset($_GET['deleted']) && $_GET['deleted'] == 'no') value="no"> {{ trans('words.غير محذوف فقط') }} </option>
            </select>

        </div>



    </x-form.search_model>


    <x-form.upload_model path="admin/products/import">

        <x-form.input name="file" type="file" class="form-control" label="ملف الاكسيل" col="col-12"
            accept=".xlsx"></x-form.input>

        <a download class="es-btn-primary" href="/assets/excel/products.xlsx">تحميل نموذج</a>

    </x-form.upload_model>

    @can('has', 'products_action')
        <x-form.create_model img id="createModel" title="{{ trans('words.إضافة منتج') }}" path="admin/products">
            <div class="row g-2">



                <x-form.input required label="{{ trans('words.اسم المنتج') }}" name="name">
                </x-form.input>

                <x-form.input accept="image/*" class="form-control" type="file" label="{{ trans('words.صورة المنتج') }}"
                    name="img">
                </x-form.input>


                <x-form.select reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

                    @foreach ($stages as $stage)
                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                    @endforeach


                </x-form.select>


                <x-form.select reqiured name="gender" label="{{ trans('words.النوع') }}">

                    <option value="boy">{{ trans('words.ذكر') }}</option>
                    <option value="girl">{{ trans('words.انثي') }}</option>
                    <option value="both">{{ trans('words.ذكر او انثي') }}</option>

                </x-form.select>

                <x-form.input col="col-4" required Type="number" label="{{ trans('words.سعر المنتج') }}"
                    name="price">
                </x-form.input>

                <x-form.input col="col-4" required Type="number" label="{{ trans('words.سعر البيع') }}"
                    name="sell_price">
                </x-form.input>

                <x-form.input col="col-4" required min="0" type="number" label="{{ trans('words.الكمية') }}"
                    name="stock">
                </x-form.input>


                <div id="variantContainer">

                </div>

                <button type="button" id="addVariant" class="es-btn-primary">إضافة مقاس</button>

            </div>
        </x-form.create_model>

        <x-form.delete title="{{ trans('words.المنتج') }}" path="admin/products/destroy"></x-form.delete>
    @endcan

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
        $('aside .products').addClass('active');

        function show_restore_model(e) {

            let element = e;


            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            if (confirm("هل انت متاكد من استرجاع " + data_name)) {
                window.location.href = `/admin/products/restore/${data_id}`;
            }

        }


        function showHideProduct(e) {
            let id = e.value;

            let show = 0;

            if (e.checked) {
                show = 1;
            }

            $.ajax({
                url: '/admin/products/showHideProduct',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    id: id,
                    show: show
                }),
                success: function(response) {
                    if (response.status == "error") {
                        Swal.fire({
                            text: response.message,
                            icon: "error"
                        });
                        $(e).prop('checked', false);
                    } else {
                        toastr["success"]("تم بنجاح");
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Network response was not ok', textStatus, errorThrown);
                }
            });

        }
    </script>

    <script>
        $(document).ready(function() {

            $('#createModel .modelSelect').select2({
                dropdownParent: $('#createModel .modal-content')
            });

            $('#searchModel .modelSelect').select2({
                dropdownParent: $('#searchModel .modal-content')
            });


        });
    </script>

    <script>
        $(document).ready(function() {
            let counter = 0;

            $('#addVariant').click(function() {
                counter++;
                $('#variantContainer').append(`
                    <div class="variant-row" id="variantRow${counter}">
                        <input type="text" name="variants[${counter}][size]" placeholder="المقاس" required>
                        <input type="number" name="variants[${counter}][quantity]" placeholder="الكمية" required>
                        <button type="button" class="removeVariant es-btn-primary delete" data-id="${counter}">إزالة</button>
                    </div>
                `);
            });

            $('#variantContainer').on('click', '.removeVariant', function() {
                const id = $(this).data('id');
                $(`#variantRow${id}`).remove();
            });
        });
    </script>

    <script>
        function qr(id) {

            $.ajax({
                url: `/admin/products/${id}/qr`,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status == "success") {
                        let html = `
<section id="print" class="test text-center">
    <div style="font-family: cairo ; margin-top:1px;" >
        ${response.qr}
    </div>
    <div style="margin-top: 0px">
        <div class="name" style="font-size: 6px">${response.product.name}</div>
    </div>

    <div style="font-family: cairo ; margin-top:20px;" >
        ${response.qr}
    </div>
    <div style="margin-top: 0px">
        <div class="name" style="font-size: 6px">${response.product.name}</div>
    </div>


    <style>
        @page {
            size: 4in 2in;
            display: flex;
            align-items: center;
            justify-content: center;

        }

        @media print {
          body {
              padding:5px;
          }
      }


        #print   {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: 'Cairo', sans-serif;
        }
        .nameprint {
            font-family: 'Cairo', sans-serif;
        }
        #id {
            font-family: cairo;
            font-size: 13px;
        }
        #name {
            text-align: test;
        }
        .value {
            direction: rtl;
            -webkit-print-color-adjust: exact;
        }
    </style>

</section>
`;

                        Swal.fire({
                            title: "",
                            html: html,
                            focusConfirm: false,
                            showConfirmButton: true,
                            inputAutoFocus: false,
                            confirmButtonText: "طباعة",
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $('#print').printThis({

                                    importCSS: true,
                                    importStyle: true,
                                });
                            }
                        })
                    }
                },
            });

        }
    </script>
@endsection
