<form class="row" action="{{ url('/profile/password') }}" method="post">

    @csrf
    @method('put')


    <x-form.input type="password" notRequired for="passwordOld" label="{{ trans('users.old_password') }}"
        name="passwordOld">
    </x-form.input>

    <x-form.input type="password" notRequired for="password" label="{{ trans('users.new_password') }}" name="password">
    </x-form.input>

    <x-form.input type="password" notRequired for="password_confirmation"
        label="{{ trans('users.confirm_new_password') }}" name="password_confirmation">
    </x-form.input>




    <div class="col-12">
        <x-form.button type="submit" title="{{ trans('words.btn_update') }}" class="mt-3"></x-form.button>
    </div>



</form>
