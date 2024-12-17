@if (session('error'))
    <script>
        Swal.fire({
            title: '{{trans("messages.error")}} !',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: '{{trans("messages.ok")}}'
        })
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            title: '{{trans("messages.done")}}',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: '{{trans("messages.ok")}}'
        })
    </script>
@endif

@if (session('error_tostar'))
    <script>
        toastr["error"]("{{ session('error_tostar') }}");
    </script>
@endif

@if (session('success_tostar'))
    <script>
        toastr["success"]("{{ session('success_tostar') }}");
    </script>
@endif


@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            Swal.fire({
                title: '{{trans("messages.error")}}',
                text: "{{ $error }}",
                icon: 'error',
                confirmButtonText: '{{trans("messages.ok")}}'
            })
        </script>
    @break
@endforeach

@endif
