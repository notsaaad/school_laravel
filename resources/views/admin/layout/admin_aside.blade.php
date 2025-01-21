<aside id="aside">

    <div id="collape_btn">
        <i class="fa-solid fa-arrow-right"></i>
    </div>

    <div class="aside_logo">
        <a href="/"><img src="{{ get_logo() }}" alt="logo"></a>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="dark_light_switch">
        </div>
    </div>

    @php
        $userPermissions = json_decode(auth()->user()->HisRole->permissions);
    @endphp

    <ul id="links">

        <x-admin.layout.li class="home " path="admin/home" :title="trans('words.home')">
            @slot('icon')
                <div class="aside_icon">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z">
                        </path>
                    </svg>
                </div>
            @endslot
        </x-admin.layout.li>

        <!-- ========== الملابس ========== -->



        <x-admin.layout.li flip='clothes' class="clothes" path="#" :title="trans('words.الملابس')">
            @slot('icon')
                <div class="aside_icon">
                    <i class="fa-solid fa-shirt"></i>
                </div>
            @endslot
        </x-admin.layout.li>

        <div class="itemm" id="panel-clothes">

            @can('has', 'products_show')
                <x-admin.layout.li class="products" path="admin/products" title="{{ trans('words.المنتجات') }}">
                    @slot('icon')
                        <div class="aside_icon">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                    @endslot
                </x-admin.layout.li>
            @endcan

            @can('has', 'packages_show')
                <x-admin.layout.li class="packages" path="admin/packages" title="{{ trans('words.الحزم') }}">
                    @slot('icon')
                        <div class="aside_icon">
                            <svg viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                focusable="false" class="">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.71333 2.05846C9.8991 1.98048 10.1084 1.98051 10.2942 2.05857L17.5405 5.10357C17.8189 5.22054 18 5.49305 18 5.795C18 5.81319 17.9993 5.83126 17.998 5.84921C17.9993 5.86722 18 5.8854 18 5.90374V14.1977C18 14.4995 17.8192 14.7718 17.5411 14.8889L10.2948 17.9412C10.1087 18.0196 9.89885 18.0196 9.71274 17.9413L2.45911 14.889C2.18092 14.772 2 14.4996 2 14.1977V5.90374C2 5.8854 2.00066 5.86722 2.00195 5.84921C2.00066 5.83126 2 5.81319 2 5.795C2 5.49295 2.18119 5.22038 2.4597 5.10346L9.71333 2.05846ZM3.5 6.92325V13.6996L9.24637 16.1177V9.33552L3.5 6.92325ZM10.7464 9.34143V16.1233L16.5 13.6998V6.92369L10.7464 9.34143ZM15.314 5.795L10.0035 3.56346L8.28923 4.28309L13.6011 6.51477L15.314 5.795ZM11.665 7.32835L7.35195 5.51631L6.33398 5.10388L4.68764 5.795L10.0035 8.02654L11.665 7.32835ZM15.6419 9.27992C15.8026 9.66172 15.6233 10.1014 15.2415 10.2621L12.6737 11.3423C12.2919 11.5029 11.8521 11.3236 11.6915 10.9418C11.5309 10.56 11.7102 10.1203 12.092 9.95968L14.6598 8.87943C15.0416 8.71881 15.4813 8.89811 15.6419 9.27992ZM14.633 12.5061C14.7945 12.8875 14.6162 13.3276 14.2348 13.4891L13.1902 13.9314C12.8088 14.0929 12.3687 13.9146 12.2072 13.5332C12.0457 13.1517 12.224 12.7116 12.6054 12.5501L13.6499 12.1078C14.0314 11.9464 14.4715 12.1246 14.633 12.5061Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    @endslot
                </x-admin.layout.li>
            @endcan




            @can('has', 'show_orders')
                <x-admin.layout.li class="orders" path="admin/orders" :title="trans('words.الطلبات')">
                    @slot('icon')
                        <div class="aside_icon">
                            <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                                </path>
                            </svg>
                        </div>
                    @endslot
                </x-admin.layout.li>
            @endcan


        </div>


        <!-- ========== End الملابس ========== -->








        <!-- ========== المستخدمين ========== -->


        @php
            $allowedPermissions = ['users_show', 'users_action', 'roles'];
        @endphp

        @if (!empty(array_intersect($allowedPermissions, $userPermissions)))
            <x-admin.layout.li flip='users' class="users" path="#" :title="trans('words.users')">
                @slot('icon')
                    <div class="aside_icon">
                        <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                @endslot
            </x-admin.layout.li>
        @endif

        <div class="itemm" id="panel-users">

            @can('has', 'users_show')
                <x-admin.layout.li class="" path="admin/users" :title="trans('words.users')">
                    @slot('icon')
                        <i class="fa-solid fa-circle aside_icon"></i>
                    @endslot
                </x-admin.layout.li>
            @endcan
            @can('has', 'roles')
                <x-admin.layout.li class="" path="admin/roles" :title="trans('words.roles')">
                    @slot('icon')
                        <i class="fa-solid fa-circle aside_icon"></i>
                    @endslot
                </x-admin.layout.li>
            @endcan

        </div>

        <!-- ========== End المستخدمين ========== -->


        <!-- ========== End الباصات ========== -->


        <x-admin.layout.li flip='buses' class="buses" path="#" :title="trans('words.الباصات')">
            @slot('icon')
                <div class="aside_icon">
                    <i class="fa-solid fa-bus"></i>
                </div>
            @endslot
        </x-admin.layout.li>

        <div class="itemm" id="panel-buses">

            <x-admin.layout.li class="" path="admin/buses" :title="trans('words.الباصات')">
                @slot('icon')
                    <i class="fa-solid fa-circle aside_icon"></i>
                @endslot
            </x-admin.layout.li>

            <x-admin.layout.li class="" path="admin/buses/orders" :title="trans('words.الطلبات')">
                @slot('icon')
                    <i class="fa-solid fa-circle aside_icon"></i>
                @endslot
            </x-admin.layout.li>

        </div>
        <!-- ========== End الباصات ========== -->



        <!-- ========== التعريفات ========== -->


        @can('has', 'sec1')

            <x-admin.layout.li flip='sec1' class="sec1" path="#" :title="trans('words.تعريفات التقديمات')">
                @slot('icon')
                <div class="aside_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>

                </div>
            @endslot
            </x-admin.layout.li>

            <div class="itemm" id="panel-sec1">


                @php
                    $definitions = ['discountType', 'referralSource', 'specialStatus'];
                @endphp

                @foreach ($definitions as $definition)
                    <x-admin.layout.li class="" path="admin/definitions/{{ $definition }}" :title="trans('words.' . $definition)">
                        @slot('icon')
                            <i class="fa-solid fa-circle aside_icon"></i>
                        @endslot
                    </x-admin.layout.li>
                @endforeach




            </div>

        @endcan


        @can('has', 'definitions')

            <x-admin.layout.li flip='definitions' class="definitions" path="#" :title="trans('words.التعريفات')">
                @slot('icon')
                    <div class="aside_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>

                    </div>
                @endslot
            </x-admin.layout.li>

            <div class="itemm" id="panel-definitions">


                @can('has', 'stages')
                    <x-admin.layout.li class="stages" path="admin/stages" title="{{ trans('words.المراحل') }}">
                        @slot('icon')
                            <i class="fa-solid fa-circle aside_icon"></i>
                        @endslot
                    </x-admin.layout.li>
                @endcan

                @php
                    $definitions = [
                        'classes',
                        'jobs',
                        'attachments',
                        'nationalities',
                        'disability',
                        'kinship',
                        'qualifications',
                    ];
                @endphp

                @foreach ($definitions as $definition)
                    <x-admin.layout.li class="" path="admin/definitions/{{ $definition }}" :title="trans('words.' . $definition)">
                        @slot('icon')
                            <i class="fa-solid fa-circle aside_icon"></i>
                        @endslot
                    </x-admin.layout.li>
                @endforeach


            </div>
        @endcan
        <!-- ========== End التعريفات ========== -->



        <!-- ========== التقديمات ========== -->



        @php
            $allowedPermissions = ['fees_index', 'fees_actions', 'tests', 'applications_show'];
        @endphp

        @if (!empty(array_intersect($allowedPermissions, $userPermissions)))
            <x-admin.layout.li flip='applications' class="applications" path="#" :title="trans('words.التقديمات')">
                @slot('icon')
                    <div class="aside_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>


                    </div>
                @endslot
            </x-admin.layout.li>

            <div class="itemm" id="panel-applications">


                @can('has', 'fees_index')
                    <x-admin.layout.li class="" path="admin/applications/fees" :title="trans('words.المصاريف')">
                        @slot('icon')
                            <i class="fa-solid fa-circle aside_icon"></i>
                        @endslot
                    </x-admin.layout.li>
                @endcan

                @can('has', 'tests')
                    <x-admin.layout.li class="" path="admin/applications/tests" :title="trans('words.الاختبارات')">
                        @slot('icon')
                            <i class="fa-solid fa-circle aside_icon"></i>
                        @endslot
                    </x-admin.layout.li>
                @endcan

                @can('has', 'applications_show')
                    <x-admin.layout.li class="" path="admin/applications" :title="trans('words.عرض التقديمات')">
                        @slot('icon')
                            <i class="fa-solid fa-circle aside_icon"></i>
                        @endslot
                    </x-admin.layout.li>
                @endcan






            </div>
        @endif

        <!-- ========== End التقديمات ========== -->



        @can('has', 'students_show')
            <x-admin.layout.li class="students" path="admin/students" title="{{ trans('words.الطلاب') }}">
                @slot('icon')
                    <div class="aside_icon">
                        <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                @endslot
            </x-admin.layout.li>
        @endcan


        @can('has', 'warehouses')

            <x-admin.layout.li flip='warehouses' class="warehouses" path="#" :title="trans('words.warehouses')">
                @slot('icon')
                    <div class="aside_icon">
                        <i class="fa-solid fa-warehouse"></i>

                    </div>
                @endslot
            </x-admin.layout.li>

            <div class="itemm" id="panel-warehouses">


                @can('has', 'warehouses')
                    <x-admin.layout.li class="warehouses" path="admin/warehouses" :title="trans('words.warehouses')">
                        @slot('icon')
                            <div class="aside_icon">

                                <i class="fa-solid fa-warehouse"></i>

                            </div>
                        @endslot
                    </x-admin.layout.li>
                @endcan


                @can('has', 'invoices')
                    <x-admin.layout.li class="invoices" path="admin/invoices" title="{{ trans('words.invoices') }}">
                        @slot('icon')
                            <div class="aside_icon">
                                <svg width="22" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-file-text h-5 w-5">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                        @endslot
                    </x-admin.layout.li>
                @endcan




            </div>

        @endcan


        @can('has', 'show_transfers')
            <x-admin.layout.li class="transfers" path="admin/transfers" title="{{ trans('words.التحويلات') }}">
                @slot('icon')
                    <div class="aside_icon">
                        <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>

                    </div>
                @endslot
            </x-admin.layout.li>
        @endcan

        @can('has', 'fees_show')
            <x-admin.layout.li class="fees" path="admin/fees" title="{{ trans('words.المصاريف') }}">
                @slot('icon')
                    <div class="aside_icon">
                        <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>

                    </div>
                @endslot
            </x-admin.layout.li>
        @endcan


        <x-admin.layout.li flip='expeness' class="expeness" path="#" title="المصاريف الدراسية">
            @slot('icon')
                <div class="aside_icon">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
            @endslot
        </x-admin.layout.li>

        <div class="itemm" id="panel-expeness">

            <x-admin.layout.li class="" path="admin/expenses" title="اضافة مصاريف دراسية">
                @slot('icon')
                    <i class="fa-solid fa-circle aside_icon"></i>
                @endslot
            </x-admin.layout.li>

            <x-admin.layout.li class="" path="#" title="عرض للمصاريف الادارية">
                @slot('icon')
                    <i class="fa-solid fa-circle aside_icon"></i>
                @endslot
            </x-admin.layout.li>

        </div>



        {{--start مصاريف دراسية  --}}



        <x-admin.layout.li class="statistics" path="admin/statistics" :title="trans('words.الاحصائيات')">
            @slot('icon')
                <div class="aside_icon">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
            @endslot
        </x-admin.layout.li>

        <x-admin.layout.li class="logout" path="logout" :title="trans('words.تسجيل الخروج')">
            @slot('icon')
                <div class="aside_icon">
                    <x-icons.logout></x-icons.logout>
                </div>
            @endslot
        </x-admin.layout.li>

        {{--end مصاريف دراسية  --}}

    </ul>


</aside>
