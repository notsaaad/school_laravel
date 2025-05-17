@extends('admin.layout')



@section('title')
    <title> {{ $application->name }} print </title>
@endsection

@section('css')

@endsection
    <script>

    </script>

@section('content')



  <x-admin.layout.back back="admin/applications/{{$application->code}}"  title="العودة الي الطلب"></x-admin.layout.back>

    <div class="container mt-2">
      <form action="{{ route('application.subject.postedit', [$code, $id]) }}" method="post">
        @csrf
        <x-form.input value="{{ old('result', $subject->result) }}" label="درجة الطالب" name="result" ></x-form.input>
        <x-form.input value="{{ old('retake_data', $subject->retake_data) }}" label="تاريخ الفاتورة" name="retake_data" class="date"></x-form.input>
        <button class="es-btn-primary" type="submit">تحديث</button>
      </form>
    </div>




@endsection

@section('js')

    <script>
              flatpickr('input.date', {
            enableTime: false,
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                checkUseDate();
            }
        });

        flatpickr('input.searchDate', {
            enableTime: false,
            mode: 'range',
            dateFormat: "Y-m-d",
        });

        document.querySelector('input.date').classList.add('checkThis');
    </script>



@endsection
