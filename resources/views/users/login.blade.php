@php
    app()->setLocale('en');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <script src="{{ asset('assets/admin/js/jquery.js') }}" charset="utf-8"></script>

    @include('users.inc.head')

    <link rel="stylesheet" href="/assets/users/css/login.css">

    <style>
        body {
            background-image: url({{ get_logo() }});

        }
    </style>
</head>

<body>

    <form action="students_login" method="post">



        <div class="layer"></div>


        @csrf

        <div class="text-center">
            <a href="/">
                <img src="{{ get_logo() }}" alt="logo" class="logo"></a>
        </div>


        <fieldset class="mt-3" title="البريد الالكتروني">


            <div @class(['input_div', 'invalid' => $errors->has('id')])>
                <svg class="svg-sm primary-text-color" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <input placeholder="ID Number" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="id"
                    value="{{ old('id') }}">


            </div>

            @error('id')
                <div class="error">{{ $message }}
                </div>
            @enderror

        </fieldset>


        <div class="button">
            <button>Login</button>
        </div>


    </form>


    @include('users.inc.script')
    @include('admin/inc/errors')


</body>

</html>
