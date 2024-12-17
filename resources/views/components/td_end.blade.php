@props(['name', 'id', 'normal'])

<td>
    <div class="{{ isset($normal) ? 'd-flex justify-content-center gap-1 ' : 'end' }}">
        {{ $slot }}

        @if (auth()->user()->role != 'trader')
            <div data-name="{{ $name }}" data-id="{{ $id }}" data-bs-target="#deleteModel" type="button"
                data-bs-toggle="modal" onclick="delete_model(this)" data-tippy-content="{{ trans('words.delete') }}"
                class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon "></i>
            </div>
        @endif
    </div>
</td>
