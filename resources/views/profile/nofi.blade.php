<div class="contnet-title"> اعدادات الاشعارات</div>

<form class="row bg-white  py-2 px-2 rounded" action="{{ url('users/notification_settings') }}" method="post"
    enctype="multipart/form-data">

    @php
        $status = [
            'قيد المراجعة',
            'تم الالغاء',
            'محاولة التأكيد',
            'تم التأكيد',
            'تحت التحضير',
            'تم التحضير',
            'في الشحن',
            'مجدولة',
            'تم التوصيل',
            'رفض الاستلام',
            'مرتجع',
            'طلب استبدال',
            'طلب استرداد نقدي',
            'مكتمل',
        ];
    @endphp



    @foreach ($status as $state)
        <x-notification notification="{{ $state}}"></x-notification>
    @endforeach





    @csrf
    @method('put')

    <div class="col-12">
        <x-form.button type="submit"  title="تعديل اﻻشعارات" class="mt-3"></x-form.button>
    </div>

</form>
