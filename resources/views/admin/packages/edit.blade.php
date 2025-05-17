@extends('admin.layout')


@section('title')
    <title> {{ trans('words.btn_update') }} {{ $package->name }}</title>
@endsection


@section('css')
    <style>
        /*/product*/

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product {
            border-radius: .375rem;
            overflow: hidden;
            text-align: center;
            box-shadow: rgb(0 0 0 / 8%) 0px 2px 4px;
            padding: 5px;
            width: calc(25% - 10px);
            cursor: pointer
        }

        .product.selected {
            border: 1px solid green;
            /* Highlight selected products */
        }

        .modal-footer {
            border: 0px;
        }


        .product img {
            object-fit: contain;
            width: 100%;
            height: 80px;
            border-radius: .375rem;
        }

        .product_name {
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            display: -webkit-box;
            overflow: hidden;
            margin-top: 2px;
            font-weight: 700;
            font-size: 12px;
            color: #37474f;

        }

        .product_name p {
            line-height: 1.4rem;
            padding: 0px;
        }
    </style>
@endsection



@section('content')
    <div class="actions border-0">
        <x-admin.layout.back back="admin/packages"
            title=" {{ trans('words.btn_update') }} {{ $package->name }}"></x-admin.layout.back>
    </div>


    <form class="row form_style" action="/admin/packages/{{ $package->id }}" method="post" enctype="multipart/form-data">


        @csrf
        @method('put')


        <x-form.input col="col-lg-3 col-6" required label="{{ trans('words.name') }}" name="name"
            value="{{ $package->name }}"></x-form.input>

        <x-form.input col="col-lg-3 col-6" accept="image/*" class="form-control" type="file"
            label="{{ trans('words.صورة الحزمة') }}" name="img">
        </x-form.input>


        <x-form.select
            col="col-lg-3 col-6"
            required
            name="stage_id[]"
            label="{{ trans('words.المرحلة') }}"
            multiple
        >
            @foreach ($stages as $stage)
                <option
                    value="{{ $stage->id }}"
                    @if(isset($package) && $package->stages->contains($stage->id)) selected @endif
                >
                    {{ $stage->name }}
                </option>
            @endforeach
        </x-form.select>



        <x-form.select col="col-lg-3 col-6" required name="gender" label="{{ trans('words.النوع') }}">

            <option @selected($package->gender == 'boy') value="boy">{{ trans('words.ذكر') }}</option>
            <option @selected($package->gender == 'girl') value="girl">{{ trans('words.انثي') }}</option>
            <option @selected($package->gender == 'both') value="both">{{ trans('words.ذكر او انثي') }}</option>

        </x-form.select>


        <x-form.input col="col-lg-3 col-6" value="{{ $package->price }}" required Type="number"
            label="{{ trans('words.سعر الحزمة') }}" name="price">
        </x-form.input>







        <x-form.button id="submitBtn" title="{{ trans('words.btn_update') }}"></x-form.button>



    </form>


    <div class="actions border-0 mt-3">
        <x-admin.layout.back title="{{ trans('words.المنتجات') }}"></x-admin.layout.back>
        <x-cr_button title="{{ trans('words.إضافة منتج') }}"></x-cr_button>

    </div>


    <div class="tableSpace">
        <table id="sortable-table">
            <thead>

                <tr>
                    <th> {{ trans('words.صورة المنتج') }}</th>
                    <th>{{ trans('words.اسم المنتج') }}</th>
                    @can('has', 'products_action')
                        <th> {{ trans('words.actions') }}</th>
                    @endcan
                </tr>

            </thead>

            <tbody>


                @forelse ($package->products as $product)
                    <tr>



                        @php
                            if (isset($product->img)) {
                                $img = str_replace('public', 'storage', $product->img);
                                $img = asset("$img");
                            } else {
                                $img = 'https://placehold.co/600x400?text=product+img';
                            }

                        @endphp

                        <td><img width="80" height="60" style="object-fit: contain" src="{{ $img }}"></td>


                        <td data-text='اسم المنتج'>
                            {{ $product->name }}
                        </td>


                        <x-td_end normal id="{{ $product->id }}" name="{{ $product->name }}">
                        </x-td_end>

                    </tr>

                @empty
                    <tr>

                        <td colspan="4">{{ trans('words.no_data') }}</td>
                    </tr>
                @endforelse


            </tbody>
        </table>

    </div>


    <x-form.delete title="{{ trans('words.المنتج') }}" path="admin/packages/{{$package->id}}/products/destroy"></x-form.delete>



    <x-form.create_model img id="createModel" title="{{ trans('words.إضافة منتج') }}" path="admin/packages/products">
        <div class="products">

            <input type="hidden" name="package_id" value="{{ $package->id }}">

            @foreach ($products as $product)
                <div class="product">
                    <img src="{{ path("$product->img") }}">

                    <div class="product_body">
                        <div class="product_name">
                            <p>{{ $product->name }}</p>
                        </div>
                    </div>

                    <input type="checkbox" name="products[]" value="{{ $product->id }}" hidden>

                </div>
            @endforeach

        </div>
    </x-form.create_model>
@endsection


@section('js')
    <script>
        $('aside .packages').addClass('active');
        $('.modelSelect').select2();

        document.querySelectorAll('.product').forEach(product => {
            product.addEventListener('click', function() {
                const checkbox = this.querySelector('input[type="checkbox"]');

                // Toggle selected class and checkbox status
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    checkbox.checked = false;
                } else {
                    this.classList.add('selected');
                    checkbox.checked = true;
                }
            });
        });


                $(document).ready(function () {
          $('.modelSelect').each(function () {
              const $this = $(this);
              const isMultiple = $this.attr('multiple') !== undefined;

              $this.select2({
                  dropdownParent: $this.closest('.modal-content').length
                      ? $this.closest('.modal-content')
                      : $(document.body),
                  closeOnSelect: !isMultiple // مهم جدًا علشان ميقفلش القائمة بعد أول اختيار
              });
          });
        });
    </script>
@endsection
