@props(["path" , "title"])

<div class="modal fade" id="deleteModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="/{{$path}}">

            <input type="hidden" name="delete_id" id="delete_id">


            @method("delete")
            @csrf
            <div class="modal-header py-2 ">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> <svg style="margin-left: 5px" width="22"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#c23d2f" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                            clip-rule="evenodd"></path>
                    </svg> {{trans("words.delete")}} {{$title}} </h1>

                <x-form.button type="button" icon="close" class="close" data-bs-dismiss="modal"
                    aria-label="Close"></x-form.button>

            </div>
            <div class="modal-body" style="padding: 15px">
                {{trans("words.be_deleted")}} : <strong style="font-size: 14px" id="delete_title"></strong>


                <div class="my-3">
                    {{$slot}}
                </div>



                <div class="d-flex align-items-center my-3">
                    <input style="margin-left:10px;margin-right: 10px" type="checkbox" name="confirm" id="confirmCheckBox">
                    <label for="confirmCheckBox"> {{trans('words.confirm_deletion')}} {{$title}}</label>
                </div>
            </div>
            <div class="modal-footer">
                <x-form.button class="delete" id="deleteBtn" disabled title="{{trans('words.delete')}}"></x-form.button>
                <x-form.button class="close" type="button" data-bs-dismiss="modal" title="{{trans('words.close')}}"></x-form.button>
            </div>
        </form>
    </div>
</div>
