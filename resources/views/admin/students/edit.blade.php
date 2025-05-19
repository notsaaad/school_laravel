@extends('admin.layout')


@section('title')
    <title> {{ trans('words.btn_update') }} {{ $user->name }}</title>
@endsection


@section('css')
    <style>
        .nav-tabs {
            justify-content: unset !important;
        }
    </style>
@endsection




@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/students?type={{ $user->details->study_type}}"
            title=" {{ trans('words.btn_update') }} {{ $user->name }}"></x-admin.layout.back>

        @can('has', 'students_login')
            <x-form.link class="es-btn-primary" path="admin/students/{{ $user->id }}/login"
                title="تسجيل الدخول علي اكونت {{ $user->name }}"></x-form.link>
        @endcan
    </div>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">

            <button onclick="change('nav-main_information')" class="nav-link active" id="nav-main_information-tab"
                data-bs-toggle="tab" data-bs-target="#nav-main_information" type="button" role="tab"
                aria-controls="nav-main_information" aria-selected="true">
                البيانات الاساسية
            </button>


            <button onclick="change('nav-parents_information')" class="nav-link " id="nav-parents_information-tab"
                data-bs-toggle="tab" data-bs-target="#nav-parents_information" type="button" role="tab"
                aria-controls="nav-parents_information" aria-selected="true">
                بيانات الوالدين
            </button>
            <button onclick="change('nav-three')" class="nav-link " id="nav-three-tab" data-bs-toggle="tab"
                data-bs-target="#nav-three" type="button" role="tab" aria-controls="nav-three" aria-selected="true">
                صلة القرابة
            </button>
            <button onclick="change('nav-disability')" class="nav-link " id="nav-disability-tab" data-bs-toggle="tab"
                data-bs-target="#nav-disability" type="button" role="tab" aria-controls="nav-disability"
                aria-selected="true">
                دمج
            </button>



        </div>
    </nav>



    <div class="tab-content" id="nav-tabContent">

        <div class="tab-pane fade show active" id="nav-main_information" role="tabpanel"
            aria-labelledby="nav-main_information-tab" tabindex="0">
            <div class="mt-2">
                @include('admin.students.main_information')
            </div>

        </div>

        <div class="tab-pane fade " id="nav-parents_information" role="tabpanel"
            aria-labelledby="nav-parents_information-tab" tabindex="0">
            <div class="mt-2">
                @include('admin.students.parents_information')
            </div>
        </div>


        <div class="tab-pane fade  " id="nav-three" role="tabpanel" aria-labelledby="nav-three-tab" tabindex="0">
            <div class="mt-2">
                @include('admin.students.three')
            </div>
        </div>

        <div class="tab-pane fade  " id="nav-disability" role="tabpanel" aria-labelledby="nav-disability-tab"
            tabindex="0">
            <div class="mt-2">
                @include('admin.students.disability')
            </div>
        </div>



    </div>
@endsection


@section('js')
    <script>
        $('aside .students').addClass('active');
        $('.modelSelect').select2();


        flatpickr('input.date', {
            enableTime: false,
            dateFormat: "Y-m-d"
        });




        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("tab");

            function removeActiveFromTabs() {
                document.querySelectorAll(".nav-link").forEach((tab) => {
                    tab.classList.remove("active");
                    tab.setAttribute("aria-selected", "false");
                });
                document.querySelectorAll(".tab-pane").forEach((tabContent) => {
                    tabContent.classList.remove("show", "active");
                });
            }

            function activateTab(tabId) {
                const tab = document.getElementById(`${tabId}-tab`);
                const tabContent = document.getElementById(tabId);
                if (tab && tabContent) {
                    removeActiveFromTabs();
                    tab.classList.add("active");
                    tab.setAttribute("aria-selected", "true");
                    tabContent.classList.add("show", "active");
                }
            }

            if (activeTab) {
                activateTab(activeTab);
            }
        });

        function change(tab) {
            const urlWithoutTab = window.location.href.split("?")[0];
            const newUrl = urlWithoutTab + `?tab=${tab}`;
            window.history.replaceState({}, "", newUrl);
        }
    </script>

    <script>
        document.getElementById('mobile').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });
        document.getElementById('father_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });
        document.getElementById('father_whatsapp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });

        document.getElementById('mother_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });
        document.getElementById('mother_whatsapp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });

        document.getElementById('kinship_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });


        document.getElementById('kinship2_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });


        document.getElementById('national_id').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) {
                value = value.slice(0, 14);
            }
            e.target.value = value;
        });

        document.getElementById('mother_national_id').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) {
                value = value.slice(0, 14);
            }
            e.target.value = value;
        });

        document.getElementById('father_national_id').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) {
                value = value.slice(0, 14);
            }
            e.target.value = value;
        });


        document.getElementById('father_national_id').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) {
                value = value.slice(0, 14);
            }
            e.target.value = value;
        });

        document.getElementById('kinship_national_id').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) {
                value = value.slice(0, 14);
            }
            e.target.value = value;
        });
    </script>
@endsection
