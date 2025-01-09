@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الاحصائيات') }}</title>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('content')
    <div class="CharHolder">
        <canvas width="50%" id="myChart"></canvas>
    </div>

    <style>
        .CharHolder{
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>


    <script type="text/javascript">
            var labels =  {{ Js::from($labels) }};
            var users =  {{ Js::from($data) }};
            var datasets =  {{ Js::from($datasets) }};


            const data = {
            labels: labels,

            datasets: datasets
            };

            const config = {
            type: 'bar',
            data: data,
            options: {}
            };

            const myChart = new Chart(
            document.getElementById('myChart'),
            config
            );

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
