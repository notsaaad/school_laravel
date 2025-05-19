@extends('admin.layout')

@section('title')
    <title>نقل المنتجات</title>
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

        <x-admin.layout.back back="admin/warehouses/{{$warehouse->id}}/edit"
            title=" العودة الي {{ $warehouse->name }}"></x-admin.layout.back>


    @if($selectedWarehouseId)
        <form  class="row form_style mt-3" method="POST" action="{{ route('stock.transfer') }}">
            @csrf

            {{-- ثابت: المخزن اللي بتحوّل اليه --}}
            <input type="hidden" name="to_warehouse_id" value="{{ $selectedWarehouseId }}">

            {{-- متغير: المخزن الوجهة --}}
            <x-form.select name="from_warehouse_id" id="from_warehouse_id" col="col-lg-4 col-12" label="اختر المخزن الوجهة:">
                <option value="">اختر المخزن الوجهة</option>
                @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                @endforeach
            </x-form.select>

            {{-- المنتجات المتاحة في المخزن المصدر --}}
            <x-form.select name="variant_id" id="variant_id" col="col-lg-4 col-12" label="اختر المنتج:">
                <option value="">اختر المنتج</option>
            </x-form.select>

            <x-form.input col="col-lg-4 col-12" label="الكمية:" min="1" name="quantity"
                          value="1" title="ادخل الكمية" inputmode="numeric" type="number">
            </x-form.input>

            <div class="col-12 mt-3">
                <x-form.button id="submitBtn" title="تحويل"></x-form.button>
            </div>
        </form>
    @else
        <div class="alert alert-danger">
            لم يتم تحديد المخزن الحالي (المصدر). برجاء اختيار مخزن.
        </div>
    @endif
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
      const routeTemplate = "{{ route('admin.warehouses.availableProducts', ['id' => 'WAREHOUSE_ID']) }}";
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });

            $('#from_warehouse_id').on('change', function () {
                // let fromWarehouseId = "{{ $selectedWarehouseId }}";
                let fromWarehouseId = $('#from_warehouse_id').val();
                let variantSelect = $('#variant_id');

                variantSelect.empty().trigger('change');
                variantSelect.append(new Option('جاري التحميل...', '', true, true)).trigger('change');

                if (!fromWarehouseId) return;
                  let url = routeTemplate.replace('WAREHOUSE_ID', fromWarehouseId);
                  console.log(url);

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        variantSelect.empty();
                        variantSelect.append(new Option('اختر المنتج', '', true, false)).trigger('change');

                        data.forEach(item => {
                            let option = new Option(item.label, item.variant_id, false, false);
                            variantSelect.append(option);
                        });

                        variantSelect.trigger('change');
                    },
                    error: function () {
                        variantSelect.empty();
                        variantSelect.append(new Option('فشل تحميل المنتجات', '', true, true)).trigger('change');
                    }
                });
            });
        });
    </script>
@endsection
