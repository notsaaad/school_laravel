<form class="row form_style" action="/admin/students/{{ $user->id }}/updateOther" method="post">

    @csrf
    @method('put')

    @php
        $fullName = explode(' ', $user->name);
        $fatherName = implode(' ', array_slice($fullName, 1));
    @endphp


    <div class="col-12 check_group mb-3">
        <label for="father_alive"> الوالد علي قيد الحياة </label>
        <input type="checkbox" name="father_alive" id="father_alive" value="1" @checked(optional($user->details)->father_alive ? true : false)>
    </div>

    <x-form.input col="col-lg-3 col-6" disabled label="اسم الوالد" name="name"
        value="{{ $fatherName }}"></x-form.input>



    <x-form.input col="col-lg-3 col-6" label="موبيل الوالد" value="{{ optional($user->details)->father_phone }}"
        name="father_phone" placeholder="010********" type="tel" maxlength="11" pattern="[0-9]{11}"
        title="يجب أن يكون الرقم مكونًا من 11 رقمًا" inputmode="numeric"></x-form.input>



    <x-form.input col="col-lg-3 col-6" label="واتساب الوالد"
        value="{{ optional($user->details)->father_whatsapp ?? optional($user->details)->father_phone }}"
        name="father_whatsapp" placeholder="010********" type="tel" maxlength="11" pattern="[0-9]{11}"
        title="يجب أن يكون الرقم مكونًا من 11 رقمًا" inputmode="numeric"></x-form.input>





    <x-form.input col="col-lg-3 col-6" label="الرقم القومي للوالد" maxlength="14" size="14"
        name="father_national_id" value="{{ optional($user->details)->father_national_id }}" pattern="[0-9]{14}"
        title="يجب أن يكون الرقم مكونًا من 14 رقمًا" inputmode="numeric"></x-form.input>







    <x-form.select col="col-lg-3 col-6" label="جنسية الوالد" name="father_nationality_id">
        <option selected disabled value="">Select</option>


        @foreach ($nationalities as $nationality)
            <option @selected(optional($user->details)->father_nationality_id == $nationality->id) value="{{ $nationality->id }}">{{ $nationality->ar_name }}</option>
        @endforeach

    </x-form.select>

    <x-form.select col="col-lg-3 col-6" label="وظيفة الوالد" name="father_job_id">
        <option selected disabled value="">Select</option>


        @foreach ($jobs as $job)
            <option @selected(optional($user->details)->father_job_id == $job->id) value="{{ $job->id }}">{{ $job->ar_name }}</option>
        @endforeach

    </x-form.select>
    <x-form.input col="col-lg-3 col-6" label="جهة عمل الاب" name="father_workplace"
        value="{{ optional($user->details)->father_workplace }}"></x-form.input>



    <x-form.select col="col-lg-3 col-6" label="مؤهل الوالد" name="father_qualification_id">
        <option selected disabled value="">Select</option>


        @foreach ($qualifications as $qualification)
            <option @selected(optional($user->details)->father_qualification_id == $qualification->id) value="{{ $qualification->id }}">{{ $qualification->ar_name }}
            </option>
        @endforeach

    </x-form.select>


    <hr>



    <div class="col-12 check_group mb-3">
        <label for="mother_alive"> الام علي قيد الحياة </label>
        <input type="checkbox" name="mother_alive" id="mother_alive" value="1" @checked(optional($user->details)->mother_alive ? true : false)>
    </div>


    <x-form.input col="col-lg-3 col-6" label="اسم الام" name="mother_name"
        value="{{ optional($user->details)->mother_name }}"></x-form.input>



    <x-form.input col="col-lg-3 col-6" label="موبيل الام" value="{{ optional($user->details)->mother_phone }}"
        name="mother_phone" placeholder="010********" type="tel" maxlength="11" pattern="[0-9]{11}"
        title="يجب أن يكون الرقم مكونًا من 11 رقمًا" inputmode="numeric"></x-form.input>




    <x-form.input col="col-lg-3 col-6" label="واتساب الام"
        value="{{ optional($user->details)->mother_whatsapp ?? optional($user->details)->mother_phone }}"
        name="mother_whatsapp" placeholder="010********" type="tel" maxlength="11" pattern="[0-9]{11}"
        title="يجب أن يكون الرقم مكونًا من 11 رقمًا" inputmode="numeric"></x-form.input>





    <x-form.input col="col-lg-3 col-6" label="الرقم القومي للوالدة" maxlength="14" size="14"
        name="mother_national_id" value="{{ optional($user->details)->mother_national_id }}" pattern="[0-9]{14}"
        title="يجب أن يكون الرقم مكونًا من 14 رقمًا" inputmode="numeric"></x-form.input>




    <x-form.select col="col-lg-3 col-6" label="جنسية الام" name="mother_nationality_id">
        <option selected disabled value="">Select</option>


        @foreach ($nationalities as $nationality)
            <option @selected(optional($user->details)->mother_nationality_id == $nationality->id) value="{{ $nationality->id }}">{{ $nationality->ar_name }}</option>
        @endforeach

    </x-form.select>

    <x-form.select col="col-lg-3 col-6" label="وظيفة الام" name="mother_job_id">
        <option selected disabled value="">Select</option>


        @foreach ($jobs as $job)
            <option @selected(optional($user->details)->mother_job_id == $job->id) value="{{ $job->id }}">{{ $job->ar_name }}</option>
        @endforeach

    </x-form.select>
    <x-form.input col="col-lg-3 col-6" label="جهة عمل الام" name="mother_workplace"
        value="{{ optional($user->details)->mother_workplace }}"></x-form.input>



    <x-form.select col="col-lg-3 col-6" label="مؤهل الام" name="mother_qualification_id">
        <option selected disabled value="">Select</option>


        @foreach ($qualifications as $qualification)
            <option @selected(optional($user->details)->mother_qualification_id == $qualification->id) value="{{ $qualification->id }}">{{ $qualification->ar_name }}
            </option>
        @endforeach

    </x-form.select>





    <div class="col-12 mt-3">
        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>



    </div>
</form>
