<nav lang="{{ getLocale() }}" dir="{{ getLocale() == getLocale() ? 'rtl' : 'ltr' }}" id="nav">


    <a href="/home"> <img src="{{ get_logo() }}" alt="logo" class="logo"></a>


    <div class="d-flex align-items-center">



    

        <div class="profile mx-2 disktop">
            <a class="user-profile dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/assets/images/arabic.png" width="27" style="object-fit: cover">
                <ul class="dropdown-menu">
                    <x-admin.layout.item path="/changeLang/en" title="English"></x-admin.layout.item>
                    <x-admin.layout.item path="/changeLang/ar" title="العربية"></x-admin.layout.item>
                </ul>
            </a>
        </div>

        <div id="menu" class="icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M4 6C4 5.44772 4.44772 5 5 5H19C19.5523 5 20 5.44772 20 6C20 6.55228 19.5523 7 19 7H5C4.44772 7 4 6.55228 4 6Z"
                    fill="currentColor"></path>
                <path
                    d="M4 18C4 17.4477 4.44772 17 5 17H19C19.5523 17 20 17.4477 20 18C20 18.5523 19.5523 19 19 19H5C4.44772 19 4 18.5523 4 18Z"
                    fill="currentColor"></path>
                <path
                    d="M5 11C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H13C13.5523 13 14 12.5523 14 12C14 11.4477 13.5523 11 13 11H5Z"
                    fill="currentColor"></path>
            </svg>
        </div>

    </div>


</nav>