@extends('admin.layout')





@section('title')
    <title> {{ $title }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back back="{{auth()->user()->role}}/reports/{{ $report }}" title="{{ $title }}"></x-admin.layout.back>

    </div>


    <div class="tableSpace">
        <div id="excel-viewer"></div>
    </div>
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


    <script>
        $('li.reports').addClass('active');

        var base64 = @json($base64);
        var binary = atob(base64);
        var array = new Uint8Array(binary.length);
        for (var i = 0; i < binary.length; i++) {
            array[i] = binary.charCodeAt(i);
        }
        var workbook = XLSX.read(array, {
            type: 'array'
        });
        var html = XLSX.utils.sheet_to_html(workbook.Sheets[workbook.SheetNames[0]]);
        document.getElementById('excel-viewer').innerHTML = html;
    </script>
@endsection
