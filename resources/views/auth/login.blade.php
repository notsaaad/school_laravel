<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> تسجيل الدخول</title>

    @include('auth/head')



</head>

<body>



    <form action="{{ url('login') }}" method="post">





        @csrf

        <div class="text-center">
            <a href="/">
                <img src="{{ get_logo() }}" alt="logo" class="logo"></a>
        </div>




        <fieldset class="mt-3" title="البريد الالكتروني">

            <label for="identifier" class="mb-2"> البريد الالكتروني او رقم التليفون</label>

            <div @class(['input_div', 'invalid' => $errors->has('identifier')])>
                <i>
                    <svg class="svg-sm primary-text-color" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </i>
                <input placeholder="البريد الالكتروني او رقم التليفون" name="identifier"
                    value="{{ old('identifier') }}">


            </div>

            @error('email')
                <div class="error">{{ $message }}
                </div>
            @enderror

        </fieldset>


        <fieldset class="mt-3" title="الرقم السرى">

            <label for="password" class="mb-2">كلمة المرور</label>


            <div @class(['input_div', 'invalid' => $errors->has('password')])>
                <i>
                    <svg class="svg-sm primary-text-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </i>

                <input name="password" type="password" placeholder="كلمة المرور" value="{{ old('password') }}">

            </div>

            @error('password')
                <div class="error">{{ $message }}
                </div>
            @enderror




        </fieldset>


        <div class="button">
            <button>تسجيل دخول</button>
        </div>


    </form>





    @include('auth/script')


</body>

</html>
