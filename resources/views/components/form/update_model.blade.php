@props(["path"])

<div class="modal fade" id="updateModel" tabindex="-1" aria-labelledby="Label_updateModel" aria-hidden="true">
    <div class="modal-dialog">
        <form  class="modal-content" method="post" autocomplete="off" action="/{{$path}}" >
            @csrf
            @method("put")
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="Label_updateModel">{{ trans('words.btn_update') }}</h1>

            <x-form.button type="button" icon="close" class="close" data-bs-dismiss="modal" aria-label="Close"></x-form.button>

        </div>
        <div class="modal-body mt-1 row " style="padding: 10px 5px">
            {{$slot}}
        </div>
        <div class="modal-footer">
            <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>
            <x-form.button class="close" type="button" data-bs-dismiss="modal" title="{{ trans('words.close') }}"></x-form.button>
        </div>
        </form>
    </div>
</div>

