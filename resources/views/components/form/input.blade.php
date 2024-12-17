@props(['label', 'required' => null, 'name', 'placeHolder', 'code' => null, 'col'])

<div class="group {{ $col ?? 'col-lg-6 col-12' }}">

    @isset($label)
        <label for="{{ $code ? $code . '_' . $name : $name }}" class="mb-2"> {{ $label }} @if ($required)
                <span class="text-danger">*</span>
            @endif   {{$slot}}</label>

        @endif


        <input  @if (isset($code) && $code != 'ar') dir="ltr" @endif {{ $attributes->merge() }}
            value="{{ old($code ? "{$code}.{$name}" : $name) }}"
            {{ $attributes->class(['checkThis' => $required, 'invalid' => $errors->has($code ? "{$code}.{$name}" : $name)]) }}
            type="text" id="{{ $code ? $code . '_' . $name : $name }}"
            name="{{ $code ? $code . '[' . $name . ']' : $name }}" placeholder="{{ $placeHolder ?? '' }}">

        @error($code ? "{$code}.{$name}" : $name)
            <p class="invalid_message">
                <x-icons.error></x-icons.error> {{ $message }}
            </p>
        @enderror

    </div>
