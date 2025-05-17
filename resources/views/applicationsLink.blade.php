<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> تسجيل الدخول</title>

    <script src="{{ asset('assets/admin/js/jquery.js') }}" charset="utf-8"></script>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.css') }}">

    @include('auth/head')

    <script src="{{ asset('assets/admin/js/select2.js') }}" defer></script>

    <style>
        :root {
            --mainBg: #31006f
        }


        button[disabled] {
            cursor: not-allowed;
            opacity: .3 !important;
        }

        form {
            width: 60%;
            padding-top: 0px;

        }



        .logo {
            max-height: 100px;
            margin: 0px;
            margin-bottom: 10px
        }

        @media (max-width:993px) {
            form {
                width: 95%;
            }
        }


        input:not([type='checkbox']),
        textarea,
        .swal2-input,
        .select2-container--default .select2-selection--multiple {
            outline: none;
            padding: .6rem 1rem;
            border-radius: .375rem;
            border: 1px solid rgb(230 230 230);
            font-size: .875rem;
            width: 100%;
            background: white;
        }


        .select2-container--default .select2-selection--multiple {
            outline: none;
            padding: .6rem 1rem;
            border-radius: .375rem;
            border: 1px solid rgb(230 230 230);
            font-size: .875rem;
            width: 100%;
        }

        .select2-container--default .select2-selection--multiple {
            outline: none;
            padding: .6rem 1rem;
            border-radius: .375rem;
            border: 1px solid rgb(230 230 230);
            font-size: .875rem;
            width: 100%;
            font-weight: 500;
            color: black;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: rgb(67 118 109);

        }

        .select2-selection--multiple:focus {
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            border-color: rgb(67 118 109);
            outline-width: 0;


            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(202 211 209 / var(--tw-ring-opacity));
        }


        /* select */

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            transition: all 100ms ease 0s;
            line-height: unset;
        }

        .modal .select2-container {
            width: 100% !important;
            min-height: 42px;

        }

        .select2-container--default .select2-selection--single {
            outline: none;
            transition: all 100ms ease 0s;
            border-radius: .375rem;
            border: 1px solid rgb(230 230 230);
            font-size: 13px;
            width: 100%;
            height: 100%;
            padding: .6rem 1rem;
            border-radius: .375rem;
        }

        .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
            padding-right: 0px;
            color: black;
            font-weight: 500;

        }

        .select2-container--default .select2-results>.select2-results__options {
            padding: 5px 10px;

        }


        .select2-selection__arrow {
            top: 50% !important;
            transform: translatey(-50%);
            transition: all 100ms ease 0s;

        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background: var(--mainBg)
        }

        .select2-search--dropdown {
            padding: 5px 10px;

        }

        /* contaner ptions */
        .select2-container--open .select2-dropdown--below {
            padding: 5px 0px;
            background-color: rgb(255, 255, 255);
            border-radius: 0.375rem;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px;
            margin-bottom: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid rgb(230, 230, 230);

        }

        /* option */
        .select2-results__option {
            border-radius: 5px;
            cursor: default;
            display: block;
            font-size: 14px;
            width: 100%;
            user-select: none;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            background-color: transparent;
            color: rgb(0, 0, 0);
            padding: 8px 12px;
            box-sizing: border-box;
            border-radius: 0.375rem;
            cursor: pointer;
        }


        .select2-container--default.select2-container--open.select2-container--below .select2-selection--single,
        .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
            border-radius: .375rem;

        }

        .select2-container {
            width: 100% !important;

        }

        /* Style for the active state of Select2 */
        .select2-container--default.select2-container--open.select2-container--below .select2-selection--single,
        .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            border-color: rgb(67 118 109);
            outline-width: 0;
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(202 211 209 / var(--tw-ring-opacity));
        }
    </style>


    <style>
        @media (max-width:993px) {

            html,
            body {
                overflow-x: hidden !important;
            }
        }
    </style>




</head>

<body>


    @if($application->can_share == 'yes')
    <form action="/applications/{{$application->code}}" method="post" class="position-relative">

        @csrf
        @method("put")

        <div class="text-center">
            <a href="/">
                <img src="{{ get_logo() }}" alt="logo" class="logo" style="width: 120px !important"></a>
        </div>


        <div class="row mb-2 g-2">
            <x-form.input col="col-lg-6 col-12" label="اسم الطالب" disabled name="name"
                :value="$application->name"></x-form.input>
            <x-form.input col="col-lg-3 col-6" label="المرحلة" disabled name="name" :value="$application->stage->name"></x-form.input>
            <x-form.input col="col-lg-3 col-6" label="نوع الدراسة" disabled name="name"
                :value="$application->study_type"></x-form.input>
        </div>




        <div class="row g-2">





            @foreach ($fields as $field)
                @php
                    $value = $application->applicationData->where('field_id', $field->id)->first()?->value;
                @endphp

                @if ($field->type == 'text')
                    <x-form.input col="col-12" label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}]" :value="$value"></x-form.input>
                @endif

                @if ($field->type == 'number')
                    <x-form.input type="number" label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}]" :value="$value"></x-form.input>
                @endif

                @if ($field->type == 'select')
                    <x-form.select label="{{ $field->name }}" :required="$field->is_required" name="fields[{{ $field->id }}]">
                        <option value="" disabled {{ !$value ? 'selected' : '' }}>Select</option>

                        @foreach (explode(',', $field->options) as $option)
                            <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                {{ $option }}</option>
                        @endforeach
                    </x-form.select>
                @endif

                @if ($field->type == 'checkbox')
                    <x-form.select col="col-12" multiple label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}][]">
                        @php
                            $values = explode(',', $value ?? '');
                        @endphp
                        @foreach (explode(',', $field->options) as $option)
                            <option value="{{ $option }}"
                                {{ in_array(trim($option), $values) ? 'selected' : '' }}>
                                {{ $option }}</option>
                        @endforeach
                    </x-form.select>
                @endif
            @endforeach


        </div>


        <div class="button">
            <button onclick="checkAllForms()" id="submitBtn">ارسال</button>
        </div>


    </form>
    @else
      <div class="alert alert-success p-4" role="alert">
          تم الاجابة علي هذا الطلب مسبقا
      </div>
    @endif



    @include('auth/script')

    <script>
        $(document).ready(function() {


            $('.modelSelect').select2();
            checkAllForms()



        });
    </script>

    <script>
        function checkAllForms() {
            $("form").each(function() {
                check($(this));
            });
        }


        $('.modelSelect').on('change', function() {
            checkAllForms()
        });


        $(".checkThis").on("keyup", function() {
            check($(this).closest("form"));
        });

        function check(form) {


            var allFilled = true;

            try {
                form.find(".checkThis").each(function() {
                    if ($(this).hasClass("modelSelect")) {
                        if (
                            $(this).select2("data")[0] == undefined ||
                            $(this).select2("data")[0].id === ""
                        ) {
                            allFilled = false;
                            return false;
                        }
                    } else if ($(this).hasClass("select-dropdown")) {
                        var selectizeControl = $(".select-dropdown.checkThis")[0]
                            .selectize;
                        var selectedValues = selectizeControl.getValue();

                        if (selectedValues.length == 0) {
                            allFilled = false;
                            return false;
                        }
                    } else if ($(this).is(":checkbox") && !$(this).is(":checked")) {
                        allFilled = false;
                        return false;
                    } else {
                        if ($(this).val().trim() === "") {
                            allFilled = false;
                            return false;
                        }
                    }
                });
            } catch (error) {}

            form.find("#submitBtn").prop("disabled", !allFilled);
        }
    </script>


</body>

</html>
