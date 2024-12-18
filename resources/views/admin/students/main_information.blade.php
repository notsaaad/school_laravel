<form class="row form_style" action="/admin/students/{{ $user->id }}" method="post">

    @csrf
    @method('put')


    <x-form.input col="col-lg-3 col-6" required label="{{ trans('words.name') }}" name="name"
        value="{{ $user->name }}"></x-form.input>


    <x-form.input col="col-lg-3 col-6" required label="{{ trans('words.كود الطالب') }}" name="code"
        value="{{ $user->code }}"></x-form.input>


    <x-form.select col="col-lg-3 col-6" reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

        <option selected disabled value="">Select</option>


        @foreach ($stages as $stage)
            <option @selected($stage->id == $user->stage_id) value="{{ $stage->id }}">{{ $stage->name }}</option>
        @endforeach


    </x-form.select>

    <x-form.select col="col-lg-3 col-6" name="study_type" label="نوع الدراسة">

        <option selected disabled value="">Select</option>

        <option @selected(optional($user->details)->study_type == 'national') value="national">National</option>
        <option @selected(optional($user->details)->study_type == 'international') value="international">International</option>
    </x-form.select>


    <x-form.input col="col-lg-3 col-6" type="email" label="{{ trans('words.email') }}" name="email"
        value="{{ $user->email }}"></x-form.input>


    <x-form.input col="col-lg-3 col-6" label="{{ trans('words.phone') }}" value="{{ $user->mobile }}" name="mobile"
        placeholder="010********" type="tel" maxlength="11" pattern="[0-9]{11}"
        title="يجب أن يكون الرقم مكونًا من 11 رقمًا" inputmode="numeric"></x-form.input>




    <x-form.select col="col-lg-3 col-6" required name="gender" label="{{ trans('words.النوع') }}">

        <option @selected($user->gender == 'boy') value="boy">{{ trans('words.ذكر') }}</option>
        <option @selected($user->gender == 'girl') value="girl">{{ trans('words.انثي') }}</option>

    </x-form.select>



    <x-form.input col="col-lg-3 col-6" class="date" label="تاريخ الالتحاق" name="join_date"
        value="{{ optional($user->details)->join_date }}"></x-form.input>



    <x-form.input col="col-lg-3 col-6" class="date" label="تاريخ الميلاد" name="birth_date"
        value="{{ optional($user->details)->birth_date }}"></x-form.input>


    <x-form.input col="col-lg-3 col-6" label="الرقم القومي" maxlength="14" size="14" name="national_id"
        value="{{ optional($user->details)->national_id }}" pattern="[0-9]{14}"
        title="يجب أن يكون الرقم مكونًا من 14 رقمًا" inputmode="numeric"></x-form.input>


    <x-form.select col="col-lg-3 col-6" label="محافظة الميلاد" name="region_id">
        <option selected disabled value="">Select</option>


        @foreach ($regions as $region)
            <option @selected(optional($user->details)->region_id == $region->id) value="{{ $region->id }}">{{ $region->name }}</option>
        @endforeach

    </x-form.select>


    <x-form.select col="col-lg-3 col-6" label="الجنسية" name="nationality_id">
        <option selected disabled value="">Select</option>


        @foreach ($nationalities as $nationality)
            <option @selected(optional($user->details)->nationality_id == $nationality->id) value="{{ $nationality->id }}">{{ $nationality->ar_name }}</option>
        @endforeach

    </x-form.select>







    <x-form.select col="col-lg-3 col-6" label="الديانة" name="religion">
        <option selected disabled value="">Select</option>

        <option @selected(optional($user->details)->religion == 'مسلم') value="مسلم">مسلم</option>
        <option @selected(optional($user->details)->religion == 'مسيحي') value="مسيحي">مسيحي</option>
        <option @selected(optional($user->details)->religion == 'اخر') value="اخر">آخر</option>
    </x-form.select>




    <div class="col-lg-3 col-6 group ">
        <label for="active" class="mb-2"> {{ trans('words.account_status') }}<span class="text-danger">*</span>
        </label>
        <select name="active" class="modelSelect">
            <option @selected($user->active == '1') value="1">نشط</option>
            <option @selected($user->active == '0') value="0">غير نشط</option>
        </select>
    </div>



    <div class="col-lg-3 col-6 group ">
        <label for="own_package" class="mb-2">هل لديه باكدج<span class="text-danger">*</span>
        </label>
        <select name="own_package" class="modelSelect">
            <option @selected($user->own_package == 'yes') value="yes">نعم</option>
            <option @selected($user->own_package == 'no') value="no">لا</option>
        </select>
    </div>



    <div class="col-12 mt-3">
        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>



    </div>
</form>
