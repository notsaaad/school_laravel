@extends('admin.layout')

@section('title')
    <title>{{ trans('words.التقديمات') }}</title>
@endsection


@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.التقديمات') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">

            <x-search_button></x-search_button>

            @can('has', 'applications_actions')
                <x-cr_button title="{{ trans('words.اضافة') }}"></x-cr_button>
            @endcan
        </div>
    </div>

    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">

            <thead>
                <tr>
                    <th>{{ trans('words.الكود') }}</th>
                    <th>{{ trans('words.اسم الطالب') }}</th>
                    <th>{{ trans('words.الحالة') }}</th>
                    <th>{{ trans('words.ارقام التواصل') }}</th>
                    <th>{{ trans('words.المرحلة') }}</th>
                    <th>{{ trans('words.السنة دراسية') }}</th>
                    <th>{{ trans('words.نوع الدراسة') }}</th>
                    <th>{{ trans('words.المبلغ') }}</th>
                    <th>{{ trans('words.تاريخ الانشاء') }}</th>

                    @can('has', 'applications_actions')
                        <th>{{ trans('words.actions') }}</th>
                    @endcan
                </tr>
            </thead>

            <tbody>

                @forelse ($applications as $application)
                    <tr>

                        <td> <span class="copy" onclick="copy('{{ $application->code }}')"><i
                                    class="fa-regular fa-clipboard"></i></span> <a
                                href="/admin/applications/{{ $application->code }}">{{ $application->code }}</a> </td>

                        <td>{{ $application->name }}</td>

                        <td><span class="orderStatus {{ $application->status }} ">{{ $application->status }} </span>
                        </td>


                        <td>
                            {{ $application->phone1 }}
                            @if ($application->phone2)
                                <br>{{ $application->phone2 }}
                            @endif
                        </td>

                        <td>{{ $application->stage->name }}</td>
                        <td>{{ $application->year->name }}</td>
                        <td>{{ $application->study_type }}</td>
                        <td>{{ $application->fees->amount }}</td>

                        <td>{{ fixDate($application->created_at) }}</td>

                        @can('has', 'applications_actions')

                            @if (canEdit($application))
                                <x-td_end normal id="{{ $application->id }}" name="{{ $application->name }}">

                                    <x-update_button data-id="{{ $application->id }}"
                                        data-stage_id="{{ $application->stage_id }}" data-name="{{ $application->name }}"
                                        data-phone1="{{ $application->phone1 }}" data-phone2="{{ $application->phone2 }}"
                                        data-gender="{{ $application->gender }}" data-place_id="{{ $application->place_id }}"
                                        data-discountType="{{ $application->discountType }}"
                                        data-referralSource="{{ $application->referralSource }}"
                                        data-specialStatus="{{ $application->specialStatus }}"
                                        data-notes="{{ $application->notes }}"></x-update_button>

                                </x-td_end>
                            @else
                                <td></td>
                            @endcan
                        @endcan
                </tr>

            @empty
                <tr>
                    <td colspan="15"> {{ trans('words.no_data') }} </td>
                </tr>
            @endforelse


        </tbody>
    </table>

    <div class="pagnator">
        {{ $applications->appends(request()->query())->links() }}
    </div>
</div>


