@extends('admin.layout')



@section('title')
    <title> {{ $application->name }} </title>
@endsection

@section('css')
    <style>
        .status_parant .select2-container {
            width: 200px !important;
        }

        .notes {
            font-weight: 700;
            color: red;
            font-size: 14px;
        }

        .status_parant .select2-container--default .select2-selection--single {
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: ;
            border-color: rgb(67 118 109);
            outline-width: 0;
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(202 211 209 / var(--tw-ring-opacity));
        }
    </style>
@endsection
    <script>

    </script>

@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/applications" title="{{ $application->name }}">
        </x-admin.layout.back>

        <div class="actions">
            <div class="status_parant">
                <x-form.select col="" required name="status" label="">

                    <option selected value="{{ $application->status }}"> {{ $application->status }} ( الحالة الحالية )
                    </option>

                    @if (canEdit($application))
                        @php
                            $status = ['new', 'paid', 'returned', 'canceled'];
                        @endphp


                        @foreach ($status as $state)
                            @can('has', $state)
                                @if ($application->status != $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endif
                            @endcan
                        @endforeach
                    @endif


                </x-form.select>
            </div>


            <div class="status_parant" @if($application->status == "new") style="display:none" @endif>
                <x-form.select col="" required name="custom_status" label="">

                    <option selected disabled value="{{ $application->custom_status()?->id }}">
                        {{ $application->custom_status()?->name ?? 'Select Custom Status' }}
                    </option>


                    @foreach (App\Models\customState::where('status', $application->status)->get() as $state)
                        @if ($application->custom_status()?->name != $state->name)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endif
                    @endforeach


                </x-form.select>
            </div>

            <x-cr_button title="بيانات اضافية"></x-cr_button>

        </div>




    </div>

    <div class="user">
        <div class="title"> {{ trans('words.تفاصيل') }}</div>

        <p class="notes">{{ $application->notes }}</p>


        <div class="user_parant">

            <div class="user_content">
                <div data-title="الكود" class="user_group">
                    <div class="user_group_title"> {{ trans('words.الكود') }}</div>
                    <div class="data">{{ $application->code }}</div>
                </div>

                <div data-title="النوع" class="user_group">
                    <div class="user_group_title">{{ trans('words.النوع') }}</div>
                    <div class="data">
                        @if ($application->gender == 'boy')
                            <td>{{ trans('words.ذكر') }}</td>
                        @else
                            <td>{{ trans('words.انثي') }}</td>
                        @endif
                    </div>
                </div>

                <div data-title="اسم الطالب" class="user_group">
                    <div class="user_group_title"> {{ trans('words.اسم الطالب') }}</div>
                    <div class="data">{{ $application->name }}</div>
                </div>

                <div data-title="الحالة" class="user_group">
                    <div class="user_group_title"> {{ trans('words.الحالة') }}</div>
                    <div class="data"><span class="orderStatus {{ $application->status }} ">{{ $application->status }}
                        </span></div>
                </div>


                <div data-title="حالة مخصصة" class="user_group">
                    <div class="user_group_title"> {{ trans('words.حالة مخصصة') }}</div>
                    <div class="data">{{ $application->specialStatus()?->getName() ?? '-' }}</div>
                </div>



                <div data-title="حالة مخصصة" class="user_group">
                    <div class="user_group_title"> {{ trans('words.حالة مخصصة اخري') }}</div>
                    <div class="data">{{ $application->custom_status()?->name ?? '-' }}</div>
                </div>



                <div data-title="المنطقة" class="user_group">
                    <div class="user_group_title"> {{ trans('words.place') }}</div>
                    <div class="data">{{ $application->place()?->name ?? '-' }}</div>
                </div>


                <div data-title="المرحلة" class="user_group">
                    <div class="user_group_title"> {{ trans('words.المرحلة') }}</div>
                    <div class="data">{{ $application->stage->name }}</div>
                </div>

                <div data-title="نوع الدراسة" class="user_group">
                    <div class="user_group_title"> {{ trans('words.نوع الدراسة') }}</div>
                    <div class="data">{{ $application->study_type }}</div>
                </div>



                <div data-title="المبلغ" class="user_group">
                    <div class="user_group_title"> {{ trans('words.المبلغ') }}</div>
                    <div class="data">{{ $application->fees->amount }}</div>
                </div>



                <div data-title="خصم" class="user_group">
                    <div class="user_group_title"> {{ trans('words.discountType') }}</div>
                    <div class="data">{{ $application->discountType()?->getName() ?? '-' }}</div>
                </div>

                <div data-title="referralSource" class="user_group">
                    <div class="user_group_title"> {{ trans('words.referralSource') }}</div>
                    <div class="data">{{ $application->referralSource()?->getName() ?? '-' }}</div>
                </div>





                <div data-title="تاريخ الانشاء" class="user_group">
                    <div class="user_group_title"> {{ trans('words.تاريخ الانشاء') }}</div>
                    <div class="data">{{ fixDate($application->created_at) }}</div>
                </div>





            </div>
        </div>
    </div>



    @can('has', 'tests_applications_index')
        <table>
            <thead>
                <tr>
                    <th> {{ trans('words.الاسم') }} </th>
                    <th> {{ trans('words.الحالة') }} </th>
                    <th> {{ trans('words.معاد التأجيل') }} </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($application->application_subjects as $application_subject)
                    <tr>
                        <td>{{ $application_subject->subject->name }}</td>

                        @if ($application->status == 'paid')
                            @can('has', 'tests_applications_actions')
                                <td>
                                    <select class="status-select" data-subject-id="{{ $application_subject->subject->id }}"
                                        data-subject-name="{{ $application_subject->subject->name }}"
                                        data-original-status="{{ $application_subject->status }}">
                                        <option value="Not Tested"
                                            {{ $application_subject->status == 'Not Tested' ? 'selected' : '' }}>
                                            Not Tested</option>
                                        <option value="Accepted" {{ $application_subject->status == 'Accepted' ? 'selected' : '' }}>
                                            Accepted
                                        </option>
                                        <option value="Rejected" {{ $application_subject->status == 'Rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                        <option value="Re-Assessment" {{ $application_subject->status == 'Re-Assessment' ? 'selected' : '' }}>
                                            Re-Assessment
                                        </option>
                                    </select>
                                </td>
                            @else
                                <td>{{ $application_subject->status }}</td>
                            @endcan
                        @else
                            <td>{{ $application_subject->status }}</td>
                        @endif



                        <td>{{ $application_subject->retake_data }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endcan


    @if (!$application->application_subjects()->where('status', '!=', 'Accepted')->exists() && $application->status == 'paid')
        <div class="mt-3 d-flex justify-content-end"><x-form.button onclick="complate()"
                title="Complate  Application"></x-form.button></div>
    @endif







    <x-form.create_model id="createModel" title="تعديل" path="admin/applications/{{ $application->id }}/updateFields">
        <div class="row g-2">


            <div class="actions">
                <button type="button" onclick="copy('{{ url('applications') . '/' . $application->code . '/link' }}')"
                    class="es-btn-primary default d-flex gap-2"> نسخ
                    الرابط
                </button>

                <div class="form-check form-switch">
                    <input value="{{ $application->id }}" @checked($application->can_share == 'yes') onchange="enableToggle(this)"
                        class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                </div>
            </div>








            @foreach ($fields as $field)
                @php
                    $value = $application->applicationData->where('field_id', $field->id)->first()?->value;
                @endphp

                @if ($field->type == 'text')
                    <x-form.input col="col-12" label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}]" :value="$value"></x-form.input>
                @endif

                @if ($field->type == 'number')
                    <x-form.input type="number" label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}]" :value="$value"></x-form.input>
                @endif

                @if ($field->type == 'select')
                    <x-form.select label="{{ $field->name }}" :required="$field->is_required" name="fields[{{ $field->id }}]">
                        <option value="" disabled {{ !$value ? 'selected' : '' }}>Select</option>

                        @foreach (explode(',', $field->options) as $option)
                            <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                {{ $option }}</option>
                        @endforeach
                    </x-form.select>
                @endif

                @if ($field->type == 'checkbox')
                    <x-form.select col="col-12" multiple label="{{ $field->name }}" :required="$field->is_required"
                        name="fields[{{ $field->id }}][]">
                        @php
                            $values = explode(',', $value ?? '');
                        @endphp
                        @foreach (explode(',', $field->options) as $option)
                            <option value="{{ $option }}" {{ in_array(trim($option), $values) ? 'selected' : '' }}>
                                {{ $option }}</option>
                        @endforeach
                    </x-form.select>
                @endif
            @endforeach


        </div>
    </x-form.create_model>




