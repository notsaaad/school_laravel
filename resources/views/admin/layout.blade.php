@php
    app()->setLocale(getLocale());
@endphp


<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getLocale() == 'ar' ? 'rtl' : 'ltr' }}">




<head>
    @yield('title')
    <script src="{{ asset('assets/admin/js/jquery.js') }}" charset="utf-8"></script>

    @include('admin.inc.head')
    @yield('css')



</head>

<body>

    <div class="layer"></div>

    <div class="layout">

        @include('admin.layout.nav')

        @if (auth()->user()->role == 'admin')
            @include('admin.layout.admin_aside')
        @elseif(auth()->user()->role == 'postman')
            @include('admin.layout.postman_aside')
        @elseif(auth()->user()->role == 'trader')
            @include('admin.layout.trader_aside')
        @endif






        <div class="colors" id="colors">
            <button style="background-color: rgb(245 158 11 / var(--tw-bg-opacity));"
                onclick="changeColor('عنبري')">عنبري</button>
            <button style="background-color: rgb(59 130 246  / var(--tw-bg-opacity));"
                onclick="changeColor('أزرق')">أزرق</button>

            <button style="background-color: rgb(79, 70, 156);  " onclick="changeColor('بنفسجي')">بنفسجي</button>

            <button style="background-color: rgb(6 182 212 / var(--tw-bg-opacity));"
                onclick="changeColor('سماوي مفتح')">سماوي مفتح</button>
            <button style="background-color: rgb(16 185 129 / var(--tw-bg-opacity));"
                onclick="changeColor('زمردي')">زمردي</button>
            <button style="background-color: rgb(107 114 128 / var(--tw-bg-opacity));"
                onclick="changeColor('رمادي')">رمادي</button>

            <button style="background-color: rgb(236 72 153  / var(--tw-bg-opacity));"
                onclick="changeColor('زهري')">زهري</button>
            <button style="background-color: rgb(168 85 247  / var(--tw-bg-opacity));"
                onclick="changeColor('أرجواني')">أرجواني</button>
            <button style="background-color: rgb(239 68 68  / var(--tw-bg-opacity));"
                onclick="changeColor('احمر')">احمر</button>
            <button style="background-color: rgb(34 197 94 / var(--tw-bg-opacity));"
                onclick="changeColor('أخضر')">أخضر</button>
            <button style="background-color: rgb(132 204 22 / var(--tw-bg-opacity));"
                onclick="changeColor('ليمي')">ليمي</button>
            <button style="background-color: rgb(249 115 22  / var(--tw-bg-opacity));"
                onclick="changeColor('برتقالي')">برتقالي</button>
            <button style="background-color: rgb(14 165 233  / var(--tw-bg-opacity));"
                onclick="changeColor('سماوي')">سماوي</button>
            <button style="background-color: rgb(20 184 166  / var(--tw-bg-opacity));"
                onclick="changeColor('شرشيري')">شرشيري</button>
            <button style="background-color: rgb(234 179 8 / var(--tw-bg-opacity));"
                onclick="changeColor('أصفر')">أصفر</button>

            <button style="background-color: #335b54;" onclick="changeColor('اخضر غامق')">اخضر غامق</button>

            <button style="background-color: #1b2225;" onclick="changeColor('اسود')">اسود</button>




            <button class="toggleColors" onclick="toggleColors()">

                <i class="fa-solid fa-gear fa-spin"></i>
            </button>
        </div>

        <div class="content" id="content">




            @yield('content')

        </div>

        <script>
            // Restore the collapse state on window load
            if (localStorage.getItem("navCollapsed") === "true") {
                $("nav").addClass("nav_collapse");
            }
            if (localStorage.getItem("contentCollapsed") === "true") {
                $("#content").addClass("content_collapse");
            }
            if (localStorage.getItem("asideCollapsed") === "true") {
                $("aside").addClass("aside_collapse");
            }
            if (localStorage.getItem("btnCollapsed") === "true") {
                $("#collape_btn").addClass("collapse");
            }
            if (localStorage.getItem("dark") === "true") {
                $("#aside").addClass("dark");
            }
        </script>

    </div>

    @include('admin.inc.scripts')
    @include('admin/inc/errors')
    @yield('js')
</body>

</html>
