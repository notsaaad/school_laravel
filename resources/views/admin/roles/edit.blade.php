@extends('admin.layout')


@section('title')
    <title> {{ trans('words.btn_update') }} {{ $role->name }}</title>
@endsection


@section('css')
    <style>
        form .role {
            display: flex;
            align-items: center;
            column-gap: 20px;
            border-bottom: 1px solid #dddddd;
            padding: 10px;

        }

        .action2,
        .master {
            display: flex;
            align-items: center;
            column-gap: 10px;

        }

        .master label {
            margin: 0px;
            font-size: 15px;
            font-weight: 700;
        }



        .role label {
            margin: 0px;
            font-size: 15px;
        }
    </style>
@endsection


@section('content')

        <div class="actions border-0" >
            <x-admin.layout.back  back="admin/roles" title=" تعديل {{ $role->name }}"></x-admin.layout.back>
        </div>

        <form class="row form_style" action="/admin/roles/{{ $role->id }}" method="post">

            @csrf
            @method('put')
            <div class="col-12">

                <x-form.input col="col-12" value="{{ $role->name }}" reqiured label="{{trans('words.role_name')}}" name="name"
                              placeHolder="مثال : خدمة العملاء "></x-form.input>


            </div>


            <div id="AdminRoles" class="mt-3">
                <p class="contnet-title d-flex align-items-center">
                    <label for="all"> الصلاحيات كاملة </label>
                    <input class="mx-2" onchange="allCheckedAdmin(this)" id="all" type="checkbox">
                </p>
                <div class="tree">
                    @foreach (config('adminRoles.permissions') as $rolee => $actions)
                        <div class="role">
                            <div class="master">
                                @if (is_array($actions))
                                    <input type="checkbox" id="{{ $rolee }}" class="master-checkbox"
                                           onchange="toggleSubPermissions(this)">
                                    <label for="{{ $rolee }}">{{ $rolee }}</label>
                                    <div class="sub-permissions">
                                        @foreach ($actions as $subAction => $subActionValue)
                                            <div class="sub-role">
                                                <input name="permissions[]" value="{{ $subActionValue }}"
                                                       @checked(in_array($subActionValue, json_decode($role->permissions))) type="checkbox"
                                                       id="{{ $subActionValue }}"
                                                       class="sub-checkbox" onchange="checkMasterCheckbox(this)">
                                                <label for="{{ $subActionValue }}">{{ $subAction }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <input name="permissions[]" value="{{ $actions }}"
                                           @checked(in_array($actions, json_decode($role->permissions)))
                                           type="checkbox" id="{{ $actions }}" class="master-checkbox">
                                    <label for="{{ $actions }}">{{ $rolee }}</label>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <div class="col-12 mt-3">
                <x-form.button title="{{ trans('words.btn_update') }}" id="submitBtn"></x-form.button>
            </div>
        </form>
@endsection


@section('js')
    <script>
        $('aside .users').addClass('active');

        function allCheckedAdmin(e) {
            $('#AdminRoles input:checkbox').not(e).prop('checked', e.checked);
        }

        document.addEventListener("DOMContentLoaded", function () {
            checkAllSubPermissions();
        });

        function toggleSubPermissions(masterCheckbox) {
            var subPermissions = masterCheckbox.parentElement.querySelector('.sub-permissions');
            if (subPermissions) {
                subPermissions = subPermissions.querySelectorAll('.sub-checkbox');
                for (var i = 0; i < subPermissions.length; i++) {
                    subPermissions[i].checked = masterCheckbox.checked;
                }
            }
        }

        function checkAllSubPermissions() {
            var subPermissionsContainers = document.querySelectorAll('.sub-permissions');
            subPermissionsContainers.forEach(function (container) {
                var subPermissions = container.querySelectorAll('.sub-checkbox');
                var allChecked = Array.from(subPermissions).every(function (subPermission) {
                    return subPermission.checked;
                });
                var masterCheckbox = container.parentElement.querySelector('.master-checkbox');
                if (masterCheckbox) {
                    masterCheckbox.checked = allChecked;
                }


            });

        }

        function checkMasterCheckbox(subCheckbox) {
            var subPermissionsContainer = subCheckbox.closest('.sub-permissions');
            var masterCheckbox = subPermissionsContainer.parentElement.querySelector('.master-checkbox');
            if (masterCheckbox) {
                var subPermissions = subPermissionsContainer.querySelectorAll('.sub-checkbox');
                var allChecked = Array.from(subPermissions).every(function (subPermission) {
                    return subPermission.checked;
                });
                masterCheckbox.checked = allChecked;
            }
        }
    </script>
@endsection
