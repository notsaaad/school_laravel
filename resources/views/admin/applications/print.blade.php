@extends('admin.layout')



@section('title')
    <title> {{ $application->name }} print </title>
@endsection

@section('css')

@endsection
    <script>

    </script>

@section('content')





                <table>
                  <thead>
                    <tr>
                      <th>السؤال</th>
                      <th>الاجابة</th>
                    </tr>
                  </thead>
                  <tbody>
            @foreach ($fields as $field)
                @php
                    $value = $application->applicationData->where('field_id', $field->id)->first()?->value;
                @endphp


                    <tr>
                      <td>{{$field->name}}</td>
                      <td>{{$value}}</td>
                    </tr>

{{--
                @if ($field->type == 'text')
                    <x-form.input col="col-12" label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}]" :value="$value"></x-form.input>
                @endif

                @if ($field->type == 'number')
                    <x-form.input type="number" label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}]" :value="$value"></x-form.input>
                @endif

                @if ($field->type == 'select')
                    <x-form.select label="{{ $field->name }}" :required="$field->is_required" name="fields[{{ $field->id }}]">
                        <option value="" disabled {{ !$value ? 'selected' : '' }}>Select</option>

                        @foreach (explode(',', $field->options) as $option)
                            <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                {{ $option }}</option>
                        @endforeach
                    </x-form.select>
                @endif

                @if ($field->type == 'checkbox')
                    <x-form.select col="col-12" multiple label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}][]">
                        @php
                            $values = explode(',', $value ?? '');
                        @endphp
                        @foreach (explode(',', $field->options) as $option)
                            <option value="{{ $option }}" {{ in_array(trim($option), $values) ? 'selected' : '' }}>
                                {{ $option }}</option>
                        @endforeach
                    </x-form.select>
                @endif--}}
            @endforeach

                  </tbody>
                </table>






@endsection

@section('js')

      <script>
              window.onload = function () {
                window.print();
            }
              window.onafterprint = function () {
                window.location.href = "{{ route('application.single', $application->code) }}";
              };
      </script>


@endsection
