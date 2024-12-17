<form class="row form_style" action="/admin/students/{{ $user->id }}/updateOther" method="post">

    @csrf
    @method('put')

    <x-form.select col="col-12" label="نوع العاقة" name="disability_id">
        <option selected disabled value="">Select</option>


        @foreach ($disabilities as $disability)
            <option @selected(optional($user->details)->disability_id == $disability->id) value="{{ $disability->id }}">{{ $disability->ar_name }}</option>
        @endforeach

    </x-form.select>




    <x-form.textarea col="col-12"  rows="5" label="ملاحظات الدمج" name="disability_notes"
        value="{{ optional($user->details)->disability_notes }}"></x-form.textarea>



    <div class="col-12 mt-3">
        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>
    </div>
</form>
