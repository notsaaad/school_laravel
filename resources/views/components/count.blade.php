@props(['title', 'count', 'color', 'notClick' => false, 'custom'])

@php
    if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator') {
        $type = 'users/' . getCountry()['code'];
    } elseif (auth()->user()->role == 'trader') {
        $type = 'trader';
    } elseif (auth()->user()->role == 'admin') {
        $type = 'admin';
    }
@endphp
<div class="item">


    @if ($notClick == false)
        <a href="/{{ $type }}/orders/search?status={{ $title }}&withModerators=yes" style="color:#626262;">
    @endif


    <div class="count">
        <div class="first">
            <div class="title">{{ $title }}</div>
            <div class="number" {{ $attributes->merge() }}>{{ $count }}</div>
        </div>

        <div class="icone" style="background-color:{{ $color }}">
            {{ $slot }}
        </div>
    </div>

    @if ($notClick == false)
        </a>
    @endif
</div>