@endsection

@section('js')
    <script>
        $('li.applications').addClass('active');



        $("#status").select2().on('change', function(e) {
            var selectedValue = $(this).val();
            Swal.fire({
                title: 'هل أنت متأكد من تغير الحالة؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، قم بالتغيير!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // إنشاء نموذج وإرساله
                    var form = $('<form>', {
                        action: '/admin/applications/changeStatus',
                        method: 'POST'
                    }).append($('<input>', {
                        type: 'hidden',
                        name: 'status',
                        value: selectedValue
                    })).append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}' // إضافة رمز CSRF إذا كنت تستخدم Laravel
                    })).append($('<input>', {
                        type: 'hidden',
                        name: 'application_id',
                        value: '{{ $application->id }}' // إضافة رمز CSRF إذا كنت تستخدم Laravel
                    }));

                    // إرفاق النموذج بالجسم وإرساله
                    $('body').append(form);
                    form.submit();
                }
            });
        });




        $("#custom_status").select2().on('change', function(e) {
            var selectedValue = $(this).val();
            Swal.fire({
                title: 'هل أنت متأكد من تغير الحالة؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، قم بالتغيير!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // إنشاء نموذج وإرساله
                    var form = $('<form>', {
                        action: '/admin/applications/changeCustomStatus',
                        method: 'POST'
                    }).append($('<input>', {
                        type: 'hidden',
                        name: 'status',
                        value: selectedValue
                    })).append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}' // إضافة رمز CSRF إذا كنت تستخدم Laravel
                    })).append($('<input>', {
                        type: 'hidden',
                        name: 'application_id',
                        value: '{{ $application->id }}' // إضافة رمز CSRF إذا كنت تستخدم Laravel
                    }));

                    // إرفاق النموذج بالجسم وإرساله
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>

    <script>
        flatpickr('input.date', {
            enableTime: false,
            dateFormat: "Y-m-d"
        });


        // Initialize Select2
        $('.status-select').select2();

        // Handle status selection
        $('.status-select').on('change', function() {
            let subjectId = $(this).data('subject-id');
            let subjectName = $(this).data('subject-name');
            let newStatus = $(this).val();
            let originalStatus = $(this).data(
                'original-status'); // Access the original status from data-original-status



            if (newStatus === originalStatus) {
                return; // Do nothing if the status is the same as before
            }
            let retakeDateInput = '';
            if (newStatus === 'Retake') {
                retakeDateInput =
                    `<input type="date" id="retake-date" class="date" placeholder="Enter retake date">`;
            }

            // Show confirmation modal
            Swal.fire({
                title: `هل انت متأكد?`,
                html: `تغير الحالة  الي <strong>${newStatus}</strong> في مادة  <strong>${subjectName}</strong>. ${retakeDateInput}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, confirm',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    if (newStatus === 'Retake') {
                        let retakeDate = document.getElementById('retake-date').value;
                        if (!retakeDate) {
                            Swal.showValidationMessage('Please enter the retake date.');
                        }
                        return {
                            retakeDate: retakeDate
                        };
                    }
                    return {};
                },
                didOpen: () => {
                    flatpickr('input.date', {
                        enableTime: false,
                        dateFormat: "Y-m-d"
                    });

                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Prepare form data
                    let formData = {
                        subject_id: subjectId,
                        status: newStatus,
                        application_id: '{{ $application->id }}',
                        retake_date: result.value ? result.value.retakeDate : null,
                        _token: '{{ csrf_token() }}'
                    };

                    // Send POST request
                    $.post('/admin/applications/update_subject', formData, function(response) {
                        Swal.fire('Success', 'The test status has been updated!', 'success');
                        $(this).data('original-status', newStatus);
                    }).fail(function() {
                        Swal.fire('Error', 'There was an error updating the status.', 'error');
                        $(`[data-subject-id="${subjectId}"]`).val(originalStatus).trigger('change');
                    });
                } else {
                    $(this).val(originalStatus).trigger('change');
                }


                window.location.reload();


            }).catch(() => {
                $(this).val(originalStatus).trigger('change');
            });
        });
    </script>

    <script>
        function complate() {
            Swal.fire({
                title: 'هل أنت متأكد من استكمال التقديم وتسجيل الطالب؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // إنشاء نموذج (form) وإرساله
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/admin/applications/complate';

                    // إضافة حقل CSRF Token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // إضافة حقل application_id
                    const applicationInput = document.createElement('input');
                    applicationInput.type = 'hidden';
                    applicationInput.name = 'application_id';
                    applicationInput.value = '{{ $application->id }}';
                    form.appendChild(applicationInput);

                    // إضافة النموذج إلى الصفحة ثم إرساله
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {

            $('#createModel .modelSelect').select2({
                dropdownParent: $('#createModel .modal-content')
            });

            checkAllForms();

        });



        function enableToggle(e) {
            let id = e.value;

            let show = 0;

            if (e.checked) {
                show = 1;
            }

            $.ajax({
                url: '/admin/applications/enableToggle',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    id: id,
                    show: show
                }),
                success: function(response) {
                    if (response.status == "error") {
                        Swal.fire({
                            text: response.message,
                            icon: "error"
                        });
                        $(e).prop('checked', false);
                    } else {
                        toastr["success"]("تم بنجاح");
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Network response was not ok', textStatus, errorThrown);
                }
            });

        }
    </script>
@endsection