@can('has', 'applications_actions')
    <x-form.create_model id="createModel" title="{{ trans('words.اضافة تقديم جديد') }}" path="admin/applications">
        <div class="row g-2">

            <x-form.input col="col-12" required label="{{ trans('words.الاسم بالكامل') }}" name="name"></x-form.input>


            <x-form.select col="col-lg-4 col-6" reqiured name="stage_id" label="{{ trans('words.المرحلة') }}">

                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                @endforeach

            </x-form.select>

            <x-form.select col="col-lg-4 col-6" reqiured name="gender" label="{{ trans('words.النوع') }}">

                <option value="boy">{{ trans('words.ذكر') }}</option>
                <option value="girl">{{ trans('words.انثي') }}</option>

            </x-form.select>


            <x-form.select col="col-lg-4 col-6" reqiured name="study_type" label="{{ trans('words.نوع الدراسة') }}">

                <option value="national">National</option>
                <option value="international">International</option>

            </x-form.select>


            <x-form.select col="col-lg-4 col-6" reqiured name="year_id" label="{{ trans('words.السنة دراسية') }}">

                @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach

            </x-form.select>


            <x-form.input col="col-lg-4 col-6" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required
                label="{{ trans('words.الرقم الاول') }}" name="phone1"></x-form.input>
            <x-form.input col="col-lg-4 col-6" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                label="{{ trans('words.الرقم الثاني') }}" name="phone2"></x-form.input>



            <x-form.select col="col-lg-4 col-6" reqiured name="place_id" label="{{ trans('words.place') }}">

                <option value="" disabled selected>select</option>

                @foreach ($places as $place)
                    <option value="{{ $place->id }}">{{ $place->name }}</option>
                @endforeach

            </x-form.select>

            <x-form.select col="col-lg-4 col-6" name="discountType" label="{{ trans('words.discountType') }}">

                <option value="" disabled selected>select</option>

                @foreach ($discountTypes as $discountType)
                    <option value="{{ $discountType->id }}">{{ $discountType->getName() }}</option>
                @endforeach

            </x-form.select>


            <x-form.select col="col-lg-4 col-6" name="referralSource" label="{{ trans('words.referralSource') }}">

                <option value="" disabled selected>select</option>

                @foreach ($referralSources as $referralSource)
                    <option value="{{ $referralSource->id }}">{{ $referralSource->getName() }}</option>
                @endforeach

            </x-form.select>


            <x-form.textarea col="col-12" label="{{ trans('words.ملاحظات') }}" name="notes"></x-form.textarea>




        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.التقديم') }}" path="admin/applications/destroy"></x-form.delete>


    <x-form.update_model path="admin/applications">
        <div class="row g-2">


            <input type="hidden" name="idInput" id="idInput">


            <x-form.input col="col-lg-8 col-12" required label="{{ trans('words.الاسم بالكامل') }}"
                name="nameInput"></x-form.input>


            <x-form.select col="col-lg-4 col-6" name="specialStatusInput" label="{{ trans('words.حالة خاصة') }}">

                <option value="" selected>select</option>

                @foreach ($specialStatus as $specialState)
                    <option value="{{ $specialState->id }}">{{ $specialState->getName() }}</option>
                @endforeach

            </x-form.select>



            <x-form.input col="col-lg-4 col-6" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required
                label="{{ trans('words.الرقم الاول') }}" name="phone1Input"></x-form.input>


            <x-form.input col="col-lg-4 col-6" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                label="{{ trans('words.الرقم الثاني') }}" name="phone2Input"></x-form.input>


            <x-form.select col="col-lg-4 col-6" reqiured name="genderInput" label="{{ trans('words.النوع') }}">

                <option value="boy">{{ trans('words.ذكر') }}</option>
                <option value="girl">{{ trans('words.انثي') }}</option>

            </x-form.select>





            <x-form.select col="col-lg-4 col-6" reqiured name="place_idInput" label="{{ trans('words.place') }}">

                <option value="" disabled selected>select</option>

                @foreach ($places as $place)
                    <option value="{{ $place->id }}">{{ $place->name }}</option>
                @endforeach

            </x-form.select>

            <x-form.select col="col-lg-4 col-6" name="discountTypeInput" label="{{ trans('words.discountType') }}">

                <option value="" selected>select</option>

                @foreach ($discountTypes as $discountType)
                    <option value="{{ $discountType->id }}">{{ $discountType->getName() }}</option>
                @endforeach

            </x-form.select>


            <x-form.select col="col-lg-4 col-6" name="referralSourceInput" label="{{ trans('words.referralSource') }}">

                <option value="" selected>select</option>

                @foreach ($referralSources as $referralSource)
                    <option value="{{ $referralSource->id }}">{{ $referralSource->getName() }}</option>
                @endforeach

            </x-form.select>


            <x-form.textarea col="col-12" label="{{ trans('words.ملاحظات') }}" name="notesInput"></x-form.textarea>




        </div>
    </x-form.update_model>
@endcan


