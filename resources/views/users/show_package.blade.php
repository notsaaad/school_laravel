@extends('users.layout')



@section('title')
    <title> {{ $package->name }} </title>
@endsection


@section('content')
    <div class="content container mt-3">
        <div class="sliderHeader w-100 px-1">



            <div class="d-flex">
                <h2 class="slide_h2"> {{ $package->name }} </h2> <span class="result">(
                    {{ count($products) }} product )
                </span>
            </div>


            <button onclick="buy_package()" class="bay_btn">buy This Package <svg width="22"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                    </path>
                </svg></button>

        </div>

        <div class="row mt-2 g-3 mb-4">
            @foreach ($products as $product)
                <div class="col-lg-3 col-12">
                    <div class="product">
                        <div class="product_content">
                            <img src="{{ path($product->img) }}" alt="{{ $product->name }}">
                            <div class="product_name">
                                <p> {{ $product->name }} </p>
                            </div>
                            <div class="sizes">
                                <div class="title">select size:</div>
                                <div class="values">

                                    @foreach ($product->variants as $variant)
                                        <div onclick="select(this)" id="{{ $variant->id }}" class="option">
                                            {{ $variant->value }}
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection



@section('js')
    <script>
        $('li.uniform').addClass('active');

        function select(e) {
            $(e).addClass("selected").siblings().removeClass("selected");
        }



        function buy_package() {
            let allSelected = true;

            $(".product").each(function() {
                if ($(this).find(".values .option.selected").length === 0) {
                    allSelected = false;
                    Swal.fire({
                        title: "warning",
                        text: "Please select an option for each product before proceeding with the order.",
                        icon: "warning",
                    });
                    return false;
                }
            });




            if (allSelected) {

                Swal.fire({
                    title: "Buy This Package ?",
                    html: `
                        <div style="text-align: left;">
                            <p>Are you sure to complete your purchase?</p>
                            <p><strong>Package Price:</strong> {{ $package->price }} EGP</p>
                            <p><strong>Service Fee:</strong>     @if (settings('service_expenses') != 0)
                                        {{ (settings('service_expenses') * $package->price) / 100 }} EGP
                                    @else
                                        Free
                                    @endif  </p>
                            <p><strong>Total:</strong> {{ (settings('service_expenses') * $package->price) / 100 + $package->price }} EGP </p>
                        </div>
                    `,
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: "Yes, Buy For  {{ (settings('service_expenses') * $package->price) / 100 + $package->price }} EGP",
                    icon: "info",
                    denyButtonText: `Cancel`
                }).then((result) => {
                    if (result.isConfirmed) {
                        work();
                    }
                });

            }


        }

        function work() {
            let ids = getIds();

            let csrfToken = $('meta[name="csrf-token"]').attr('content');


            $.ajax({
                url: `/students/storeOrder/{{ $package->id }}`,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Set the CSRF token in the header
                },
                data: {
                    data: ids,
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == "success") {

                        Swal.fire({
                            title: "{{ trans('messages.done') }}",
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: '{{ trans('messages.ok') }}'
                        }).then(function() {
                            window.location.href = '/students/orders';
                        });





                    } else if (response.status == "ValidationError") {

                        Swal.fire({
                            title: '{{ trans('messages.error') }} !',
                            text: Object.values(response.Errors)[0][0],
                            icon: 'error',
                            confirmButtonText: '{{ trans('messages.ok') }}'
                        })

                    } else if (response.status == "CoustomErrors") {
                        Swal.fire({
                            title: '{{ trans('messages.error') }} !',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: '{{ trans('messages.ok') }}'
                        })
                    }

                },
            });
        }
    </script>
@endsection
