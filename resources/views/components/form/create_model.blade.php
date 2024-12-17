@props(['id', 'title', 'path', 'img', 'button'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="Label_{{ $id }}"
    aria-hidden="true">
    <div class="modal-dialog  ">
        <form class="modal-content" method="post" autocomplete="off" action="/{{ $path }}"
            @isset($img) enctype="multipart/form-data" @endisset>

            <div id="model_loader">
                <i class="fa-solid fa-spinner fa-spin"></i>
            </div>

            @csrf

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="Label_{{ $id }}">{{ $title }}</h1>

                <x-form.button type="button" icon="close" class="close" data-bs-dismiss="modal"
                    aria-label="Close"></x-form.button>

            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">

                @if (isset($button))
                    <x-form.button type="button" onclick="submit_form()" id="submitBtn"
                        title="{{ $title }}"></x-form.button>
                @else
                    <x-form.button id="submitBtn" title="{{ $title }}"></x-form.button>
                @endif

                <x-form.button class="close" type="button" data-bs-dismiss="modal"
                    title="{{ trans('words.close') }}"></x-form.button>
            </div>
        </form>
    </div>

</div>
