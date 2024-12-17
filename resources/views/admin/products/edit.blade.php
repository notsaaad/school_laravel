@extends('admin.layout')


@section('title')
    <title> {{ trans('words.btn_update') }} {{ $product->name }}</title>
@endsection




@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/products"
            title=" {{ trans('words.btn_update') }} {{ $product->name }}"></x-admin.layout.back>
    </div>


    <form class="row form_style" action="/admin/products/{{ $product->id }}" method="post" enctype="multipart/form-data">


        {{-- <div>
            <img style="width: 80px ;margin-bottom: 20px ;height: 50px;object-fit: contain" src="{{ path($product->img) }}" alt="{{ $product->name }}">
        </div> --}}
        @csrf
        @method('put')


        <x-form.input col="col-lg-3 col-6" required label="{{ trans('words.name') }}" name="name"
            value="{{ $product->name }}"></x-form.input>

        <x-form.input col="col-lg-3 col-6" accept="image/*" class="form-control" type="file"
            label="{{ trans('words.صورة المنتج') }}" name="img">
        </x-form.input>



        <x-form.select col="col-lg-3 col-6" reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

            @foreach ($stages as $stage)
                <option @selected($stage->id == $product->stage_id) value="{{ $stage->id }}">{{ $stage->name }}</option>
            @endforeach


        </x-form.select>



        <x-form.select col="col-lg-3 col-6" required name="gender" label="{{ trans('words.النوع') }}">

            <option @selected($product->gender == 'boy') value="boy">{{ trans('words.ذكر') }}</option>
            <option @selected($product->gender == 'girl') value="girl">{{ trans('words.انثي') }}</option>
            <option @selected($product->gender == 'both') value="both">{{ trans('words.ذكر او انثي') }}</option>

        </x-form.select>


        <x-form.input col="col-lg-3 col-6" value="{{ $product->price }}" required Type="number"
            label="{{ trans('words.سعر المنتج') }}" name="price">
        </x-form.input>

        <x-form.input col="col-lg-3 col-6" value="{{ $product->sell_price }}" required Type="number"
            label="{{ trans('words.سعر البيع') }}" name="sell_price">
        </x-form.input>


        <x-form.input col="col-lg-3 col-6" value="{{ $product->stock }}" required min="0" type="number"
            label="{{ trans('words.الكمية') }}" name="stock">
        </x-form.input>



        <div class="col-lg-3 col-6 group ">
            <label for="show" class="mb-2"> {{ trans('words.حالة المنتج') }}<span class="text-danger">*</span>
            </label>
            <select name="show" class="modelSelect">
                <option @selected($product->show == '1') value="1">نشط</option>
                <option @selected($product->show == '0') value="0">غير نشط</option>
            </select>
        </div>





        <div class="col-6 mt-3">
            <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>



        </div>
    </form>

    <div class="actions border-0  mt-3">
        <x-admin.layout.back title="{{ trans('words.مقاسات المنتج') }}"></x-admin.layout.back>
        <x-cr_button title="{{ trans('words.إضافة مقاس') }}"></x-cr_button>

    </div>

    <div class="tableSpace">
        <table id="sortable-table" class="mb-3">

            <tbody class="clickable">

                @forelse ($product->variants as $variant)
                    <tr>

                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $variant->value }}
                        </td>

                        <td>
                            {{ $variant->stock }}
                        </td>


                        <input type="hidden" value="{{ $variant->id }}" class="ids">


                        <x-td_end id="{{ $variant->id }}" name="{{ $variant->value }}">


                            <div onclick='qr({{ $variant->id }})' data-tippy-content="طباعة الباركود"
                                class="square-btn ltr has-tip ">
                                <i class="fa-solid fa-qrcode mr-2  icon fa-fw" aria-hidden="true"></i>
                            </div>



                            <div type="button" data-id="{{ $variant->id }}" data-name="{{ $variant->value }}"
                                data-stock="{{ $variant->stock }}" onclick="show_new_value_model(this)"
                                data-bs-toggle="modal" data-bs-target="#exampleModal2"
                                data-tippy-content="{{ trans('words.btn_update') }}" class="square-btn ltr has-tip">
                                <i class="far fa-edit mr-2 icon" aria-hidden="true"></i>
                            </div>


                        </x-td_end>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>


        @if ($product->variants->isNotEmpty())
            <x-form.button onclick="UpdateOrder()" title="{{ trans('words.change') }}"></x-form.button>
        @endif

    </div>

    <x-form.delete title="{{ trans('words.المقاس') }}" path="admin/products/variants/destroy"></x-form.delete>



    <form method="post" action="{{ url('admin/products/variants/edit') }}" class="modal fade" id="exampleModal2"
        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        @csrf
        @method('put')
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ trans('words.btn_update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <input type="hidden" name="value_id">

                    <x-form.input name="new_value_input" label="{{ trans('words.المقاس') }}"></x-form.input>
                    <x-form.input name="new_stock_input" label="{{ trans('words.الكمية') }}"></x-form.input>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary"> {{ trans('words.btn_update') }} </button>

                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('words.close') }}</button>
                </div>
            </div>
        </div>
    </form>


    <x-form.create_model id="createModel" title="{{ trans('words.إضافة مقاس') }}"
        path="admin/products/{{ $product->id }}/variants">
        <div class="row g-2">

            <div id="variantContainer">
                <div class="variant-row" id="variantRow1">
                    <input type="text" name="variants[1][size]" placeholder="المقاس" required>
                    <input type="number" name="variants[1][quantity]" placeholder="الكمية" required>
                    <button type="button" class="removeVariant es-btn-primary delete" data-id="1">إزالة</button>
                </div>
            </div>

            <button type="button" id="addVariant" class="es-btn-primary">إضافة حقل</button>

        </div>
    </x-form.create_model>
@endsection


@section('js')
    <x-move model="products/variants"></x-move>


    <script>
        $('aside .products').addClass('active');
        $('.modelSelect').select2();


        function show_new_value_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_stock = element.getAttribute('data-stock')
            let data_id = element.getAttribute('data-id')

            $("#new_value_input").val(data_name)
            $("#new_stock_input").val(data_stock)
            $("input[name='value_id']").val(data_id)
        }
    </script>

    <script>
        $(document).ready(function() {
            let counter = 1;

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
                url: `/admin/products/${id}/qrVairant`,
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
                    <div class="name" style="font-size: 6px">${response.name}</div>
                </div>

                <div style="font-family: cairo ; margin-top:6px;" >
                    ${response.qr}
                </div>
                <div style="margin-top: 0px">
                    <div class="name" style="font-size: 6px">${response.name}</div>
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
