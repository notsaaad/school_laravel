@extends('admin.layout')

@section('title')
    <title>منتجات ليس لها قطع</title>
@endsection


@section('css')
  <style>
    table img{
        object-fit: cover;
    }
  </style>
@endsection

@section('content')




    <div class="tableSpace">
        <h2 class="mb-4">منتجات ليس لديها مخزون</h2>
    </div>



    @if($variants->isEmpty())
        <div class="alert alert-success">لا توجد منتجات ناقصة المخزون حالياً.</div>
    @else
        <div class="table-responsive">
            <table class="text-center">
                <thead>
                    <tr>
                      <th>الصورة</th>
                        <th>الاسم</th>
                        <th>SKU</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>الجنس</th>
                        <th>المخازن</th>
                        <th>عدد الكميات في الطلبات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variants as $product)
                        <tr>
                          @php
                            if (isset($product->product->img)) {
                                $img = str_replace('public', 'storage', $product->product->img);
                                $img = asset("$img");
                            } else {
                                $img = 'https://placehold.co/600x400?text=product+img';
                            }
                          @endphp
                          <td><img src="{{ $img }}" width="60"></td>
                            <td>{{ $product->product->name }} [{{$product->value}}]</td>
                            {{-- <td>{{ $product->sku }}</td> --}}
                            {{-- <td><span onclick="copy('{{$product->sku }}')">{{$product->sku}}</span></td> --}}
                            <td><x-copy text="{{ $product->sku }}"></x-copy> {{$product->sku}}</td>
                            <td>{{ $product->product->price }}</td>
                            <td>{{ $product->product->sell_price }}</td>
                            <td>{{ $product->product->gender }}</td>
                            <td>
                                @foreach($product->warehouses as $warehouse)
                                    <a href="{{ route('admin.warehouse.edit', $warehouse->id) }}" class="badge bg-primary text-white p-2" style="text-decoration: none; font-size:14px">
                                        {{ $warehouse->name }}
                                    </a>
                                @endforeach
                            </td>
                            <td class="@if($product->total_orders > 0) text-light fw-bold bg-danger @endif">{{$product->total_orders}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @endsection
@section('js')

@endsection
