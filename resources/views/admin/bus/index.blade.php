@extends('admin.layout')

@section('title')
    <title>{{ trans('words.الباصات') }}</title>
@endsection

@section('style')
    <style>
        .places {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;

        }

        .places label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
@endsection



@section('content')
    <div class="actions border-0">

        <x-admin.layout.back title="{{ trans('words.الباصات') }}"></x-admin.layout.back>

        <div class="d-flex gap-2">


            <x-cr_button title="{{ trans('words.اضافة باص') }}"></x-cr_button>
        </div>
    </div>
    <div class="tableSpace">
        <table class="mb-3" id="sortable-table">




            <tbody>

                @forelse ($AllData as $bus)
                    <tr>

                        <td>{{ $bus->id }}</td>
                        <td>عدد المشتريكين : {{$bus->order_meta['bus_users_count']}}</td>
                        <td>عدد الكراسي الفاضية ذاهبا : {{$bus->order_meta['empty_going_chairs']}}</td>
                        <td>عدد الكراسي الفاضية رجوعا : {{$bus->order_meta['empty_return_chairs']}}</td>
                        <td style="text-align: {{ getLocale() == 'ar' ? 'right' : 'left' }}">
                            <x-icons.move></x-icons.move> {{ $bus->name }}
                        </td>
                        <input type="hidden" value="{{ $bus->id }}" class="ids">



                        <x-td_end id="{{ $bus->id }}" name="{{ $bus->name }}">


                            <x-form.link title="اعدادات الباص" style=" height: 32px;margin: 0px 5px"
                                path="admin/buses/{{ $bus->id }}/settings"></x-form.link>

                            <x-update_button data-id="{{ $bus->id }}" data-name="{{ $bus->name }}"
                                data-go_chairs_count="{{ $bus->go_chairs_count }}"
                                data-return_chairs_count="{{ $bus->return_chairs_count }}"
                                data-places="{{ json_encode($bus->places->pluck('id')->toArray()) }}"></x-update_button>

                        </x-td_end>

                    </tr>

                @empty
                    <tr>
                        <td colspan="2"> {{ trans('words.no_data') }} </td>
                    </tr>
                @endforelse


            </tbody>
        </table>


        @if ($all->isNotEmpty())
            <x-form.button onclick="UpdateOrder()" title="{{ trans('words.change') }}"></x-form.button>
        @endif

    </div>


    <x-form.create_model id="createModel" title="{{ trans('words.اضافة باص') }}" path="admin/buses">
        <div class="row g-2">

            <x-form.input required col="col-4" label="{{ trans('words.اسم الباص') }}" name="name">
            </x-form.input>

            <x-form.input required col="col-4" type="number" min="1"
                label="{{ trans('words.عدد كراسي الذهاب') }}" name="go_chairs_count">
            </x-form.input>
            <x-form.input required col="col-4" type="number" min="1"
                label="{{ trans('words.عدد كراسي العودة') }}" name="return_chairs_count">
            </x-form.input>
            <hr>



            @foreach ($regions as $governorate)
                <h5>
                    <label>
                        <input type="checkbox" class="governorate-checkbox" data-governorate-id="{{ $governorate->id }}">
                        {{ $governorate->name }}
                    </label>
                </h5>
                <div class="places" id="governorate-{{ $governorate->id }}">
                    @foreach ($governorate->places as $area)
                        <label>
                            <input type="checkbox" name="area_ids[]" value="{{ $area->id }}" class="place-checkbox"
                                data-governorate-id="{{ $governorate->id }}">
                            {{ $area->name }}
                        </label>
                    @endforeach
                </div>
                <hr>
            @endforeach

        </div>
    </x-form.create_model>

    <x-form.delete title="{{ trans('words.الباص') }}" path="admin/buses/destroy"></x-form.delete>

    <x-form.update_model path="admin/buses">
        <div class="row g-2">
            <input type="hidden" name="idInput" id="idInput">
            <x-form.input id="nameInput" col="col-4" required label="{{ trans('words.اسم الباص') }}" name="nameInput">
            </x-form.input>


            <x-form.input required col="col-4" type="number" min="1"
                label="{{ trans('words.عدد كراسي الذهاب') }}" name="go_chairs_countInput">
            </x-form.input>
            <x-form.input required col="col-4" type="number" min="1"
                label="{{ trans('words.عدد كراسي العودة') }}" name="return_chairs_countInput">
            </x-form.input>

            <hr>



            @foreach ($regions as $governorate)
                <h5>{{ $governorate->name }}</h5>
                <div class="places">

                    @foreach ($governorate->places as $area)
                        <label>
                            <input type="checkbox" name="area_ids[]" value="{{ $area->id }}">
                            {{ $area->name }}
                        </label>
                    @endforeach
                </div>
                <hr>
            @endforeach


        </div>
    </x-form.update_model>
@endsection


@section('js')

    <x-move model="buses"></x-move>


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
        $('aside .buses').addClass('active');


        document.querySelectorAll('.governorate-checkbox').forEach(govCheckbox => {
            govCheckbox.addEventListener('change', function() {
                const governorateId = this.getAttribute('data-governorate-id');
                const isChecked = this.checked;

                // حدد أو ألغِ تحديد جميع الأماكن التابعة للمحافظة
                document.querySelectorAll(`.place-checkbox[data-governorate-id="${governorateId}"]`)
                    .forEach(placeCheckbox => {
                        placeCheckbox.checked = isChecked;
                    });
            });
        });


        function showUpdateModel(e) {
            $("#idInput").val(e.getAttribute('data-id'));
            $("#nameInput").val(e.getAttribute('data-name'));
            $("#go_chairs_countInput").val(e.getAttribute('data-go_chairs_count'));
            $("#return_chairs_countInput").val(e.getAttribute('data-return_chairs_count'));
            checkAllForms();


            const places = JSON.parse(e.getAttribute('data-places'));



            const checkboxes = document.querySelectorAll('input[name="area_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = places.includes(parseInt(checkbox.value));
            });


            $('#updateModel').modal('show');

        }
    </script>

@endsection
