@extends('users.layout')


@php
    if (auth()->user()->role == 'admin') {
        $role = 'admin';
    } else {
        $role = 'students';
    }
@endphp



@section('title')
    <title> Items </title>
@endsection

@section('css')
    <style>
        .empty {
            font-weight: 600;
            font-size: 25px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-transform: capitalize;
        }

        .es-btn-primary {
            font-size: 16px !important;
            height: 42px !important;
        }
    </style>
@endsection


@section('content')
    <div class="content container mt-3">

        <div class="sliderHeader w-100 px-1">

            @if (!request()->has('addTo'))
                <div class="d-flex">
                    <h2 class="slide_h2"> All Items </h2> <span class="result">(
                        {{ count($products) }} result )
                    </span>
                </div>
            @else
                <div class="d-flex align-items-center">

                    <a class="back" href="/{{$role}}/orders/{{ request()->get('addTo') }}">
                        <svg style="height: 1rem;
            width: 1rem;" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="black" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>


                    <h1 class="contnet-title"> Back to Order</h1>

                </div>
            @endif




        </div>
        <div class="row mt-2 g-3">


            @forelse ($products as $product)
                <div class="col-lg-3  col-md-6 col-12">
                    <div class="product">
                        <div class="product_content">
                            <img src="{{ path($product->img) }}" alt="{{ $product->name }}">
                            <div class="product_name">
                                <p> {{ $product->name }} </p>
                            </div>

                            <div class="product_name">
                                <p> {{ $product->sell_price }} EGP </p>
                            </div>


                            <form action="/{{ $role }}/items/makeOrder" method="post">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="addTo" value="{{ request()->get('addTo') }}">

                                <div class="d-flex align-items-center justify-content-between mt-1 gap-3">
                                    <div class="numberInput">

                                        <button type="button" class="adjust-quantity-btn"
                                            onclick="minusStock('{{ $product->id }}')">-</button>

                                        <input id="stock_{{ $product->id }}" name="stock" min="1" value="1"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                        <button type="button" class="adjust-quantity-btn"
                                            onclick="plusStock('{{ $product->id }}')">+</button>
                                    </div>


                                    <select name="variant_id">
                                        <option value="" disabled selected> select size</option>

                                        @foreach ($product->variants as $variant)
                                            <option value="{{ $variant->id }}"> {{ $variant->value }}</option>
                                        @endforeach
                                    </select>

                                </div>



                                <div class="cart_btn_div">
                                    <button disabled class="es-btn-primary mt-2" id="addToCartBtn">Add To Cart <svg
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </button>
                                </div>


                            </form>
                        </div>
                    </div>

                </div>

            @empty
                <div class="text-center d-flex flex-column  align-items-center" style="font-size: 30px">

                    <svg width="247" height="198" viewBox="0 0 247 198" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M36.4536 30.4048H118.497C118.898 22.5338 114.989 14.6539 108.013 10.9913C101.038 7.3286 91.96 8.11155 85.7082 12.9101C80.1178 8.09079 74.308 3.14694 67.2496 0.976035C60.1912 -1.19487 51.5017 0.145636 47.145 6.10672C44.1348 10.2261 43.7226 15.858 45.2054 20.7574C38.8133 20.706 32.4252 20.8562 26.041 21.2081C22.402 21.4068 17.4404 22.4656 17.2595 26.1075C16.9807 31.5496 36.4536 30.4048 36.4536 30.4048Z"
                            fill="#F9F9F9"></path>
                        <path
                            d="M211.52 28.5839C210.003 27.8092 208.258 27.6081 206.606 28.0174C205.6 28.2458 204.63 28.6106 203.64 28.9072C201.763 29.4825 201.599 29.9748 200.834 28.3407C200.001 26.5613 201.024 24.7314 199.39 22.8719C198.591 21.9713 197.549 21.3207 196.389 20.9982C195.228 20.6757 194 20.695 192.851 21.0539C191.617 18.1446 190.181 15.3627 187.83 13.0969C183.147 8.59495 175.999 6.71469 169.89 9.24148C167.156 10.4129 164.734 12.2075 162.817 14.4819C161.81 15.6569 160.922 16.9296 160.168 18.281C159.483 19.5058 159.181 21.1014 158.359 22.2076C156.553 21.5284 154.889 20.6328 153.021 20.1138C149.335 19.0966 145.328 18.8978 142.101 21.3001C139.45 23.2693 137.771 26.8311 138.628 30.1201C139.115 31.9856 139.999 33.3676 141.891 33.3824C144.56 33.421 147.229 33.3824 149.919 33.3824H216.197C216.197 33.3824 214.275 30.049 211.52 28.5839Z"
                            fill="#F9F9F9"></path>
                        <path
                            d="M246.471 54.1869C247.361 49.6049 245.688 44.5602 242.292 41.2949C238.051 37.223 231.586 35.5207 226.058 37.8043C223.584 38.8657 221.392 40.4907 219.658 42.5494C218.746 43.6129 217.943 44.7654 217.262 45.9897C216.642 47.0959 216.372 48.5402 215.621 49.5485C213.99 48.9346 212.481 48.122 210.778 47.6534C207.43 46.7222 203.815 46.5591 200.906 48.7152C198.471 50.5242 196.952 53.8103 197.797 56.8205C198.094 57.8881 198.88 59.632 200.078 59.7328C200.425 59.7624 200.787 59.7328 201.137 59.7328H243.155C243.864 59.7328 243.956 59.5993 244.377 59.0269C245.43 57.5916 246.146 55.9372 246.471 54.1869Z"
                            fill="#F9F9F9"></path>
                        <path
                            d="M0.236287 60.8835C-0.653428 56.3015 1.01924 51.2568 4.41498 47.9945C8.65596 43.9196 15.1212 42.2202 20.6493 44.5038C23.1232 45.5653 25.3149 47.1902 27.0493 49.249C27.9621 50.3109 28.765 51.4625 29.4456 52.6863C30.0655 53.7954 30.3353 55.2368 31.0857 56.2451C32.7168 55.6312 34.2264 54.8186 35.9287 54.35C39.2651 53.4307 42.8922 53.2498 45.8105 55.4207C48.2453 57.2297 49.7667 60.5187 48.9185 63.526C48.6219 64.5936 47.836 66.3375 46.6379 66.4413C46.2909 66.4709 45.9291 66.4413 45.5791 66.4413H3.55196C2.84315 66.4413 2.75121 66.3078 2.33008 65.7354C1.27622 64.2961 0.560539 62.6377 0.236287 60.8835Z"
                            fill="#F9F9F9"></path>
                        <path
                            d="M41.5772 185.051C40.0469 186.184 38.6678 187.545 38.7746 189.716C39.0029 194.334 46.9273 195.44 50.1689 195.882C55.9105 196.665 61.661 196.546 67.4293 196.772C88.387 197.562 109.352 197.712 130.323 197.219C141.089 196.978 151.849 196.583 162.605 196.033C172.054 195.561 182.576 196.166 191.527 192.519C196.898 190.33 202.058 187.604 207.091 184.68C211.261 182.257 216.667 179.935 219.141 175.418C223.47 167.479 208.289 165.91 204.113 165.038C187.689 161.607 170.752 160.996 154.046 160.486C137.047 159.967 119.997 160.287 103.068 162.076C81.8457 164.318 59.152 171.498 41.8174 184.876L41.5772 185.051Z"
                            fill="#E5E5E5"></path>
                        <path d="M206.957 93.6299H85.3511V176.99H206.957V93.6299Z" fill="#E5E5E5"></path>
                        <path
                            d="M206.957 93.6358H85.363L101.458 75.0318C101.826 74.6062 102.282 74.2644 102.793 74.0296C103.305 73.7948 103.861 73.6723 104.424 73.6706H221.997C222.192 73.6707 222.383 73.7269 222.548 73.8326C222.712 73.9383 222.843 74.089 222.924 74.2668C223.005 74.4445 223.034 74.6419 223.006 74.8353C222.978 75.0288 222.895 75.2102 222.768 75.3581L206.957 93.6358Z"
                            fill="white"></path>
                        <path
                            d="M207.017 93.099H87.264C86.8192 93.099 86.0481 92.9447 85.624 93.099C85.5531 93.1079 85.4813 93.1079 85.4104 93.099L85.9502 93.8493C89.9045 89.2761 93.8588 84.704 97.8131 80.1328C99.2188 78.5106 100.556 76.7935 102.057 75.2483C103.142 74.1184 104.32 74.1985 105.681 74.1985H218.26C219.301 74.1985 220.475 74.0472 221.507 74.1985C222.99 74.4179 221.617 75.7703 221.05 76.4287L207.473 92.1203L206.355 93.4133C205.762 94.0984 207.123 94.3505 207.559 93.8493L219.775 79.7265L222.593 76.4643C223.061 75.9245 223.779 75.3076 223.734 74.5306C223.657 73.0478 222.148 73.1249 221.065 73.1249H107.211C106.342 73.1249 105.468 73.0952 104.602 73.1249C102.407 73.202 101.158 74.4387 99.8416 75.9601L85.9591 92.0076L84.7491 93.4074C84.3695 93.8463 84.8825 94.1577 85.2888 94.1577H201.927C203.493 94.1577 205.122 94.2912 206.673 94.1577C206.744 94.1577 206.818 94.1577 206.886 94.1577C207.72 94.1637 207.989 93.099 207.017 93.099Z"
                            fill="#6A79A8"></path>
                        <path d="M85.5321 93.6299L55.6584 105.261V188.619L85.5321 177.42V93.6299Z" fill="#F7F7F7"></path>
                        <path
                            d="M85.277 93.443L59.1283 103.618L55.4004 105.072C54.4573 105.439 55.3411 105.665 55.9105 105.451L82.0562 95.2729L85.7841 93.8197C86.7302 93.4549 85.8464 93.2265 85.277 93.443Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M86.2468 177.227V97.4052C86.2468 97.0137 84.8144 97.2658 84.8144 97.7878V177.613C84.8144 178.002 86.2468 177.749 86.2468 177.227Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M85.5291 93.6357L55.6554 105.261L41.337 91.3225C41.0217 91.0155 40.789 90.6339 40.6606 90.213C40.5322 89.7921 40.5122 89.3455 40.6024 88.9148C40.6926 88.4841 40.8902 88.0832 41.1767 87.7492C41.4633 87.4152 41.8295 87.1591 42.2415 87.0044L66.7294 77.8107C67.1881 77.6378 67.6861 77.5976 68.1666 77.6946C68.647 77.7917 69.0904 78.022 69.446 78.3593L85.5291 93.6357Z"
                            fill="#F2F2F2"></path>
                        <path
                            d="M84.758 93.9323L55.6554 105.261L43.496 93.1909C43.18 92.884 42.9468 92.5021 42.818 92.0809C42.6892 91.6596 42.6689 91.2126 42.7592 90.7815C42.8495 90.3503 43.0473 89.949 43.3342 89.6147C43.6212 89.2805 43.988 89.0243 44.4005 88.8699L52.8706 85.7292C57.0078 84.1929 62.0139 81.0404 66.5811 82.0161C69.8553 82.716 84.4169 93.618 84.758 93.9323Z"
                            fill="white"></path>
                        <path
                            d="M85.2266 93.2621L59.0808 103.44L55.3529 104.905L56.3168 104.974L45.3436 94.2971L42.7872 91.8059C42.2148 91.2513 41.5594 90.7264 41.3429 89.9227C40.6875 87.4819 43.9053 86.8828 45.5334 86.2719L62.9451 79.7473C64.2797 79.2461 66.8006 77.7099 68.2508 78.3712C68.844 78.6381 69.3036 79.1868 69.7722 79.6287L72.2812 82.0012L84.8707 93.9323C85.2948 94.3327 86.6501 93.7662 86.1904 93.3392L74.3276 82.0962C72.9248 80.7645 71.5754 79.3262 70.0955 78.0806C68.2686 76.5414 66.3735 77.4874 64.4606 78.1873L46.1147 85.0648C44.5933 85.6342 42.8287 86.0583 41.42 86.8828C39.7681 87.8496 39.2817 89.9938 40.5303 91.4886C40.9439 91.9397 41.3804 92.3693 41.8382 92.7757L54.9763 105.57C55.2343 105.822 55.6317 105.757 55.9401 105.638L82.0859 95.4597L85.8138 94.0094C86.7539 93.6357 85.9324 92.9892 85.2266 93.2621Z"
                            fill="#6A79A8"></path>
                        <path d="M177.084 105.261H55.1008V188.622H177.084V105.261Z" fill="#F2F2F2"></path>
                        <path
                            d="M177.09 106.982C173.178 112.815 168.667 120.659 165.867 125.666C165.035 127.155 163.821 128.396 162.35 129.261C160.879 130.126 159.205 130.584 157.498 130.589L55.1038 130.814V188.619H177.09V106.982Z"
                            fill="white"></path>
                        <path d="M206.957 93.6299L177.084 105.261V188.619L206.957 177.42V93.6299Z" fill="#E5E5E5"></path>
                        <path
                            d="M206.957 121.267L197.037 125.247C195.377 125.912 193.545 126.01 191.823 125.528C190.101 125.045 188.587 124.008 187.514 122.578L177.09 108.713V188.619L206.963 177.42L206.957 121.267Z"
                            fill="#F2F2F2"></path>
                        <path
                            d="M176.366 109.013V185.701C176.366 185.997 177.798 185.781 177.798 185.318V108.63C177.798 108.334 176.366 108.55 176.366 109.013Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M54.8043 105.611H170.897C172.819 105.611 174.848 105.801 176.76 105.611C177.831 105.505 178.943 104.873 179.931 104.487L188.608 101.109L206.106 94.2882L207.23 93.8523C208.167 93.4845 207.298 93.1523 206.696 93.3866L193.271 98.6152C187.932 100.691 182.443 102.542 177.211 104.864C176.502 105.181 177.276 104.882 177.306 104.9C177.182 104.825 176.793 104.9 176.651 104.9H78.3076C70.7954 104.9 63.2447 104.677 55.7325 104.9H55.3915C54.9259 104.9 53.8493 105.599 54.7983 105.599L54.8043 105.611Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M206.231 119.592V177.474L206.64 176.993L193.084 182.076C187.766 184.069 182.318 185.87 177.09 188.07C176.473 188.334 177.244 188.118 177.048 188.091C176.819 188.078 176.589 188.078 176.36 188.091H58.0932C57.2806 188.091 56.299 187.94 55.5012 188.091C55.3878 188.101 55.2736 188.101 55.1602 188.091L55.8245 188.565V127.661C55.8245 126.908 54.3742 127.119 54.3742 127.768V188.672C54.3742 189.046 54.7183 189.147 55.0356 189.147H170.995C172.923 189.147 175.061 189.408 176.977 189.147C177.967 189.01 179.005 188.444 179.943 188.097L188.517 184.882L206.154 178.271L207.29 177.844C207.462 177.782 207.699 177.568 207.699 177.367V119.485C207.699 118.732 206.246 118.942 206.246 119.592H206.231Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M177.09 105.261H55.0979L39.0148 123.856C38.9108 123.977 38.8436 124.125 38.8212 124.282C38.7988 124.44 38.8221 124.6 38.8883 124.745C38.9545 124.89 39.0609 125.012 39.1948 125.098C39.3287 125.184 39.4844 125.23 39.6435 125.23H159.432C159.552 125.229 159.67 125.202 159.779 125.15C159.887 125.099 159.983 125.025 160.061 124.933L177.09 105.261Z"
                            fill="#F2F2F2"></path>
                        <path
                            d="M177.09 105.261H56.302C54.4454 108.853 52.6601 112.492 50.6968 116.027C49.4897 118.198 49.2584 121.745 52.7728 121.745H156.365C158.336 121.743 160.233 120.995 161.674 119.651L177.09 105.261Z"
                            fill="white"></path>
                        <path
                            d="M177.345 104.796H56.1448C55.5516 104.796 54.9852 104.727 54.4929 105.119C54.0006 105.51 53.6743 106.062 53.3066 106.495L50.7946 109.396L38.8487 123.21C37.9976 124.194 37.784 125.568 39.5101 125.695C40.1714 125.743 40.8565 125.695 41.5179 125.695H157.365C157.958 125.695 158.551 125.731 159.12 125.695C160.529 125.606 161.17 124.509 162.018 123.524L174.563 109.019L177.683 105.424C178.255 104.763 176.879 104.668 176.496 105.095C172.528 109.686 168.559 114.275 164.589 118.862C162.922 120.79 161.309 122.792 159.574 124.66C159.346 124.906 159.918 124.702 159.45 124.767C159.103 124.791 158.755 124.791 158.409 124.767H43.7451C42.6745 124.767 41.4734 124.921 40.4117 124.767C40.2901 124.749 40.0172 124.829 39.9104 124.767C38.9822 124.224 40.3879 123.112 40.7053 122.744L54.4751 106.824L55.6851 105.424L54.8369 105.721H161.754C166.677 105.721 171.689 105.994 176.606 105.721H176.82C177.434 105.724 178.311 104.796 177.345 104.796Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M206.957 93.6358L177.09 105.261L193.766 121.377C193.943 121.55 194.165 121.67 194.407 121.723C194.649 121.775 194.9 121.758 195.133 121.674L222.323 111.48C222.528 111.404 222.711 111.276 222.854 111.11C222.997 110.944 223.097 110.745 223.143 110.53C223.189 110.316 223.18 110.093 223.118 109.883C223.055 109.673 222.941 109.482 222.785 109.327L206.957 93.6358Z"
                            fill="#F2F2F2"></path>
                        <path
                            d="M205.985 94.0095L177.446 105.404L192.449 120.561C192.605 120.722 192.806 120.833 193.024 120.88C193.243 120.927 193.471 120.91 193.68 120.828L204.935 116.418L211.756 113.749C213.98 112.877 217.094 111.822 216.821 108.82C216.7 107.456 215.769 106.353 214.87 105.392C213.847 104.312 206.483 94.5255 205.985 94.0095Z"
                            fill="white"></path>
                        <path
                            d="M206.634 93.182L180.485 103.36L176.758 104.814C176.669 104.838 176.589 104.885 176.524 104.951C176.46 105.016 176.414 105.097 176.392 105.186C176.369 105.275 176.37 105.368 176.396 105.457C176.421 105.545 176.469 105.625 176.535 105.688L188.858 117.596C190.272 118.963 191.642 120.606 193.211 121.801C194.667 122.913 196.915 121.561 198.386 121.009L217.916 113.687C219.461 113.094 221.178 112.655 222.643 111.908C225.487 110.481 222.714 108.272 221.457 107.02L207.61 93.3096C207.129 92.8321 205.78 93.4341 206.302 93.9531L217.773 105.324L220.813 108.337C221.205 108.725 221.819 109.149 222.112 109.624C223.153 111.311 219.422 111.997 218.402 112.367L199.629 119.399L196.302 120.648C195.931 120.787 195.225 121.226 194.819 121.205C194.338 121.178 193.775 120.443 193.463 120.14L190.857 117.622L177.623 104.834C177.546 105.131 177.472 105.427 177.398 105.724L203.544 95.5457L207.272 94.0955C208.191 93.7247 207.39 92.8884 206.634 93.182Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M167.727 127.178V141.342C167.727 142.113 169.18 141.935 169.18 141.244V127.083C169.18 126.309 167.727 126.49 167.727 127.178Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M167.736 144.373V146.606C167.736 147.2 169.168 146.903 169.168 146.429V144.195C169.168 143.602 167.736 143.899 167.736 144.373Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M167.73 149.181V154.996C167.73 155.726 169.177 155.495 169.177 154.878V149.059C169.177 148.332 167.73 148.561 167.73 149.181Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M183.59 170.744L196.524 166.569C197.384 166.29 197.144 165.326 196.266 165.608L183.335 169.787C182.475 170.062 182.716 171.026 183.59 170.744Z"
                            fill="#D1D1D1"></path>
                        <path
                            d="M183.564 165.623L196.435 161.408C197.274 161.136 197.093 160.367 196.233 160.649L183.362 164.863C182.523 165.139 182.704 165.904 183.564 165.623Z"
                            fill="#D1D1D1"></path>
                        <path
                            d="M183.552 160.661L196.367 156.399C197.194 156.126 197.04 155.45 196.189 155.732L183.377 159.985C182.55 160.258 182.704 160.934 183.555 160.649L183.552 160.661Z"
                            fill="#D1D1D1"></path>
                        <path
                            d="M183.614 174.632L186.467 173.719C186.764 173.624 187.14 173.363 187.043 172.998C186.945 172.634 186.479 172.568 186.185 172.663L183.318 173.577C183.021 173.672 182.647 173.933 182.742 174.294C182.837 174.656 183.309 174.724 183.602 174.632H183.614Z"
                            fill="#D1D1D1"></path>
                        <path
                            d="M188.561 172.82L191.444 171.901C191.628 171.842 192.15 171.673 192.072 171.388C191.995 171.103 191.441 171.204 191.272 171.257L188.389 172.177C188.205 172.236 187.683 172.405 187.76 172.69C187.837 172.975 188.392 172.874 188.561 172.82Z"
                            fill="#D1D1D1"></path>
                        <path
                            d="M193.638 171.278L196.545 170.35C196.77 170.279 197.242 170.083 197.156 169.757C197.07 169.431 196.562 169.487 196.334 169.555L193.428 170.483C193.205 170.558 192.734 170.75 192.817 171.077C192.9 171.403 193.425 171.346 193.638 171.278Z"
                            fill="#D1D1D1"></path>
                        <path
                            d="M100.209 167.559C104.023 163.111 110.32 160.548 116.049 160.308C120.423 160.136 124.764 161.136 128.621 163.206C130.104 164 132.094 165.344 133.07 166.308C133.544 166.785 134.896 166.186 134.377 165.667C131.338 162.621 126.963 160.694 122.811 159.786C119.362 159.04 115.795 159.017 112.336 159.72C108.878 160.423 105.603 161.836 102.718 163.87C101.351 164.792 100.102 165.879 98.9994 167.105C98.4062 167.796 99.7645 168.075 100.206 167.559H100.209Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M105.118 149.412C105.393 150.628 104.735 151.865 103.635 152.176C102.535 152.487 101.434 151.749 101.155 150.53C100.877 149.311 101.538 148.077 102.638 147.766C103.739 147.455 104.839 148.205 105.118 149.412Z"
                            fill="#6A79A8"></path>
                        <path
                            d="M131.355 153.015C132.481 153.015 133.393 151.997 133.393 150.741C133.393 149.484 132.481 148.466 131.355 148.466C130.23 148.466 129.318 149.484 129.318 150.741C129.318 151.997 130.23 153.015 131.355 153.015Z"
                            fill="#6A79A8"></path>
                    </svg>

                    <div class="empty my-3">You have not purchased any packages yet
                    </div>

                    <x-form.link class="es-btn-primary" title="Buy Package" path="students/packages"></x-form.link>
                </div>
            @endforelse
        </div>

    </div>
@endsection

@section('js')
    <script>
        $('li.items').addClass('active');

        // استدعاء select2
        $('form select').select2();

        // الاستماع لتغيير الخيار في الـ select
        $('select').on('change', function() {
            // احصل على الفورم الذي يحتوي على هذا الـ select
            var form = $(this).closest('form');

            // احصل على زر addToCartBtn الموجود في الفورم
            var addToCartBtn = form.find('#addToCartBtn');

            // تحقق إذا كان هناك خيار مختار
            if ($(this).val()) {
                // إذا تم اختيار خيار صالح، قم بإزالة disabled من الزر
                addToCartBtn.prop('disabled', false);
            } else {
                // إذا لم يتم اختيار أي خيار، قم بتعطيل الزر
                addToCartBtn.prop('disabled', true);
            }
        });




        function plusStock(id) {
            var stockInput = document.getElementById("stock_" + id);
            var currentValue = parseInt(stockInput.value);
            stockInput.value = currentValue + 1;
        }

        function minusStock(id) {
            var stockInput = document.getElementById("stock_" + id);
            var currentValue = parseInt(stockInput.value);
            if (currentValue > 1) {
                stockInput.value = currentValue - 1;
            }
        }
    </script>
@endsection