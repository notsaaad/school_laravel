@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الاحصائيات') }}</title>
@endsection


@section('content')

    <table>
        <thead>
            <tr>
                <th>Stage</th>
                <th>Application</th>
                <th>Assessment fees</th>
                <th>Assessment Date</th>
                <th>Enrolled</th>
                <th>Cancled</th>
            </tr>
        </thead>
        <tbody class="clickable">
            @forelse ( $All as $stage )
            <tr>
                <td>{{$stage['stage_name']}}</td>
                <td>{{$stage['applications_counter']}}</td>
                <td>{{$stage['applications_Fees_counter']}}</td>
                <td>0</td>
                <td>{{$stage['applications_Enrolled_Counter']}}</td>
                <td>{{$stage['applications_Canceld_Counter']}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">{{ trans('words.no_data') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
