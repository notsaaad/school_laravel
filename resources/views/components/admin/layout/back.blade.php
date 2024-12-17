@props(['title', 'back'])

<div class="d-flex align-items-center">

    @if (isset($back))
        <a class="back"  href="/{{ $back }}">
            <svg style="height: 1rem;
        width: 1rem;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="black" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z"
                    clip-rule="evenodd"></path>
            </svg>
        </a>
    @endif


    <h1 class="contnet-title">{{ $title }}  {{$slot}}</h1>

</div>
