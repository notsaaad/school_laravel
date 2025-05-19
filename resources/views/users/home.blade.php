@extends('users.layout')



@section('title')
    <title> Home </title>
@endsection


@section('css')
    <style>
        header {
            box-shadow: 0 5px 5px #00000012 !important;
            padding-bottom: 12px;
        }

        .content {
            text-align: center;
            min-height: calc(100vh - 150px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        h1 {
            color: var(--secondBg);
            font-weight: 700;
            margin-bottom: 25px;
        }

        .section {
            background: #F9FBFF;
            padding: 20px 10px;
            border-radius: 15px;
            cursor: pointer;
        }

        .section img {
            width: 90%;
            height: 200px;
            object-fit: contain;
        }

        .section .section_title {
            color: var(--mainColor);
            font-weight: 700;
            margin-top: 20px;
            margin-bottom: 0px;
        }
    </style>
@endsection


@section('content')
    <div class="content container mt-3">
        <div class="sections row g-4">
            <div class="col-lg-3 col-6">
                <div class="section">
                    <a href="/students/packages">
                        <img src="/assets/users/imgs/shirt.png">
                        <p class="section_title">Uniform</p>
                    </a>

                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="section">
                    <a href="/students/pending_payments"> <img src="/assets/users/imgs/online-payment.png">

                        <p class="section_title">Pending Payments </p>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="section">
                    <a href="/students/Buses"> <img src="/assets/users/imgs/bus.png">

                        <p class="section_title">Buses</p>
                    </a>
                </div>
            </div>


            {{-- <div class="col-lg-3 col-6">
                <div class="section">
                    <img src="/assets/users/imgs/event.svg">

                    <p class="section_title">Events</p>

                </div>
            </div> --}}
            {{--
            <div class="col-lg-3 col-6">
                <div class="section">
                    <img src="/assets/users/imgs/soon.svg">

                    <p class="section_title">SOON</p>

                </div>
            </div> --}}
        </div>
    </div>
@endsection
