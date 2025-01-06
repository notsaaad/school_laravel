@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الاحصائيات') }}</title>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('content')
    <canvas id="myChart"></canvas>
    @push('scripts')
        <script>
            const myChart = new Chart(
                document.getElementById('myChart'),
                config // We'll add the configuration details later.
            );
        </script>
    @endpush


    @push('scripts')
        <script>
        const data = {
            labels: @json($data->map(fn ($data) => $data->date)),
            datasets: [{
                label: 'Registered users in the last 30 days',
                backgroundColor: 'rgba(255, 99, 132, 0.3)',
                borderColor: 'rgb(255, 99, 132)',
                data: @json($data->map(fn ($data) => $data->aggregate)),
            }]
        };
        const config = {
            type: 'bar',
            data: data
        };
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
    @endpush

@endsection
