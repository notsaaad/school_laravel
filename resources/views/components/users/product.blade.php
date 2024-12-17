@props(['add_to', 'product', 'userType'])

@php
    $data = productData($product)['users'];
@endphp

<div class="product position-relative">


    <div class="fav" onclick="favToggle(this , '{{ $product->id }}')">
        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $product->favourites_exists ? 'red' : 'none' }} "
            viewBox="0 0 24 24" stroke-width="1.5" stroke=" {{ $product->favourites_exists ? 'red' : '#6b7280' }} "
            class="w-6 h-6 heart">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
        </svg>
    </div>



    <a
        href="/{{ $userType }}/{{ getCountry()['code'] }}/products/{{ slug($product->name) }}@if (!empty($add_to)) ?add_new_product={{ $add_to }} @endif">
        <img src="{{ path($product->firstImg?->img) }}">

    </a>

    <div class="product_body">
        <div class="product_name">
            <p>{{ $product->name }}</p>
        </div>

        @if (count($product->variants) == 0 || (count($product->variants) != 0 && thisProductSamePrice($product)))
            @if ($product->comissation != null)
                <div class="p_s">
                    <div class="title">سعر البيع شامل ربحك</div>
                    <div class="price">{{ $data['priceShowToUser'] + $data['comissation'] }}
                        {{ getCountry()['curancy'] }}</div>
                </div>

                <div class="p_s">
                    <div class="title"> أرباحك </div>
                    <div class="price">{{ $data['comissation'] }} {{ getCountry()['curancy'] }}</div>
                </div>
            @else
                <div class="p_s">
                    <div class="title">سعر البيع بدون ربحك</div>
                    <div class="price">{{ $data['priceShowToUser'] }} {{ getCountry()['curancy'] }}</div>
                </div>


                <div class="p_s">
                    <div class="title"> أرباحك </div>
                    <div class="price">مفتوحة</div>
                </div>
            @endif
        @else
            <div class="p_s">
                <div class="title">{{ trans('users.يرجي اختيار خصائص المنتج') }}</div>
            </div>
        @endif


        <div class="show_price">
            @if ($product->show_price == '1')
                <div class="p_s">
                    <div class="title">حالة المخزون</div>
                    <div class="price">{{ $data['stock'] }} {{ trans('users.piece') }} </div>
                </div>
            @else
                <div class="p_s">
                    <div class="title">حالة المخزون</div>
                    <div class="price">{{ trans('users.available') }} </div>
                </div>
            @endif

        </div>


        @if (count($product->variants) == 0 && $product->stock != 0)
            <div class="d-flex align-items-center justify-content-between mt-1">
                <div class="numberInput">

                    <button class="adjust-quantity-btn" onclick="minasStock()">-</button>

                    <input id="stock_{{ $product->id }}" name="stock" min="1" value="1"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                    <button class="adjust-quantity-btn" onclick="plusStock()">+</button>
                </div>

                <x-form.button id="addToCartBtn" onclick="add_to_cart_direct({{ $product->id }})"
                    title="{{ trans('users.add_to_cart') }}"></x-form.button>
            </div>
        @else
            <a class="es-btn-primary w-100 d-flex justify-content-center" id="addToCartBtn"
                href="/{{ $userType }}/{{ getCountry()['code'] }}/products/{{ slug($product->name) }}@if (!empty($add_to)) ?add_new_product={{ $add_to }} @endif">
                اضغط هنا لي اختيار خصائص المنتج</a>
        @endif



        <div class="d-flex justify-content-between my-1 align-items-center">


            @if ($product->video != null)
                <div onclick="showVideo('{{ $product->video }}')">
                    <img src="{{ asset('assets/images/video.png') }}" style="width: 35px; height: unset;" alt="video" class="video">

                </div>
            @endif

            @if ($data['comissation'] != 0)
                <div class="stock">
                    <svg width="32" height="24" viewBox="0 0 32 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">

                        <path
                            d="M11.5469 11.1784V14.0951M20.5469 9.72005V12.6367M19.7969 6.07422C21.6334 6.07422 22.6267 6.34748 23.121 6.55944C23.1868 6.58766 23.2197 6.60178 23.3147 6.68991C23.3716 6.74273 23.4755 6.89773 23.5023 6.9697C23.5469 7.08976 23.5469 7.15538 23.5469 7.28664V15.124C23.5469 15.7867 23.5469 16.118 23.4447 16.2883C23.3407 16.4615 23.2404 16.5421 23.0458 16.6086C22.8545 16.6741 22.4683 16.6019 21.696 16.4577C21.1554 16.3567 20.5143 16.2826 19.7969 16.2826C17.5469 16.2826 15.2969 17.7409 12.2969 17.7409C10.4603 17.7409 9.46702 17.4676 8.97279 17.2557C8.90697 17.2274 8.87406 17.2133 8.77907 17.1252C8.72214 17.0724 8.61821 16.9174 8.59148 16.8454C8.54687 16.7253 8.54687 16.6597 8.54688 16.5285L8.54688 8.69109C8.54688 8.02845 8.54688 7.69713 8.64908 7.52682C8.75305 7.35359 8.85332 7.27305 9.04792 7.20648C9.23923 7.14103 9.6254 7.21317 10.3977 7.35744C10.9383 7.45843 11.5795 7.53255 12.2969 7.53255C14.5469 7.53255 16.7969 6.07422 19.7969 6.07422ZM17.9219 11.9076C17.9219 12.9143 17.0824 13.7305 16.0469 13.7305C15.0113 13.7305 14.1719 12.9143 14.1719 11.9076C14.1719 10.9008 15.0113 10.0846 16.0469 10.0846C17.0824 10.0846 17.9219 10.9008 17.9219 11.9076Z"
                            stroke="#039855" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <rect x="0.1" y="0.2" width="31" height="23" rx="3.5" stroke="none">
                        </rect>
                    </svg>
                    {{ trans('users.your_commission') }} <span> {{ $data['comissation'] }}
                        {{ getCountry()['curancy'] }}</span>
                </div>
            @endif
        </div>

    </div>



</div>
