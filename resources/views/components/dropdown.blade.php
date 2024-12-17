@props(['title'])
<div class="dropdown">
    <button class="asSearch dropdown-toggle" style="background: var(--mainBg);
    color: white"
        type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $title }} <x-icons.mark></x-icons.mark>
    </button>
    <ul class="dropdown-menu">
        {{ $slot }}
    </ul>
</div>
