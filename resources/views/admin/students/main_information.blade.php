<form class="row form_style" action="/admin/students/{{ $user->id }}" method="post" enctype="multipart/form-data">

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

        {{-- <x-form.input col="col-lg-3 col-6" class="date" label="تاريخ الميلاد" name="birth_date"
        value="{{ optional($user->details)->birth_date }}"></x-form.input> --}}


    <div class="col-lg-3 col-6 group">
      <label for="division" class="mb-2">شعبة الطالب</label>
      <select name="division" class="modelSelect">
          <option @selected(optional($user->details)->division == 'أدبي') value="أدبي">أدبي</option>
          <option @selected(optional($user->details)->division == 'علمي') value="علمي">علمي</option>
          <option @selected(optional($user->details)->division == 'علمي علوم') value="علمي علوم">علمي علوم</option>
          <option @selected(optional($user->details)->division == 'علمي رياضة') value="علمي رياضة">علمي رياضة</option>
          <option @selected(optional($user->details)->division == 'ليس تابع للنظام الثانوي') value="ليس تابع للنظام الثانوي">ليس تابع للنظام الثانوي</option>
      </select>
    </div>
    <div class="col-lg-3 col-6 group">
      <label for="enrollment_status" class="mb-2">حالة قيد الطالب</label>
      <select name="enrollment_status" class="modelSelect">
          <option @selected(optional($user->details)->enrollment_status == 'منقول') value="منقول">منقول</option>
          <option @selected(optional($user->details)->enrollment_status == 'مستجد') value="مستجد">مستجد</option>
          <option @selected(optional($user->details)->enrollment_status == 'باقي عام') value="باقي عام">باقي عام</option>
          <option @selected(optional($user->details)->enrollment_status == 'باقي عامين') value="باقي عامين">باقي عامين</option>
          <option @selected(optional($user->details)->enrollment_status == 'منقطع') value="منقطع">منقطع</option>
          <option @selected(optional($user->details)->enrollment_status == 'حالة وفاة') value="حالة وفاة">حالة وفاة</option>

      </select>
    </div>
    <div class="col-lg-3 col-6 group">
      <label for="second_language" class="mb-2">اللغة الثانية للطالب</label>
      <select name="second_language" class="modelSelect">
          <option value="NULL">-- اختر اللغة -- </option>
          <option @selected(optional($user->details)->second_language == 'فرنساوي') value="فرنساوي">فرنساوي</option>
          <option @selected(optional($user->details)->second_language == 'ايطالي') value="ايطالي">ايطالي</option>
          <option @selected(optional($user->details)->second_language == 'ألماني') value="ألماني">ألماني</option>
      </select>
    </div>
        <x-form.input placeHolder="قم بادخال كود الوزارة" col="col-lg-3 col-6"  label="كود الوزارة" name="ministry_code"
        value="{{ optional($user->details)->ministry_code }}"></x-form.input>

    <div class="col-12 check_group mb-3">
        <label for="is_international"> هل الطالب وافد</label>
        <input type="checkbox" name="is_international" id="is_international" @checked(optional($user->details)->is_international === 1)>
    </div>

        <x-form.input col="col-lg-3 col-6" class="date" label="تاريخ انتهاء الاقامة" name="residence_expiry_date"
        value="{{ optional($user->details)->residence_expiry_date }}"></x-form.input>

        <x-form.input col="col-lg-3 col-6"  label="اسم الطالب بالانجليزي" name="name_en"
        value="{{ optional($user->details)->name_en }}"></x-form.input>

        <x-form.input class="form-control" col="col-lg-3 col-12" onchange="checkAllForms()" type="file" accept="image/*"
        label="صورة الطالب" name="student_image"></x-form.input>

      @if(!empty($user->details->student_image))
          <img src="{{ $user->details->student_image }}" alt="صورة الطالب" style="width: 150px; height: auto;">
      @else
          <p>لا توجد صورة</p>
      @endif
    <div class="col-12 mt-3">
        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>



    </div>
</form>
