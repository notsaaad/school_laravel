@extends('admin.layout')


@section('title')
    <title> المصاريف</title>

@endsection


@section('content')


    <div class="container">
        <div class="tableSpace">
            <table class="mb-3" id="sortable-table">

                <thead>
                    <tr>
                        <th>رقم النظام</th>
                        <th>اسم النظام</th>
                        <th>تعديل</th>
                        <th>مسح</th>

                    </tr>
                </thead>

                <tbody>

                    @forelse ($systems as $system)
                        <tr>

                            <td>{{ $system->id }}</td>
                            <td>{{ $system->name }}</td>
                            <td>
                                    <a href="{{route('admin.Expenses.viewform.eidt', $system->id)}}">تعديل</a>
                            </td>
                            <td>
                                <form action="{{route('admin.Expenses.delete.system')}}" method="post">
                                    <input type="hidden" name="id" value="{{$system->id}}">
                                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5"> لا يوجد سة نظامية مضافة </td>
                        </tr>
                    @endforelse


                </tbody>
            </table>
        </div>
    </div>

    @section('js')
        <script>


    </script>
    @endsection

@endsection
