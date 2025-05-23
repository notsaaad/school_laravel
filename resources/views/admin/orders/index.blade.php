@extends('admin.layout')





@section('title')
    <title> {{ trans('words.الطلبات') }} </title>

    <style>
        #copy-button {
            display: none
        }
    </style>
@endsection


@section('content')
    <div class="actions">

        <x-admin.layout.back title="{{ trans('words.الطلبات') }}"></x-admin.layout.back>
        <div class="d-flex gap-2 flex-wrap">


            <button id="copy-button" class="es-btn-primary">نسخ الايديهات</button>


            <x-search_button></x-search_button>



        </div>

    </div>


    <div class="position-relative">

        <div id="loader">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </div>



        <div class="tableSpace">
            <table>
                <thead>

                    <tr>
                        <th> <input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>{{ trans('words.كود الطلب') }}</th>
                        <th>{{ trans('words.الطالب') }}</th>
                        <th>{{ trans('words.الوصف') }}</th>
                        <th>{{ trans('words.السعر') }}</th>
                        <th>{{ trans('words.الحالة') }}</th>
                        <th>{{ trans('words.تاريخ الشراء') }} </th>

                        @can('has', 'show_in_order')
                            <th>{{ trans('words.actions') }}</th>
                        @endcan
                    </tr>

                </thead>

                <tbody class="clickable">

                    @foreach ($orders as $order)
                        <tr>
                            <td><input type="checkbox" class="order-checkbox" value="{{ $order->reference }}"></td>


                            <td>{{ $loop->index + 1 }}</td>
                            <td> <span class="copy" onclick="copy('{{ $order->reference }}')"><i
                                        class="fa-regular fa-clipboard"></i></span> <a
                                    href="/admin/orders/{{ $order->reference }}">{{ $order->reference }}</a> </td>
                            <td>
                              @if (isset($order->user->name))
                                <div><a style="text-decoration: none" href="{{ route('admin.edit.student', $order->user->id) }}">{{ $order->user->name }}</a></div>
                              @endif

                            </td>

                            <td>{{ $order->package->name ?? 'Items' }}</td>
                            <td>{{ $order->getTotalPrice() }} EGP</td>
                            <td><span class="orderStatus {{ $order->status }} ">{{ $order->status }} </span>
                            </td>
                            <td>{{ fixDate($order->created_at) }}</td>


                            @can('has', 'show_in_order')
                                <td>
                                    <div onclick="GetOrderDetails({{ $order->id }} , 'admin')"
                                        data-tippy-content="{{ trans('words.عرض المنتجات') }}" class="square-btn ltr has-tip">
                                        <i class="fa-regular fa-eye mr-2 icon fa-fw"></i>
                                    </div>
                                </td>
                            @endcan



                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <x-orderFrame></x-orderFrame>


    <x-form.search_model path="admin/orders/search">

        <x-form.input value="{{ $_GET['reference'] ?? '' }}" type="search" col="col-lg-6 col-12"
            label="{{ trans('words.كود الطلب') }}" name="reference"></x-form.input>

        <x-form.input value="{{ $_GET['code'] ?? '' }}" type="search" col="col-lg-6 col-12"
            label="{{ trans('words.كود الطالب') }}" name="code"></x-form.input>


        <div class="col-lg-6 col-12" id="status">
            <label for="status" class="mb-2">{{ trans('words.الحالة') }}</label>

            <select name="status" class="modelSelect w-100 ">
                <option value="">{{ trans('words.كل الحالات') }}</option>
                <option @selected(isset($_GET['status']) && $_GET['status'] == 'pending') value="pending">pending</option>
                <option @selected(isset($_GET['status']) && $_GET['status'] == 'paid') value="paid">paid</option>
                <option @selected(isset($_GET['status']) && $_GET['status'] == 'picked') value="picked">picked</option>
                <option @selected(isset($_GET['status']) && $_GET['status'] == 'canceled') value="canceled">canceled</option>
                <option @selected(isset($_GET['status']) && $_GET['status'] == 'partially_picked') value="partially_picked">partially_picked</option>
                <option @selected(isset($_GET['status']) && $_GET['status'] == 'return_requested') value="return_requested">return_requested</option>
                <option @selected(isset($_GET['status']) && $_GET['status'] == 'returned') value="returned">returned</option>

            </select>
        </div>

        <x-form.input col="col-6" dir="ltr" value="{{ $_GET['date'] ?? '' }}" class="date" type="search"
            label="{{ trans('words.تاريخ الشراء') }}" name="date"></x-form.input>

      <x-form.select label="محتوي الاوردر" required="false" name="type">
        <option disabled >قم بختيار نوع  محتوي الطلب</option>
        <option @selected(isset($_GET['type']) && $_GET['type'] == 'items')  value="items">Items</option>
        <option @selected(isset($_GET['type']) && $_GET['type'] == 'package')  value="package">Package</option>

      </x-form.select>






    </x-form.search_model>
@endsection


@section('js')
    <script>
        $('li.orders').addClass('active');


        $(document).ready(function() {

            $('#searchModel .modelSelect').select2({
                dropdownParent: $('#searchModel .modal-content')
            });

            flatpickr('input.date', {
                enableTime: false,
                mode: "range",
                dateFormat: "Y-m-d"
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let copyButton = document.getElementById('copy-button');
            let checkboxes = document.querySelectorAll('.order-checkbox');
            let selectAllCheckbox = document.getElementById('select-all');

            // تحديث حالة الزر بناءً على التحديد
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateButtonState);
            });

            // تحديث حالة الزر وعرض IDs
            function updateButtonState() {
                let selectedIds = getSelectedIds();

                if (selectedIds.length === 0) {
                    copyButton.style.display = "none"

                } else {
                    copyButton.style.display = "block"

                }
            }

            // نسخ الـ IDs إلى الحافظة
            copyButton.addEventListener('click', function() {
                let selectedIds = getSelectedIds().join(', ');

                navigator.clipboard.writeText(selectedIds).then(() => {

                    toastr.success('تم نسخ ' + getSelectedIds().length + " اوردرات ")

                }).catch(err => {

                    toastr.error('لم يتم النسخ')

                });
            });

            // تحديد أو إلغاء تحديد كل الطلبات
            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;

                    // Find the closest <tr> element
                    let trElement = checkbox.closest('tr');

                    // Add or remove the 'active' class based on the checkbox state
                    if (checkbox.checked) {
                        trElement.classList.add('active');
                    } else {
                        trElement.classList.remove('active');
                    }
                });

                updateButtonState(); // تحديث حالة الزر بعد التحديد أو الإلغاء
            });


            // وظيفة لجلب الـ IDs المحددة
            function getSelectedIds() {
                return Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
            }

            $("tr").on("click", function(event) {
                if ($(event.target).is("td:not(:first-child)")) {
                    $(this).toggleClass("active");

                    var checkbox = $(this).find("input[type='checkbox']");
                    checkbox.prop("checked", !checkbox.prop("checked"));
                }

                updateButtonState()

            });
        });


    </script>
@endsection
