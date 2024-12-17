@extends('admin.layout')


@section('title')
    <title>
        {{ trans('words.btn_update') }} {{ $user->name }}</title>
@endsection

@section('css')
    <style>
        .Delivered {
            background: rgb(11, 196, 117);
            color: white;
            padding: 3px 12px;
            border-radius: 3px;
            font-weight: 700;
            font-size: 10px;
        }

        .tryAgain {
            background-color: rgb(253, 240, 227);
            color: rgb(128, 63, 0) !important;
            padding: 3px 12px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
        }
    </style>
@endsection






@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/students"
            title=" {{ trans('words.المصاريف') }} {{ $user->name }}  ( {{ $user->code }} )"></x-admin.layout.back>
    </div>


    <x-user :user="$user"></x-user>


    <table>
        <thead>
            <tr>
                <th>{{ __('words.العنوان') }}</th>
                {{-- <th>{{ __('words.السعر كامل') }}</th>
                <th>{{ __('words.سعر البنود') }}</th>
                <th>{{ __('words.عدد البنود') }}</th>
                <th>{{ __('words.المبلغ المدفوع') }}</th>
                <th>{{ __('words.المبلغ المتبقي') }}</th> --}}
                <th>{{ __('words.تاريخ الانشاء') }}</th>
                <th>{{ __('words.تاريخ الانتهاء') }}</th>

            </tr>
        </thead>

        <tbody>
            @foreach ($user->All_fees()->get() as $fee)
                @php
                    $totalPrice = $fee->items()->sum('price');
                    $paidAmount = $fee->payments()->sum('amount');
                    $remainingAmount = $totalPrice - $paidAmount;
                @endphp
                <tr>
                    <td><a style="font-weight: 700 !important"
                            href="/admin/students/{{ $user->code }}/fees/{{ $fee->id }}">{{ $fee->name }}</a>
                    </td>
                    {{-- <td data-title="Full">{{ $fee->price . ' ' . trans('words.ج') }}</td> --}}
                    {{-- <td data-title="Items_Price">{{ $totalPrice . ' ' . trans('words.ج') }}</td>
                    <td data-title="Items_count">{{ $fee->items()->count() }}</td>
                    <td data-title="Paid Amount"><span class="Delivered">{{ $paidAmount . ' ' . trans('words.ج') }}</span>
                    </td>
                    <td data-title="Remaining"><span
                            class="tryAgain">{{ $remainingAmount . ' ' . trans('words.ج') }}</span>
                    </td> --}}

                    <td>{{ fixdate($fee->created_at) }}</td>
                    <td>{{ fixdate($fee->end_at) }}</td>


                </tr>
            @endforeach

        </tbody>
    </table>
@endsection


@section('js')
    <script>
        $('aside .students').addClass('active');
        $('.modelSelect').select2();
    </script>
@endsection
