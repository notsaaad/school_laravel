@props(['path', 'icon', 'title', 'class', 'flip'])

<li @isset($flip)
id="flip-{{ $flip }}"
@endisset class="{{ $class }} "
    {{ $attributes->merge() }}>
    <a href="{{ url("$path") }}"> {{ $icon }} <span class="aside_li_title">{{ $title }}</span> </a>

    @if (isset($flip))
        <span class="menu-arrow {{ $flip }}"></span>
    @endif
</li>
