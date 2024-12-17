@php
    app()->setLocale('en');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>



    @yield('title')
    <script src="{{ asset('assets/admin/js/jquery.js') }}" charset="utf-8"></script>
    @include('users.inc.head')
    @yield('css')


</head>

<body>


    <div class="layer"></div>

        @include('users.inc.nav')




    @if (!request()->is('students/home') && auth()->user()->role == 'user')
        @include('users.inc.user_aside')
    @endif




    @yield('content')


    @include('users.inc.script')
    @include('admin/inc/errors')
    @yield('js')

</body>

</html>
