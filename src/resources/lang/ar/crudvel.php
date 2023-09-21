<?php

$cvGlobalSpecials = [
  'client-web-app'    => 'إذن لطلب الرمز عبر تطبيق الويب العميل',
  'client-mobile-app' => 'إذن لطلب الرمز عبر تطبيق الجوال',
  'code-hooks'        => 'إذن لإدارة الخطافات في الشفرة',
  'inactives'         => 'إذن للوصول إلى السجلات غير النشطة',
];

$cvSections =  [
  'additionals' => 'إضافيات',
  'catalogs'    => 'الفهارس',
  'dashboard'   => 'لوحة القيادة',
  'files'       => 'الملفات',
  'main'        => 'الرئيسية',
  'profile'     => 'الملف الشخصي',
  'security'    => 'الأمان',
  'settings'    => 'الإعدادات',
];

$cvActions = [
  'index'  => [
    'call_message'   =>'قائمة',
    'called_message' =>'قائمة',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'show'   => [
    'call_message'   =>'عرض',
    'called_message' =>'عرض',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'create' => [
    'call_message'   =>'عرض الإنشاء',
    'called_message' =>'إنشاء',
    'next_message'   =>'حفظ',
    'success'        =>'تم الإنشاء',
    'for'            =>'(العميل)',
  ],

  'add' => [
    'call_message'   =>'إضافة',
    'called_message' =>'إضافة',
    'success'        =>'تمت الإضافة',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'store'  => [
    'call_message'   =>'إنشاء',
    'called_message' =>'إنشاء',
    'success'        =>'تم الإنشاء',
    'for'            =>'(واجهة برمجة التطبيقات)',
  ],

  'edit'   => [
    'call_message'   =>'عرض التحرير',
    'called_message' =>'تحرير',
    'next_message'   =>'تحديث',
    'for'            =>'(العميل)',
  ],

  'update' => [
    'call_message'   =>'تحديث',
    'called_message' =>'تحديث',
    'success'        =>'تم التحديث',
    'for'            =>'(واجهة برمجة التطبيقات)',
  ],

  'delete' => [
    'call_message'       =>'عرض الحذف',
    'success'            =>'تم الحذف',
    'confirmation_alert' =>'سيتم حذف السجل',
    'for'                =>'(العميل)',
  ],

  'destroy' => [
    'call_message'       =>'حذف',
    'success'            =>'تم الحذف',
    'confirmation_alert' =>'سيتم حذف السجل',
    'for'                =>'(واجهة برمجة التطبيقات)',
  ],

  'activate' => [
    'call_message' =>'تنشيط',
    'success'      =>'تم التنشيط',
    'for'          =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'deactivate' => [
    'call_message' =>'إلغاء التنشيط',
    'success'      =>'تم إلغاء التنشيط',
    'for'          =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'import' => [
    'call_message'   =>'عرض الاستيراد',
    'called_message' =>'استيراد',
    'main_label'     =>'ملف للاستيراد (إكسل)',
    'next_message'   =>'استيراد',
    'for'            =>'(العميل)',
  ],

  'importing' => [
    'call_message'   =>'استيراد',
    'success'        =>'تم الاستيراد',
    'for'            =>'(واجهة برمجة التطبيقات)',
  ],

  'export' => [
    'call_message'   =>'عرض التصدير',
    'called_message' =>'تصدير',
    'next_message'   =>'إنشاء ملف تصدير إكسل',
    'success'        =>'تم التصدير',
    'for'            =>'(العميل)',
  ],

  'exporting' => [
    'call_message'   =>'تصدير',
    'called_message' =>'تصدير',
    'next_message'   =>'إنشاء ملف تصدير إكسل',
    'success'        =>'تم التصدير',
    'for'            =>'(واجهة برمجة التطبيقات)',
  ],

  'exportings' => [
    'call_message'   =>'تصدير الكل',
    'called_message' =>'تصدير الكل',
    'next_message'   =>'إنشاء ملف تصدير إكسل',
    'success'        =>'تم تصدير الكل',
    'for'            =>'(واجهة برمجة التطبيقات)',
  ],

  'relatedIndex' => [
    'call_message'   =>'العلاقات',
    'called_message' =>'علاقات',
    'next_message'   =>'حفظ العلاقات',
    'success'        =>'السجلات المرتبطة',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'indexOwnedBy' => [
    'call_message'   =>'مالك السجلات',
    'called_message' =>'علاقات',
    'next_message'   =>'حفظ العلاقات',
    'success'        =>'السجلات المرتبطة',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'sluged' => [
    'call_message'   =>'القائمة المرتبة حسب الروابط',
    'called_message' =>'الحصول على القائمة حسب الروابط',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],
  'unauthorized' => [
    'call_message'   =>'تحميل قيود الصلاحيات',
    'called_message' =>'جارٍ تحميل قيود الصلاحيات',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'permissions' => [
    'call_message'   =>'تحميل الصلاحيات حسب الدور',
    'called_message' =>'جارٍ تحميل الصلاحيات حسب الدور',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'roles' => [
    'call_message'   =>'تحميل الأدوار حسب المستخدم',
    'called_message' =>'جارٍ تحميل الأدوار حسب المستخدم',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'profile' => [
    'call_message'   =>'تحميل معلومات المستخدم',
    'called_message' =>'جارٍ تحميل معلومات المستخدم',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'logout' => [
    'call_message'   =>'تسجيل الخروج',
    'called_message' =>'جارٍ تسجيل الخروج',
    'next_message'   =>'تسجيل الخروج',
    'success'        =>'تم تسجيل الخروج',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'updateProfile' => [
    'call_message'   =>'تحديث معلومات المستخدم',
    'called_message' =>'جارٍ تحديث معلومات المستخدم',
    'next_message'   =>'تحديث معلومات المستخدم',
    'success'        =>'تم تحديث معلومات المستخدم',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'dashboardInfo' => [
    'call_message'   =>'تحميل معلومات اللوحة الإدارية',
    'called_message' =>'جارٍ تحميل معلومات اللوحة الإدارية',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'resources' => [
    'call_message'   =>'الحصول على الموارد ذات الصلة',
    'called_message' =>'جارٍ الحصول على الموارد ذات الصلة',
    'for'            =>'(واجهة برمجة التطبيقات.العميل)',
  ],

  'resourcer' => [
    'call_message'   =>'الحصول على ملفات الموارد',
    'called_message' =>'جارٍ الحصول على ملفات الموارد',
    'for'            =>'(واجهة برمجة التطبيقات.العميل)',
  ],

  'register' => [
    'call_message'   =>'التسجيل',
    'called_message' =>'جارٍ التسجيل',
    'success'        =>'تم التسجيل',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'recovery' => [
    'call_message'   =>'استعادة',
    'called_message' =>'جارٍ الاستعادة',
    'success'        =>'تمت الاستعادة',
    'for'            =>'(واجهة برمجة التطبيقات والعميل)',
  ],

  'storeUpdate'  => [
    'call_message'   =>'إنشاء أو تحرير',
    'called_message' =>'إنشاء أو تحرير',
    'success'        =>'تم التحرير',
    'for'            =>'(واجهة برمجة التطبيقات)',
  ],

  'zippedResource' => [
    'call_message'   => 'تنزيل الملفات المضغوطة',
    'called_message' => 'جارٍ تنزيل الملفات المضغوطة',
    'success'        => 'تم تنزيل الملفات المضغوطة',
    'for'            => '(واجهة برمجة التطبيقات والعميل)',
  ],
];

$cvSpecials=[
  'inactives'            => 'غير نشط',
  'general-owner'        => 'المالك العام',
  'particular-owner'     => 'المالك الخاص',
  'files-settings'       => 'إعدادات ملفات المورد',
  'index-files'          => 'قائمة ملفات المورد',
  'show-files'           => 'عرض ملفات المورد',
  'create-files'         => 'عرض إنشاء ملفات المورد',
  'store-files'          => 'إنشاء ملفات المورد',
  'edit-files'           => 'عرض تحرير ملفات المورد',
  'update-files'         => 'تحديث ملفات المورد',
  'delete-files'         => 'عرض حذف ملفات المورد',
  'destroy-files'        => 'حذف ملفات المورد',
  'zippedResource-files' => 'تنزيل ملفات مضغوطة',
  'code-hooks'           => 'تعيين خطافات الشفرة',
];

$cvActionsExtra = [
  'common'=>[
    'cancel'    => 'إلغاء',
    'back'      => 'رجوع',
    'confirm'   => 'هل أنت متأكد؟',
    'correctly' => 'بنجاح',
    'of'        => 'من',
  ],

  'label'=>'الإجراءات',

  'status'=>[
    'yes' =>'نعم',
    'no'  =>'لا',
  ],
];

$cvCrudvel = [
  'context_permission'=>'الإذن ينطبق على',
];

$cvWeb = [
  'unauthorized'         => 'ليس لديك إذن لاستخدام هذا الإجراء',
  'has_no_permsissions' => 'ليس لديك إذن لاستخدام هذا الإجراء',
  'operation_error'     => 'تعذر إجراء العملية، يُرجى المحاولة لاحقًا',
  'transaction-error'   => 'حدث خطأ أثناء تنفيذ العملية',
  'success'             => 'تمت العملية بنجاح',
  'not_found'           => 'لم يتم العثور على المورد',
  'error'               => 'حدث خطأ غير متوقع',
  'file_error'          => 'حدث خطأ أثناء محاولة الوصول إلى الملف',
  'already_exist'       => 'تم تسجيله بالفعل',
  'validation_errors'   => 'لم يتم اجتياز الاختبارات الصحيحة',
];

$cvApi = [
  'unauthorized'           => 'ليس لديك إذن لاستخدام هذا الإجراء',
  'has_no_permsissions'   => 'ليس لديك إذن لاستخدام هذا الإجراء',
  'operation_error'       => 'تعذر إجراء العملية، يُرجى المحاولة لاحقًا',
  'transaction-error'     => 'حدث خطأ أثناء تنفيذ العملية',
  'not_found'             => 'لم يتم العثور على المورد',
  'error'                 => 'حدث خطأ غير متوقع',
  'file_error'            => 'حدث خطأ أثناء محاولة الوصول إلى الملف',
  'already_exist'         => 'تم تسجيله بالفعل',
  'validation_errors'     => 'لم يتم اجتياز الاختبارات الصحيحة',
  'logget_out'            => 'لقد قمت بتسجيل الخروج',
  'success'               => 'تمت العملية بنجاح',
  'incomplete'            => 'العملية غير مكتملة',
  'bad_paginate_petition' => 'معلمات الترقيم غير صحيحة، يتم الرد بقيم افتراضية',
  'unproccesable'         => 'معلومات غير متسقة',
  'miss_configuration'    => 'الخدمة غير مكونة بشكل صحيح',
  'true'                  => 'صحيح',
  'false'                 => 'خاطئ',
  'no_files_to_zip'       => 'لا توجد ملفات للمورد.',
  'no_cache_property'     => 'تعذر الوصول إلى خاصية',
];
