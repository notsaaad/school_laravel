@extends('admin.layout')

@section('title')
    <title>اختر الطالب</title>
@endsection

@section('css')
  <style>
    #content{
      height: calc(90vh - 80px);
    }
    .container{
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .box{
      /* background-color: var(--mainBg); */
      color: var(----mainColor);
      font-size: 18px;
      font-weight: bold;
      height: 20vh;
    }
  </style>
@endsection

@section('content')


  <div class="container">
    <div class="row gx-2 gy-2">
      <div class="col-sm-12 col-md-6">
        <a  href="{{ route('admin.student.index', ['type' => 'international']) }}" class="box p-5  border rounded shadow d-flex justify-content-center align-items-center">
          الطلاب الانترناشونال
        </a>
      </div>
      <div class="col-sm-12 col-md-6">
        <a href="{{ route('admin.student.index', ['type' => 'national']) }}" class="box p-5 border rounded shadow d-flex justify-content-center align-items-center">
          الطلاب الناشونال
        </a>
      </div>

    </div>
  </div>

@endsection


@section('js')

@endsection
