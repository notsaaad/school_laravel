<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<meta name="csrf-token" content="{{ csrf_token() }}">


<link rel="stylesheet" href="{{ asset('assets/admin/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery-ui.min.css') }}">






{{-- @if (auth()->user()->role == 'admin') --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.core.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/admin/css/ssi-uploader.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
{{-- @endif --}}




<link rel="stylesheet" href="{{ asset('assets/admin/css/time.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap2-toggle.min.css') }}">




<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
    integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}">


<link rel="stylesheet" href="{{ asset('assets/admin/css/icons.css') }}">

<link rel="stylesheet" href="{{ asset('assets/admin/css/adminstyle.css?ver=1.1') }}">
@if (getLocale() != 'ar')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/english.css?ver=1.1') }}">
@endif

<link rel="stylesheet" href="{{ asset('assets/admin/css/mobile.css?ver=1.2') }}" media="screen and (max-width: 992px)">




<link rel="shortcut icon" href="{{ get_fav() }}" type="image/x-icon">

<script>
    if (
        localStorage.getItem("mainBg")
    ) {
        let rootStyles = document.documentElement.style;

        let mainBg = localStorage.getItem("mainBg");
        rootStyles.setProperty("--mainBg", `${mainBg}`);
    }
</script>

@yield('style')
