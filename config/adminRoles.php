<?php

return [
    'permissions' => [
        "المستخدمين" => [
          "عرض المستخدمين"                      => "users_show",
          "الاجراءات"                            => "users_action",
          "تسجيل الدخول علي اكونت المستخدم"   => "user_login"
        ],

        "المراحل" => "stages",


        "الطلاب" => [
          "عرض الطلاب" => "students_show",
          "الاجراءات" => "students_action",  "تسجيل الدخول علي اكونت الطالب" => "students_login"],

        "المنتجات" => ["عرض المنتجات" => "products_show", "الاجراءات" => "products_action"],

        "حزم المنتجات" => ["عرض الحزم" => "packages_show", "الاجراءات" => "packages_action"],


        "الطلبات" => [
            "عرض الطلبات" => "show_orders",
            "عرض  محتوي الاوردر" => "show_in_order",
            "عرض  محتوي  عند فتح الاوردر" => "show_in_order_in_show_page",
            "تعديل المقاسات" => "update_size",
            "الغاء الاوردر" => "cancel_order",
            "تسوية الاوردر" => "order_payment",
            "تسليم الاوردر" => "picking_order",
            "استرجاع منتج" => "return_product",
            "استرجاع الاوردر" => "return_requested",
            "عرض سداد المتسحقات ام لا"  => "show_sending_received",
            'اضافة منتج للطلب' => 'order_add_item',
            'مسح الطلب'         => 'delete_order'

        ],

        "التحويلات" => [
            "عرض التحويلات" => "show_transfers",
            "اضافة وتعديل وحذف" => "actions_transfers",
            "دفع" => "paid_transfers",
            "استلام" => "confirm_transfers",
        ],

        "المصاريف" => [
            "عرض المصاريف" => "fees_show",
            "استلام نقدية" => "fees_paid",
        ],

        "تعريفات التقديمات" => "sec1",



        "التقديم" => [
            "مصاريف التقديم" => "fees_index",
            "  مصاريف التقديم [ اضافة - حذف - اضافة ] " => "fees_actions",
            "الاختبارات" => "tests",
            "التقديمات" => "applications_show",
            "  التقديمات [ اضافة - حذف - اضافة ] " => "applications_actions",
            "new" => "new",
            "paid" => "paid",
            "complate" => "complate",
            "returned" => "returned",
            "canceled" => "canceled",
            "عرض الاختبارات داخل التقديمات" => "tests_applications_index",
            "تغير حالة الاختبارات في التقديمات" => "tests_applications_actions"


        ],



        "الصلاحيات" => "roles",
        "البنرات الاعلانية" => "ads",
        "التعريفات" => "definitions",


        "المخازن" => "warehouses",
        "الفواتير" => "invoices",


        "اعدادات الموقع" => [
            "العلامة التجارية" => "branding",
            "السنوات الدراسية" => "year",
            "المحافظات والمناطق" => "regions",
            "اعدادات مالية" => "other",
            "الحالات الخاصة" => "custom_status",
            "الحقول المخصصة" => "dynamic_fields",
        ],


    ]
];
