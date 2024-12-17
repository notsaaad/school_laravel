@props(["title"])
<div class="d-flex justify-content-end">
    <x-form.button icon="plus" type="button" data-bs-toggle="modal" data-bs-target="#createModel"
        title="{{$title}}"></x-form.button>
</div>
