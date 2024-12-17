@extends('admin.layout')

@section('title')
    <title>الفواتير</title>
@endsection


@section('content')

    <div class="content">

        <div class="actions border-0">

            <x-layout.back title="الفواتير"></x-layout.back>

            <div class="d-flex gap-2">
                <x-cr_button title="إضافة فاتورة"></x-cr_button>
            </div>
        </div>

        <div class="tableSpace">
            <table class="mb-3">

                <tbody class="clickable">

                @forelse ($invoices as $invoice)
                    <tr>

                        <x-td_end id="{{ $invoice->id }}" name="{{ $invoice->InvoiceName }}">
                            <x-form.link style="height: 32px" target="_self" title="تعديل"
                                         path="admin/invoices/{{ $invoice->id }}/edit"></x-form.link>
                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="2">لا يوجد فواتير</td>
                    </tr>
                @endforelse


                </tbody>
            </table>


        </div>
    </div>


    <x-form.create_model id="createModel" title="اضافة فاتورة" path="admin/invoices">
        <div class="row g-2">
            <x-form.input reqiured label="اسم التصنيف" name="name" placeHolder="مثال : ملابس  "></x-form.input>
        </div>
    </x-form.create_model>

    <x-form.delete title="الفاتورة" path="admin/invoices/destroy"></x-form.delete>

@endsection

@section('js')

    @if ($errors->any())
        <script>
            $(document).ready(function () {
                $('#createModel').modal('show');
            });
        </script>
    @endif

    <script>
        $('aside .invoices').addClass('active');
    </script>

@endsection


