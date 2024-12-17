@extends('users.layout')

@section('title')
    <title>{{ $page->title }} </title>
    <style>
        .page * {
            font-family: cairo !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <h1 style="font-size: 22px ; color: #4F4F4F">{{ $page->title }}</h1>
        <div class="page">
            <?= $page->body ?>
        </div>

    </div>
@endsection
