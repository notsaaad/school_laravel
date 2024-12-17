@extends('users.layout')



@section('title')
    <title> Uniform </title>
@endsection


@section('content')
    <div class="content container mt-3">
        <div class="sliderHeader w-100 px-1">

            <div class="d-flex">
                <h2 class="slide_h2"> All Packages </h2> <span class="result">(
                    {{ count($packages) }} result )
                </span>
            </div>
        </div>

        <div class="row mt-2 g-3">
            @foreach ($packages as $package)
                <div class="col-lg-3 col-12">
                    <div class="product">
                        <a href="/students/packages/{{ $package->id }}">
                            <div class="product_content">
                                <img src="{{ path($package->img) }}" alt="{{ $package->name }}">
                                <div class="product_name">
                                    <p> {{ $package->name }} </p>
                                </div>

                                <div class="product_name">
                                    <p> {{ $package->price }} EGP </p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('li.uniform').addClass('active');
    </script>
@endsection
