<form action="/admin/students/{{ $user->id }}/updateOther" method="post">

    @csrf
    @method('put')


    <div class="row form_style mb-4">





        <x-form.input col="col-lg-4 col-6" label="اسم ولي الامر" name="kinship_name"
            value="{{ optional($user->details)->kinship_name }}"></x-form.input>


        <x-form.select col="col-lg-4 col-6" label="صلة القرابة" name="kinship_id">
            <option selected disabled value="">Select</option>


            @foreach ($kinships as $kinship)
                <option @selected(optional($user->details)->kinship_id == $kinship->id) value="{{ $kinship->id }}">{{ $kinship->ar_name }}</option>
            @endforeach

        </x-form.select>


        <x-form.input col="col-lg-4 col-6" label=" ايميل والي الامر " type="email" name="kinship_email"
            value="{{ optional($user->details)->kinship_email }}"></x-form.input>



        <x-form.input col="col-lg-3 col-6" label=" موبيل والي الامر "
            value="{{ optional($user->details)->kinship_phone }}" name="kinship_phone" placeholder="010********"
            type="tel" maxlength="11" pattern="[0-9]{11}" title="يجب أن يكون الرقم مكونًا من 11 رقمًا"
            inputmode="numeric"></x-form.input>



        <x-form.input col="col-lg-3 col-6" label=" الرقم القومي لولي الامر" maxlength="14" size="14"
            name="kinship_national_id" value="{{ optional($user->details)->kinship_national_id }}" pattern="[0-9]{14}"
            title="يجب أن يكون الرقم مكونًا من 14 رقمًا" inputmode="numeric"></x-form.input>





        <x-form.select col="col-lg-3 col-6" label="وظيفة والي الامر" name="kinship_job_id">
            <option selected disabled value="">Select</option>


            @foreach ($jobs as $job)
                <option @selected(optional($user->details)->kinship_job_id == $job->id) value="{{ $job->id }}">{{ $job->ar_name }}</option>
            @endforeach

        </x-form.select>




    </div>

    <div class="actions border-0">
        <x-admin.layout.back title="الوصية"></x-admin.layout.back>
    </div>


    <div class="row form_style  mb-4">


        <x-form.input col="col-lg-2 col-6" label="نوع الوصية " name="kinship_type"
            value="{{ optional($user->details)->kinship_type }}"></x-form.input>


        <x-form.input col="col-lg-5 col-6" label="سبب الوصية " name="kinship_reason"
            value="{{ optional($user->details)->kinship_reason }}"></x-form.input>

        <x-form.input col="col-lg-5 col-6" label="ملاحظات الوصية " name="kinship_notes"
            value="{{ optional($user->details)->kinship_notes }}"></x-form.input>
    </div>


    <div class="actions border-0">
        <x-admin.layout.back title="المقربين"></x-admin.layout.back>
    </div>


    <div class="row form_style  mb-4">




        <x-form.input col="col-lg-4 col-6" label=" اقرب المقربين " name="kinship2_name"
            value="{{ optional($user->details)->kinship2_name }}"></x-form.input>



        <x-form.input col="col-lg-4 col-6" label=" موبيل اقرب المقربين "
            value="{{ optional($user->details)->kinship2_phone }}" name="kinship2_phone" placeholder="010********"
            type="tel" maxlength="11" pattern="[0-9]{11}" title="يجب أن يكون الرقم مكونًا من 11 رقمًا"
            inputmode="numeric"></x-form.input>




        <x-form.select col="col-lg-4 col-6" label="صلة اقرب المقربين" name="kinship2_id">
            <option selected disabled value="">Select</option>


            @foreach ($kinships as $kinship)
                <option @selected(optional($user->details)->kinship2_id == $kinship->id) value="{{ $kinship->id }}">{{ $kinship->ar_name }}</option>
            @endforeach

        </x-form.select>

    </div>


    <div class="col-12 mt-3">
        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>



    </div>
</form>
