<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<link rel="stylesheet" href="{{ asset('assets/admin/css/all.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/time.css') }}">

<link rel="stylesheet" href="{{ asset('assets/admin/css/select2.css') }}">



<link rel="stylesheet" href="{{ asset('assets/users/css/usersStyle.css?ver=1.1') }}">
<link rel="stylesheet" href="{{ asset('assets/users/css/usersMobile.css?ver=1.2') }}"
    media="screen and (max-width: 992px)">

<link rel="shortcut icon" href="{{ get_fav() }}" type="image/x-icon">

<meta name="csrf-token" content="{{ csrf_token() }}">


@yield('style')
