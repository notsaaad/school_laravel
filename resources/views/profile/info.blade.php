<form class="row" action="{{ url('/profile/info') }}" method="post" enctype="multipart/form-data">

    @csrf
    @method('put')

    @if (auth()->user()->role == 'user')
        <x-form.input required disabled label="Your Id" name="refrence" value="{{ auth()->user()->code }}">
        </x-form.input>
    @endif

    <x-form.input required disabled label="Your Name" name="name" value="{{ auth()->user()->name }}">
    </x-form.input>

    <x-form.input required label="Email" name="email" type="email" value="{{ auth()->user()->email }}">
    </x-form.input>


    <x-form.input required label="Phone" name="mobile" value="{{ auth()->user()->mobile }}">
    </x-form.input>



    <x-form.input type="file" label="Account Image" name="img" class="form-control"></x-form.input>



    <div class="d-flex justify-content-end">

        <a class="delete" href="/delete_img">
            Remove Account Image
        </a>

    </div>


    <div class="col-12">
        <x-form.button id="submitBtn" type="submit" title="Update" class="mt-3"></x-form.button>
    </div>




</form>
