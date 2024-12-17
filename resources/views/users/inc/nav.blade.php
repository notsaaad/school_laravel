<header>

    <div data-title="جزء البحث والايقونات" class="container up">

        <a href="/home"><img src="{{ get_logo() }}" alt="logo" class="logo"></a>




        <div class="icons">

            <div class="icon position-relative" data-title="سلة التسوق">
                <a style="color: inherit" href="/students/cart"> <img src="/assets/images/cart.png"></a>
                <span id="cartCount" class="cart_icon_count" style="font-family: Cairo">{{cartCount()}}</span>

            </div>



            <div class="profile">
                <a class="user-profile dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <div class="avatar overflow-hidden"
                        @if (auth()->user()->img != null) )
                 style="background: transparent" @endif>
                        @if (auth()->user()->img == null)
                            {{ substr(auth()->user()->name, 0, 2) }}
                        @else
                            <img src="{{ path(auth()->user()->img) }}">
                        @endif
                    </div>
                    <div class="mx-1 d-lg-block d-none">
                        <p class="user-name">{{ auth()->user()->name }}</p>
                        <p class="role">
                            student
                            <svg class="mark2" xmlns="http://www.w3.org/2000/svg" width="8" height="4.574"
                                viewBox="0 0 8 4.574" style="transform: translate(-5px, 0px);">
                                <path id="Icon_ionic-ios-arrow-forward" data-name="Icon ionic-ios-arrow-forward"
                                    d="M14.442,10.195,11.414,7.17a.569.569,0,0,1,0-.807.577.577,0,0,1,.81,0l3.43,3.427a.571.571,0,0,1,.017.788L12.227,14.03a.572.572,0,1,1-.81-.807Z"
                                    transform="translate(14.196 -11.246) rotate(90)" fill="currentColor"></path>
                            </svg>
                        </p>
                    </div>

                </a>
                <ul class="dropdown-menu" style=""><a class="user-profile dropdown" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    </a>
                    <li><a class="user-profile dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        </a><a class="dropdown-item" href="/profile">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span> Account Settings </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/students/logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-log-out w-5 h-5">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span> Logout </span>
                        </a>
                    </li>
                </ul>


            </div>


            <div id="menu" class="icon" title="القائمة">
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



    </div>

</header>



