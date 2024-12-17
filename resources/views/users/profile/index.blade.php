@extends('users.layout')



@section('css')
    <style>
        .nav-tabs {
            padding: 16px 8px;
            border-radius: 5px;
        }


        .tab_content {
            background: white;
            padding: 16px 12px;
            border-radius: 5px;
        }


        .nav-link svg {
            margin-left: 5px;
        }

        @media (min-width:993px) {
            .nav-tabs {
                display: flex;
                flex-direction: column;
                padding: 16px 8px;
                border-radius: 5px;

            }

            .nav-tabs button {
                display: flex;
            }


    

        }

        .tabs {
            padding: 0px;
        }

        body {
            background: #f8f8f8;
        }

        header {
            background: white;
        }
    </style>

    <style>
        .way {
            display: none;
        }

        #loader {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 9;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            display: none;
        }

        #loader {
            font-size: 40px;
            color: var(--mainColor);
        }

        @media (max-width:993px) {
            .tab_content {

                margin-top: 10px
            }

        }
    </style>
@endsection

@section('title')
    <title>Account Settings</title>
@endsection


@section('content')
    <div class="content container  mt-3">
        <div class="contnet-title mb-2"> Account Settings </div>


        <div class="w-100 row m-auto p-0">
            <div class="col-lg-3 p-lg-1 p-0">

                <nav class="tabs">

                    <div class="nav nav-tabs" id="nav-tab" role="tablist">

                        <x-tab class="active" name="info" title="Basic Information"><svg width="22"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg></x-tab>










                    </div>
                </nav>
            </div>

            <div class="col-lg-9  p-lg-1 p-0 mb-5">
                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab"
                        tabindex="0">
                        <div class="tab_content p-3">
                            @include('profile/info')
                        </div>
                    </div>





                </div>
            </div>

        </div>
    </div>
@endsection


@section('js')
    <script>
        $('.settings').addClass('active');

        document.addEventListener("DOMContentLoaded", function() {
            // Read the URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("tab");

            // Function to remove active class from other tabs
            function removeActiveFromTabs() {
                const tabs = document.querySelectorAll(".nav-link");
                const tabContents = document.querySelectorAll(".tab-pane");
                tabs.forEach((tab) => {
                    tab.classList.remove("active");
                    tab.setAttribute("aria-selected", "false");
                });
                tabContents.forEach((tabContent) => {
                    tabContent.classList.remove("show", "active");
                });
            }

            // Activate the tab based on the URL parameter
            if (activeTab === "nav-info" || activeTab === "") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-info-tab");
                const navImgsTabContent = document.getElementById("nav-info");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");
            } else if (activeTab === "nav-password") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-password-tab");
                const navImgsTabContent = document.getElementById("nav-password");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");

            } else if (activeTab === "nav-payment") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-payment-tab");
                const navImgsTabContent = document.getElementById("nav-payment");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");

            }

        });

        function change(tab) {
            const urlWithoutTab = window.location.href.split("?")[0];
            const newUrl = urlWithoutTab + `?tab=${tab}`;
            window.history.replaceState({}, "", newUrl);
        }
    </script>

    <script>
        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='payment_id']").val(data_id)

        }
    </script>
@endsection
