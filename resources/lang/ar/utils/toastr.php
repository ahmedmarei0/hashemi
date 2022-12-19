<?php


$messages = [

    // general messages
    'login_error' => 'محاولة تسجيل دخول خاظئة الرجاء التأكد من البيانات المدخلة',
    'process_success_message' => 'تمت العملية بنجاح',
    'successful_process_message' => 'عملية ناجحة',
    'store_success_message' => 'تمت الإضافة بنجاح',
    'update_success_message' => 'تم التعديل بنجاح',
    'destroy_success_message' => 'تم الحذف بنجاح',

];

// Entity Name => Display Text
$entities = [
    'login' => 'تسجيل دخول',
    'article' => 'المقال',
    'category' => 'القسم',
    'contact' => 'طلب التواصل',
];

foreach ($entities as $entity_name => $display_text) {
    $messages[$entity_name . '_trying_login'] = 'محاولة ' . $display_text ;
    $messages[$entity_name . '_store_success_message'] = 'تم إضافة ' . $display_text . ' بنجاح';
    $messages[$entity_name . '_update_success_message'] = 'تم تحديث ' . $display_text . ' بنجاح';
    $messages[$entity_name . '_destroy_success_message'] = 'تم حذف ' . $display_text . ' بنجاح';
}


return $messages;
