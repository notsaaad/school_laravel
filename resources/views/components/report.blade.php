@props(['icon', 'title', 'dis', 'path', 'type'])

<div class="tool">
    <div class="tool_icon">
        <img src="{{ asset("assets/admin/icons/$icon") }}" alt={{ $title }}>
    </div>
    <div class="title">{{ $title }}</div>

    <div class="dis">{{ $dis }}</div>

    @if (isset($type))
        <x-form.link class="es-btn-primary" title="انشاء تقرير"
            path="{{ $type }}/reports/{{ $path }}"></x-form.link>
    @else
        <x-form.link class="es-btn-primary" title="انشاء تقرير" path="admin/reports/{{ $path }}"></x-form.link>
    @endif


</div>
