@extends('users.layout')



@section('title')
    <title> Buses </title>
@endsection


@section('content')
    <div class="content container mt-3">
        <div class="text-center d-flex flex-column  align-items-center" style="font-size: 30px">

            <img src="/assets/users/imgs/bus.png" width="247">


            <div class="empty my-3">You are not subscribed to any bus.</div>

            <x-cr_button title="Subscribe"></x-cr_button>

        </div>


        <x-form.create_model id="createModel" title="Subscribe in Bus" path="students/subscribe_in_bus">
            <div class="row g-2">
                <x-form.input required col="col-12" label="Your Full Address" name="address"></x-form.input>

                <x-form.select col="col-6" reqiured name="region_id" label="region">

                    <option disabled selected value="">
                        Select Region
                    </option>
                    @forelse (App\Models\region::get() as $region)
                        <option data-price="{{ $region->delivery_price }}" @if (old('region_id') == $region->id) selected @endif
                            value="{{ $region->id }}">
                            {{ $region->name }}
                        </option>
                    @empty
                        <option disabled> {{ trans('words.no_data') }} </option>
                    @endforelse
                </x-form.select>

                <x-form.select col="col-6" reqiured name="place_id" label="place">
                    <option disabled selected value=""> Select Region First </option>
                </x-form.select>


                <x-form.select col="col-6" reqiured name="bus_setting_id" label="Bus Type">
                    <option disabled selected value=""> Select Place First </option>
                </x-form.select>


                <x-form.input col="col-6" required disabled label="Bus Price" name="price"></x-form.input>



            </div>
        </x-form.create_model>
    </div>
@endsection

@section('js')
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#createModel').modal('show');
            });
        </script>
    @endif

    <script>

        $(document).ready(function() {


            $('#createModel select').select2({
                dropdownParent: $('#createModel .modal-content')
            }).on('change', function() {
                checkAllForms();
            });


            $('#region_id').change(function() {
                var regionId = $(this).val();
                if (regionId) {
                    $.ajax({
                        type: 'get',
                        url: `/get_places/${regionId}`,
                        success: function(response) {


                            let cartona =
                                `$('#place_id').html('<option value=""> Select Place </option>');`

                            for (const key of response) {
                                cartona += `<option value="${key.id}">${key.name}</option>`
                            }
                            $('#place_id').html(cartona);

                            checkAllForms();

                            $('#bus_setting_id').html(
                                '<option value="" disabled selected> Select Place First </option>'
                            );

                            $('#price').val("");


                        }
                    });
                } else {
                    $('#place_id').html('<option value="" >  Select Place</option>');
                    $('#price').val("");
                }

            });

            $('#place_id').change(function() {
                var place_id = $(this).val();
                if (place_id) {
                    $.ajax({
                        type: 'get',
                        url: `/get_bus_settings/${place_id}`,
                        success: function(response) {

                            if (response.status == "success") {
                                let cartona =
                                    `$('#bus_setting_id').html('<option value="" selected> Select Bus Type </option>');`

                                for (const key of response.data) {
                                    cartona +=
                                        `<option data-price="${key.price}" value="${key.id}">${key.name}</option>`
                                }
                                $('#bus_setting_id').html(cartona);
                            } else if (response.status == "error") {

                                $('#bus_setting_id').html(
                                    '<option value="" disabled selected> Select Bus Type </option>'
                                );

                                Swal.fire({
                                    title: 'Warning!',
                                    text: `${response.message}`,
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                })
                            }


                            checkAllForms();

                        }
                    });
                } else {
                    $('#bus_setting_id').html('<option value="" disabled>   Select Bus Type </option>');
                }

            });


            $('#bus_setting_id').change(function() {
                var price = $(this).find(':selected').data('price'); // الحصول على قيمة data-price
                $('#price').val(price); // وضع القيمة في input الذي يحتوي على id="price"
                checkAllForms();
            });

            checkAllForms();


        });
    </script>
@endsection
