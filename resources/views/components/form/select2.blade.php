@props(['label', 'reqiured', 'name', "col"])


<div class="group {{$col ?? 'col-lg-6 col-12'}} "  >

    <label for="{{ $name }}" class="mb-2"> {{ $label }} @isset($reqiured)
            <span class="text-danger">*</span>
        @endisset </label>


    <select multiple id="{{ $name }}"
            @class(['invalid' => $errors->has("$name") , "select-dropdown" , "w-100" , 'checkThis' => isset($reqiured) ])  name="{{ $name }}[]">
        {{$slot}}
    </select>

    @error("$name")
    <p class="invalid_message">
        <x-icons.error></x-icons.error> {{ $message }}
    </p>
    @enderror

</div>