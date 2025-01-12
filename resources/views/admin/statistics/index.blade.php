@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الاحصائيات') }}</title>
@endsection


@section('content')

    <div><a class="es-btn-primary default" href="{{route('application.char')}}">رسم بياني</a></div>

    <div class="From">
        <form action="" method="GET">
            
            <select name="year" id="">
                @foreach ($years as  $year)
                    <option value="{{$year->id}}" @if($year_id == $year->id)
                        selected
                    @endif>{{$year->name}}</option>
                @endforeach
            </select>
            <input type="submit" id="submitfrom1" value="filter">
        </form>

        <style>
            .From{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            }

            form{
                width: 50%;
                padding: 0 20px;
                background-color: white;
                height: 200px;
                border-radius: 8px;
                display: flex;
                flex-direction: column;
                justify-content: space-evenly;
            }
            #submitfrom1{

            }
        </style>

    </div>
    <table>
        <thead>
            <tr>
                <th>Stage</th>
                <th>Application</th>
                <th>Assessment fees</th>
                <th>Assessment Date</th>
                <th>Enrolled</th>
                <th>Parents Interview</th>
                <th>over due date</th>
                <th>wating MR</th>
                <th>cancel Application With Refund</th>
                <th>cancel Application With No Refund</th>
                <th>Didn't Finish</th>
                <th>Waiting List</th>
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
                <td>{{$stage['Parent_interview']}}</td>
                <td>{{$stage['wating_mr']}}</td>
                <td>{{$stage['Cancel_with_refund']}}</td>
                <td>{{$stage['Cancel_with_no_refund']}}</td>
                <td>{{$stage['DidnotFinish']}}</td>
                <td>{{$stage['watting_list']}}</td>
                <td>{{$stage['watting_list']}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">{{ trans('words.no_data') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