<x-form.search_model path="admin/applications">
    <x-form.input value="{{ $_GET['name'] ?? '' }}" type="search" label="{{ trans('words.name') }}"
        name="name"></x-form.input>

    <x-form.input value="{{ $_GET['phone'] ?? '' }}" type="search" col="col-lg-6 col-12"
        label="{{ trans('words.phone') }}" name="phone"></x-form.input>

    <div class="col-lg-6 col-12 group ">
        <label for="stage_id" class="mb-2"> {{ trans('words.المرحلة') }}</label>
        <select name="stage_id" class="modelSelect w-100 ">
            <option value="">{{ trans('words.الكل') }}</option>

            @foreach ($stages as $stage)
                <option value="{{ $stage->id }}" @selected(isset($_GET['stage_id']) && $_GET['stage_id'] == $stage->id)> {{ $stage->name }} </option>
            @endforeach
        </select>
    </div>

    @php
        $status = ['new', 'paid', 'complate', 'returned', 'canceled'];
    @endphp


    <div class="col-lg-6 col-12 group ">
        <label for="status" class="mb-2"> {{ trans('words.الحالة') }}</label>
        <select name="status" class="modelSelect w-100 ">
            <option value="">{{ trans('words.الكل') }}</option>

            @foreach ($status as $state)
                <option value="{{ $state }}" @selected(isset($_GET['status']) && $_GET['status'] == $state)> {{ $state }} </option>
            @endforeach
        </select>
    </div>


    <div class="col-lg-6 col-12 group ">
        <label for="year_id" class="mb-2"> {{ trans('words.السنة دراسية') }}</label>
        <select name="year_id" class="modelSelect w-100 ">
            <option value="">{{ trans('words.الكل') }}</option>

            @foreach ($years as $year)
                <option value="{{ $year->id }}" @selected(isset($_GET['year_id']) && $_GET['year_id'] == $year->id)> {{ $year->name }} </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 col-12 group ">
        <label for="study_type" class="mb-2"> {{ trans('words.نوع الدراسة') }}</label>
        <select name="study_type" class="modelSelect w-100 ">
            <option value="">{{ trans('words.الكل') }}</option>

            <option value="national" @selected(isset($_GET['study_type']) && $_GET['study_type'] == 'national')> National </option>
            <option value="international" @selected(isset($_GET['study_type']) && $_GET['study_type'] == 'international')> International </option>
        </select>
    </div>


</x-form.search_model>
@endsection


@section('js')



@if ($errors->any())
    @if ($errors->has('idInput'))
        <script>
            $(document).ready(function() {
                $('#updateModel').modal('show');
            });
        </script>
    @else
        <script>
            $(document).ready(function() {
                $('#createModel').modal('show');
            });
        </script>
    @endif
@endif


<script>
    $('aside .applications').addClass('active');

    function showUpdateModel(e) {
        $("#idInput").val(e.getAttribute('data-id'));
        $("#nameInput").val(e.getAttribute('data-name'));
        $("#phone1Input").val(e.getAttribute('data-phone1'));
        $("#phone2Input").val(e.getAttribute('data-phone2'));
        $("#genderInput").val(e.getAttribute('data-gender')).trigger('change');

        $("#place_idInput").val(e.getAttribute('data-place_id')).trigger('change');
        $("#discountTypeInput").val(e.getAttribute('data-discountType')).trigger('change');
        $("#referralSourceInput").val(e.getAttribute('data-referralSource')).trigger('change');
        $("#specialStatusInput").val(e.getAttribute('data-specialStatus')).trigger('change');


        $("#notesInput").val(e.getAttribute('data-notes')).trigger('change');

        checkAllForms();
        $('#updateModel').modal('show');

    }

    $(document).ready(function() {

        $('#createModel .modelSelect').select2({
            dropdownParent: $('#createModel .modal-content')
        });
        $('#updateModel .modelSelect').select2({
            dropdownParent: $('#updateModel .modal-content')
        });

        $('#searchModel .modelSelect').select2({
            dropdownParent: $('#searchModel .modal-content')
        });

        checkAllForms();

    });
</script>



@endsection
