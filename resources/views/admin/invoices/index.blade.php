@extends('admin.layout')

@section('title')
    <title>الفواتير</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="الفواتير"></x-admin.layout.back>

        <div class="d-flex gap-2">
            <x-search_button></x-search_button>

            @if (auth()->user()->role == 'admin')
                <x-cr_button title="إضافة فاتورة"></x-cr_button>
            @endif
        </div>
    </div>

    <div class="tableSpace">
        <table class="mb-3">

            <thead>
                <tr>
                    <th> اسم الفاتورة</th>

                    @if (auth()->user()->role == 'admin')
                        <th> المخزن</th>
                    @endif

                    <th>نوع الفاتورة</th>
                    <th>عدد القطع</th>
                    <th> تاريخ التسجيل</th>
                </tr>
            </thead>

            <tbody class="clickable">

                @forelse ($invoices as $invoice)
                    <tr>

                        <td>{{ $invoice->InvoiceName }}</td>

                        @if (auth()->user()->role == 'admin')
                            <td>{{ $invoice->warehouse->name ?? "المخزن الرئيسي" }}</td>
                        @endif


                        <td>{{ $invoice->type }}</td>


                        <td>{{ $invoice->items->sum('qnt') }}</td>

                        <td>{{ $invoice->date->format('Y-m-d') }}</td>



                        <x-td_end id="{{ $invoice->id }}" name="{{ $invoice->InvoiceName }}">
                            <x-form.link style="height: 32px" target="_self" title="عرض الحقول"
                                path="{{ auth()->user()->role }}/invoices/{{ $invoice->id }}/edit"></x-form.link>
                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5">لا يوجد فواتير</td>
                    </tr>
                @endforelse


            </tbody>
        </table>


    </div>


    <x-form.create_model id="createModel" title="اضافة فاتورة" path="admin/invoices">
        <div class="row g-2">

            <x-form.input reqiured label="اسم الفاتورة" name="InvoiceName"
                placeHolder="مثال : فاتورة استرجاع 10 قطع"></x-form.input>

            @if (auth()->user()->role == 'admin')
                <x-form.select  name="warehouseId" label="المخزن">

                    <option value="">المخزن الرئيسي</option>

                    @forelse ($warehouses as $warehouse)



                        <option @selected(old('warehouseId') == $warehouse->id) value="{{ $warehouse->id }}">{{ $warehouse->mobile }}
                            {{ $warehouse->name }}
                        </option>
                    @empty
                        <option disabled>لا يوجد تجار مضافة</option>
                    @endforelse
                </x-form.select>
            @endif


            <x-form.select reqiured name="type" label="نوع الفاتورة">
                <option @selected(old('type') == 'مشتريات') value="مشتريات">مشتريات</option>
                <option @selected(old('type') == 'مرتجعات') value="مرتجعات">مرتجعات</option>
            </x-form.select>

            <x-form.input label="تاريخ الفاتورة" name="date" class="date"></x-form.input>
        </div>
    </x-form.create_model>

    <x-form.delete title="الفاتورة" path="admin/invoices/destroy">
        <x-form.select col="col-12" name="refreshStock" label="اعادة ضبط الاستوك">
            <option value="yes">نعم</option>
            <option value="no">لا</option>
        </x-form.select>
    </x-form.delete>

    <x-form.search_model path="{{ auth()->user()->role }}/invoices/search">

        <x-form.input value="{{ $_GET['InvoiceName'] ?? '' }}" type="search" label="اسم الفاتورة"
            name="InvoiceName"></x-form.input>

        @if (auth()->user()->role == 'admin')
            <x-form.select label="المخزن" name="warehouse_Id">

                <option value="">كل المخازن</option>

                @if (auth()->user()->role == 'admin')
                    @foreach ($warehouses as $warehouse)
                        <option @selected(isset($_GET['warehouse_Id']) && $warehouse->id == $_GET['warehouse_Id']) value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                @endif

            </x-form.select>
        @endif



        <x-form.select label="نوع الفاتورة" name="invoice_type">
            <option value="">كل الفواتير</option>
            <option @selected(isset($_GET['invoice_type']) && $_GET['invoice_type'] == 'مشتريات') value="مشتريات">مشتريات</option>
            <option @selected(isset($_GET['invoice_type']) && $_GET['invoice_type'] == 'مرتجعات') value="مرتجعات">مرتجعات</option>
        </x-form.select>


        <x-form.input value="{{ $_GET['searchDate'] ?? '' }}" label="تاريخ الفاتورة" name="searchDate" class="searchDate"
            dir="ltr"></x-form.input>




    </x-form.search_model>
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
        $('aside .invoices').addClass('active');

        flatpickr('input.date', {
            enableTime: false,
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                checkUseDate();
            }
        });

        flatpickr('input.searchDate', {
            enableTime: false,
            mode: 'range',
            dateFormat: "Y-m-d",
        });

        document.querySelector('input.date').classList.add('checkThis');

        $('#createModel .modelSelect').select2({
            dropdownParent: $('#createModel .modal-content')
        });
        $('#deleteModel .modelSelect').select2({
            dropdownParent: $('#deleteModel .modal-content')
        })
        $('#searchModel .modelSelect').select2({
            dropdownParent: $('#searchModel .modal-content')
        })
    </script>
@endsection
