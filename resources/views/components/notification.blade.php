@props(['notification', 'customMessage'])
<div class="form-element inlined switch intable my-2  p-0">
    <div class="flex  align-items-center">
        <label class="d-flex gap-3">

            <div class="form-check form-switch p-0">

                <input value="{{ $notification }}" @checked(ON($notification)) class="form-check-input" type="checkbox"
                    name="notification[]" id="{{ $notification }}">

            </div>


            @if (!isset($customMessage))
                <label class="m-0 " for="{{ $notification }}">اشعارات طلبات {{ $notification }} </label>
            @else
                <label class="m-0 " for="{{ $notification }}"> {{ $customMessage }} </label>
            @endif


        </label>
    </div>
</div>
