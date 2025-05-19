@extends('admin.layout')


@section('title')
    <title> مشاهدة التحويلات</title>
@endsection


@section('css')

@endsection




@section('content')
        <x-admin.layout.back back="admin/warehouses/{{$warehouse->id}}/edit"
            title=" العودة الي {{ $warehouse->name }}"></x-admin.layout.back>


  @if ($transfers->isEmpty())
  <p class="mt-5">لا توجد تحويلات حتى الآن.</p>
  @else
      <div class="tableSpace mt-5">
      <table>
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المنتج</th>
                    <th>المقاس</th>
                    <th>الكمية</th>
                    <th>من مخزن</th>
                    <th>إلى مخزن</th>
                    <th>نوع التحويل</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfers as $transfer)
                    <tr @if($transfer->from_warehouse_id == $warehouse->id) style="background:#fff7f7;" @else style="background:#f7fff7;" @endif>
                        <td>{{ $transfer->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $transfer->product->name ?? '-' }}</td>
                        <td>{{ $transfer->variant->value ?? '-' }}</td>
                        <td>{{ $transfer->quantity }}</td>
                        <td>{{ $transfer->fromWarehouse->name }}</td>
                        <td>{{ $transfer->toWarehouse->name }}</td>
                        <td>
                            @if($transfer->from_warehouse_id == $warehouse->id)
                                <span class="text-danger">صادر</span>
                            @else
                                <span class="text-success">وارد</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  @endif

@endsection


@section('js')


@endsection
