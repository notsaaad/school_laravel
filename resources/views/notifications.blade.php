@php
    $userType = auth()->user()->role == 'user' ? 'users' : 'admin';
@endphp

@extends($userType . '/layout')


@section('title')
    <title> الاشعارات </title>
@endsection


@section('content')
    @php
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        $path = $parsedUrl['path'] ?? 'admin/home';
        $path = ltrim($path, '/'); // Remove leading slash
    @endphp

    <div class="actions border-0">
        <x-admin.layout.back :back="$path" title=" {{ trans('words.allNoti') }}"></x-admin.layout.back>
    </div>


    <div>

        <div class="tableSpace" id="inventor">
            <table id="example" class="not">
                <thead>

                    <tr>

                        <th> المحتوي </th>

                        <th>التاريخ</th>
                        <th> من </th>

                    </tr>

                </thead>

                <tbody>
                    @foreach (auth()->user()->notifications()->orderBy('created_at', 'desc')->get() as $notification)
                        <tr data-link="{{ $notification->data['content'][getLocale()]['link'] }}"
                            onclick="read2(this , '{{ $notification->id }}')" style="cursor: pointer">



                            <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif data-text='المحتوي'>
                                {{ $notification->data['content'][getLocale()]['message'] }} </td>




                            <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif data-text='الوقت'>
                                {{ fixData($notification->created_at) }} </td>

                            <td>{{ $notification->created_at->diffForHumans() }}</td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>
@endsection


@section('js')
    <script>
        function read2(e, notificationId, type = "users") {
            let link = $(e).attr("data-link");

            $.ajax({
                type: "get",
                url: `mark-notification-as-read/` + notificationId, // Replace with your actual route.

                success: function(response) {
                    window.location.href = `${link}`;
                },
                error: function(xhr) {
                    console.error("Error marking notification as read");
                },
            });
        }
    </script>

    <script>
        var table = $('#example').DataTable({ // Corrected DataTable function
            columnDefs: [{
                type: 'datetime-moment',
                targets: [1]
            }], // Apply custom sorting to the second column
            order: [
                [1, 'desc'] // Sort by the second column in descending order
            ],

            "language": {
                "paginate": {
                    "first": "الاول",
                    "last": "الاخير",
                    "next": "التالي",
                    "previous": "السابق"
                },
                info: '',
                infoEmpty: '',
                zeroRecords: 'لا يوجد  اشعارات ',
                infoFiltered: "",
                search: "",
                "searchPlaceholder": "ابحث في الاشعارات",
                sLengthMenu: "عرض _MENU_"
            }
        });

        $('.paginate_button.next', table.table().container()).addClass('xbutton');
    </script>
@endsection
