@extends('admin.layout')

@section('title')
    <title>{{ trans('words.التحويلات') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.التحويلات') }}"></x-admin.layout.back>

        @can('has', 'actions_transfers')
            <div class="d-flex gap-2">
                <x-cr_button title="{{ trans('words.عمل تحويل') }}"></x-cr_button>
            </div>
        @endcan
    </div>


    <div class="tableSpace">
        <table class="mb-3">

            <thead>
                <tr>
                    <th>{{ trans('words.كود التحويل') }}</th>
                    <th>{{ trans('words.عنوان التحويل') }}</th>
                    <th>{{ trans('words.العمولة المستحقة') }}</th>
                    <th>{{ trans('words.الحالة') }}</th>
                    <th>{{ trans('words.بواسطة') }}</th>
                    <th>{{ trans('words.تاريخ الانشاء') }}</th>
                    <th>{{ trans('words.تم الدفع بواسطة') }}</th>
                    <th>{{ trans('words.تاريخ الدفع') }}</th>

                    <th>{{ trans('words.المستلم') }}</th>
                    <th>{{ trans('words.تاريخ الاستلام') }}</th>


                    @can('has', 'actions_transfers')
                        <th>{{ trans('words.actions') }}</th>
                    @endcan
                </tr>
            </thead>

            <tbody>

                @forelse ($all as $transfer)
                    <tr>
                        <td>{{ $transfer->code }}</td>

                        <td>{{ $transfer->name }}</td>

                        @php
                            $totalFees = $transfer->orders->sum(function ($order) {
                                return $order->fees();
                            });
                        @endphp

                        <td>{{ $totalFees }} {{ trans('words.ج') }}</td>

                        @if ($transfer->type == 'paid')
                            <td><span class="Delivered">{{ trans('words.مدفوع') }}</span></td>
                        @elseif($transfer->type == 'pending')
                            <td><span class="canceled"> {{ trans('words.غير مدفوع') }}</span></td>
                        @else
                            <td><span class="Delivered"> {{ trans('words.مكتمل') }}</span></td>
                        @endif




                        <td>{{ $transfer->enteredByUser->name ?? '' }}</td>
                        <td>{{ fixdate($transfer->created_at) }}</td>

                        <td>{{ $transfer->paidByUser?->name }}</td>


                        <td>{{ fixdate($transfer->paid_at) }}</td>


                        <td>{{ $transfer->confirmByUser?->name }}</td>


                        <td>{{ fixdate($transfer->confirmed_at) }}</td>



                        @can('has', 'actions_transfers')
                            <x-td_end normal id="{{ $transfer->id }}" name="{{ $transfer->name ?? $transfer->code }}">

                                <x-form.link style="height: 32px;" path="admin/transfers/{{ $transfer->code }}"
                                    title="عرض"></x-form.link>


                                <x-update_button data-id="{{ $transfer->id }}"
                                    data-name="{{ $transfer->name }}"></x-update_button>

                            </x-td_end>
                        @endcan

                    </tr>

                @empty
                    <tr>
                        <td colspan="11"> {{ trans('words.no_data') }} </td>
                    </tr>
                @endforelse


            </tbody>
        </table>



    </div>


    <x-form.create_model id="createModel" title="{{ trans('words.عمل تحويل') }}" path="admin/transfers">
        <div class="row g-2">
            <x-form.input col="col-12" label="{{ trans('words.عنوان التحويل') }}" name="name">
            </x-form.input>

        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.التحويل') }}" path="admin/transfers/destroy"></x-form.delete>

    <x-form.update_model path="admin/transfers">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">
            <x-form.input id="nameInput" col="col-12" label="{{ trans('words.عنوان التحويل') }}" name="nameInput">
            </x-form.input>
        </div>
    </x-form.update_model>
@endsection


@section('js')

    @if ($errors->any())
        @if ($errors->has('idInput'))
            <script>
                $(document).ready(function() {
                    $('#updateModel').modal('show');
                });
            </script>
        @else
            <script>
                $(document).ready(function() {
                    $('#createModel').modal('show');
                });
            </script>
        @endif
    @endif


    <script>
        $('aside .transfers').addClass('active');

        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));
            $("#nameInput").val(e.getAttribute('data-name'));
            checkAllForms();
            $('#updateModel').modal('show');

        }
    </script>

@endsection
