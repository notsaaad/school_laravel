@extends('admin.layout')

@section('title')
    <title>{{ $invoice->InvoiceName }}</title>
@endsection


@section('css')
    <style>
        #s2 {
            display: none;
        }

        .total {
            background-color: var(--mainBg) !important;
            color: white !important;
            text-align: center;
            padding: 10px 5px !important;
        }
    </style>
@endsection

@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="{{ auth()->user()->role . '/invoices' }}"
            title="{{ $invoice->InvoiceName . ' [ ' . ($invoice->warehouse->name ?? 'المخزن الرئيسي') . ' ]' }}">
        </x-admin.layout.back>


        @if (auth()->user()->role == 'admin' && is_null(optional($invoice->warehouse)->deleted_at))
            <div class="d-flex gap-2">
                <x-cr_button title="إضافة حقل جديد"></x-cr_button>
            </div>
        @endif
    </div>

    <div class="tableSpace">
        <table class="mb-3">

            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الخصائص</th>
                    <th> الكمية</th>
                    <th> السعر</th>
                    <th> الاجمالي</th>
                </tr>
            </thead>

            <tbody class="clickable">

                @php
                    $qnt = 0;
                    $total = 0;
                @endphp

                @forelse ($invoice->items as $item)
                    <tr>

                        <td>{{ $item->product->name }}</td>


                        <td>{{ $item->variant->value }}</td>

                        <td>{{ $item->qnt }}</td>

                        <td>{{ $item->price }}</td>
                        <td>{{ $item->qnt * $item->price }}</td>

                        @php
                            $name = $item->product->name . ' ' . $item->variant->value;
                        @endphp

                        @if (auth()->user()->role == 'admin' && is_null((optional($invoice->warehouse)->deleted_at)))
                            <x-td_end id="{{ $item->id }}" name="{{ $name }}">

                            </x-td_end>
                        @endif


                    </tr>


                    @php
                        $qnt += $item->qnt;
                        $total += $item->price * $item->qnt;
                    @endphp

                @empty
                    <tr>
                        <td colspan="6">لا يوجد فواتير</td>
                    </tr>
                @endforelse


            </tbody>

            <tfoot>
                <tr>
                    <td colspan="1" class="total" style="border-radius: 5px">الاجمالي</td>
                    <td></td>
                    <td class="total"
                        style=" border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important">
                        {{ $qnt }}</td>
                    <td></td>
                    <td class="total"
                        style=" border-top-left-radius: 5px !important;border-bottom-left-radius: 5px !important">
                        {{ $total }}</td>
                    @if (auth()->user()->role == 'admin')
                        <td></td>
                    @endif
                </tr>
            </tfoot>


        </table>


    </div>


    @if (auth()->user()->role == 'admin')
        <x-form.create_model id="createModel" title="اضافة حقل" path="admin/invoices/{{ $invoice->id }}/storeItem">
            <div class="row g-2">
                <x-form.select reqiured name="product_id" label="المنتج">
                    <option disabled selected value="">اختار المنتج من هنا</option>



                    @foreach ($products as $product)
                        @if ($invoice->warehouseId != null)
                            @php
                                $product = $product->product;
                            @endphp
                        @endif

                        <option data-price="{{ $product->price }}" value="{{ $product->id }}">{{ $product->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.input reqiured col="col-lg-3 col-6" label="السعر" name="price" type="number"
                    min="0"></x-form.input>

                <x-form.input reqiured col="col-lg-3 col-6" label="الكمية" name="stock" type="number"
                    min="1"></x-form.input>

                <div class="mb-5" id="hide">
                    <x-form.select2 col="col-12" name="vairants" label="خصائص المنتج">

                    </x-form.select2>
                </div>


            </div>
        </x-form.create_model>


        <x-form.delete title="الحقل" path="admin/invoices/items/destroy">
            <x-form.select col="col-12" name="refreshStock" label="اعادة ضبط الاستوك">
                <option value="yes">نعم</option>
                <option value="no">لا</option>
            </x-form.select>
        </x-form.delete>
    @endif
@endsection

@section('js')
    @if ($errors->any() || session('error'))
        <script>
            $(document).ready(function() {
                $('#createModel').modal('show');
            });
        </script>
    @endif

    <script>
        $('aside .invoices').addClass('active');

        $("#hide").hide();

        $('#createModel .modelSelect').select2({
            dropdownParent: $('#createModel .modal-content')
        }).on('select2:select', function(e) {
            checkAllForms()
        });


        $('#deleteModel .modelSelect').select2({
            dropdownParent: $('#deleteModel .modal-content')
        })

        let select = $(".select-dropdown").selectize({

            delimiter: ',',
            diacritics: true,
            plugins: ["clear_button", "drag_drop", "remove_button"],
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            searchConjunction: "and",
            persist: true,
            maxItems: null,
            placeholder: 'اختار خصائص المنتج',
            onChange: function() {
                checkAllForms()
            },
            score: function(search) {
                var score = this.getScoreFunction(search);
                return function(item) {
                    return item.title
                        .toLowerCase()
                        .includes(search.toLowerCase()) ? 1 : 0;
                };
            }

        });


        $('select[name=product_id]').on('select2:select', function(e) {

            var selectedOption = e.params.data;
            var price = selectedOption.element.dataset.price;

            $('input[name=price]').val(price)


            let product_id = e.params.data.id;

            $.ajax({
                url: '/admin/products/ajaxVariant',
                type: 'GET',
                dataType: 'json',
                data: {
                    query: product_id
                },
                beforeSend() {
                    $("#model_loader").css("display", "flex");
                },
                success: function(response) {

                    var control = select[0].selectize;
                    control.clearOptions();
                    control.clearOptions();

                    if (response.status == "success") {
                        for (const variant of response.variant) {

                            control.addOption({
                                id: variant.id,
                                title: variant.value
                            });
                        }
                        $("#hide").show();

                    } else {
                        $("#hide").hide();
                    }
                    $("#model_loader").css("display", "none");


                },
                error: function() {
                    control.clearOptions();
                    control.clearOptions();
                    $("#model_loader").css("display", "none");
                }
            });


            checkAllForms()
        });
    </script>
@endsection
