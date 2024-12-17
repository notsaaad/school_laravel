@extends('admin.layout')



@section('css')
    <style>
        .fa-circle {
            font-size: 12px;
            margin-right: 5px;

        }

        .fa-circle.open {
            color: rgb(34 197 94);
        }

        .fa-circle.close {
            color: rgb(239 68 68 /1);
        }

        .select2-container {
            width: 100% !important;
        }

        .swal2-popup {
            width: 26em !important;
        }

        .swal2-title {
            font-size: 1.5em !important;
        }

        .swal2-styled {
            padding: 0.5em 1em !important;


        }
    </style>
    <style>
        .swal2-title {
            font-size: 1.5em !important;
            margin-bottom: 10px !important;
        }

        .swal2-input {
            margin: 15px auto !important;
            width: 90% !important;
        }

        .swal2-actions {
            margin-top: 0px !important;

            width: fit-content !important;
            border-color: var(--xbutton-border);
            background-color: var(--xbutton-background);
            border-radius: 0.25rem;
            border-bottom-width: 4px;
            padding: 0.5rem 1.2rem;
            font-weight: 700;
            color: rgb(255 255 255 / 1);
            font-family: Alexandria, sans-serif;
            font-size: 12px;
        }

        .swal2-validation-message {
            margin: 10px 0px !important;
        }
    </style>
@endsection


@section('title')
    <title>{{ trans('words.settings') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back :title="trans('words.settings')"></x-admin.layout.back>
    </div>

    <div class="row g-3 ">
        @can('has', 'branding')
            <div data-title="العلامة التجارية" class="col-lg-3 col-md-6 col-12">
                <x-tool title="{{ trans('words.brand') }}" dis="{{ trans('words.brand_dis') }}" icon="branding.png"
                    path="branding"></x-tool>
            </div>
        @endcan

        @can('has', 'other')
            <div data-title="اخري" class="col-lg-3 col-md-6 col-12">
                <x-tool title="اعدادات مالية" dis="اعدادات تتعلق بمصاريف الخدمة " icon="calculator.png"
                    path="financial"></x-tool>
            </div>
        @endcan

        @can('has', 'year')
            <div class="col-lg-3 col-md-6 col-12">
                <x-tool title="السنوات الدراسية" dis="اضافة وازالة السنوات الدراسية" icon="graduate-hat.png"
                    path="years"></x-tool>
            </div>
        @endcan

        @can('has', 'regions')
            <div class="col-lg-3 col-md-6 col-12">
                <x-tool title="المحافظات والمناطق" dis="اضافة وتعديل المناطق الخاصة بالباص" icon="map.png"
                    path="regions"></x-tool>
            </div>
        @endcan

        @can('has', 'custom_status')
            <div class="col-lg-3 col-md-6 col-12">
                <x-tool title="الحالات الخاصة" dis="ضبط الحالات الخاصة للتقديمات" icon="filter.png"
                    path="custom_status"></x-tool>
            </div>
        @endcan

        @can('has', 'dynamic_fields')
            <div class="col-lg-3 col-md-6 col-12">
                <x-tool title="الحقول المخصصة" dis="ضبط الحقول المخصصة للتقديمات" icon="ask.png"
                    path="dynamic_fields"></x-tool>
            </div>
        @endcan




    </div>
@endsection


@section('js')
    <script>
        $('aside .settings').addClass('active');
    </script>
@endsection
