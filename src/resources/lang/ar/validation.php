<?php

$cvValidations = [

  /*
  |--------------------------------------------------------------------------
  | Validation Language Lines
  |--------------------------------------------------------------------------
  |
  | The following language lines contain the default error messages used by
  | the validator class. Some of these rules have multiple versions such
  | as the size rules. Feel free to tweak each of these messages here.
  |
  */

  'accepted'                   => 'يجب قبول الحقل [:attribute].',
  'active_url'                 => 'الحقل [:attribute] ليس رابطًا صالحًا.',
  'after'                      => 'الحقل [:attribute] يجب أن يكون تاريخًا لاحقًا من :date.',
  'alpha'                      => 'الحقل [:attribute] يجب أن يحتوي فقط على الأحرف.',
  'alpha_dash'                 => 'الحقل [:attribute] يجب أن يحتوي فقط على الأحرف والأرقام والشرطات.',
  'alpha_num'                  => 'الحقل [:attribute] يجب أن يحتوي فقط على الأحرف والأرقام.',
  'alpha_spaces'               => 'الحقل [:attribute] يجب أن يحتوي فقط على الأحرف والمسافات.',
  'array'                      => 'الحقل [:attribute] يجب أن يكون مصفوفة.',
  'before'                     => 'الحقل [:attribute] يجب أن يكون تاريخًا قبل :date.',
  'between'                    => [
    'numeric' => 'الحقل [:attribute] يجب أن يكون بين :min و :max.',
    'file'    => 'الحقل [:attribute] يجب أن يكون حجم الملف بين :min و :max كيلوبايت.',
    'string'  => 'الحقل [:attribute] يجب أن يكون بين :min و :max أحرف.',
    'array'   => 'الحقل [:attribute] يجب أن يحتوي على عدد من العناصر بين :min و :max.',
  ],
  'boolean'                    => 'الحقل [:attribute] يجب أن يكون قيمة صحيحة أو خاطئة.',
  'confirmed'                  => 'تأكيد الحقل [:attribute] غير متطابق.',
  'date'                       => 'الحقل [:attribute] ليس تاريخًا صالحًا.',
  'date_format'                => 'الحقل [:attribute] لا يتوافق مع الصيغة :format.',
  'different'                  => 'الحقل [:attribute] و :other يجب أن يكونا مختلفين.',
  'digits'                     => 'الحقل [:attribute] يجب أن يحتوي على :digits أرقام.',
  'digits_between'             => 'الحقل [:attribute] يجب أن يحتوي على عدد من الأرقام بين :min و :max.',
  'email'                      => 'الحقل [:attribute] ليس بريدًا إلكترونيًا صالحًا',
  'exists'                     => 'الحقل [:attribute] غير صالح.',
  'filled'                     => 'الحقل [:attribute] مطلوب.',
  'hex_color'                  => 'الحقل [:attribute] يجب أن يكون قيمة هكس مالية صحيحة.',
  'image'                      => 'الحقل [:attribute] يجب أن يكون ملف صورة.',
  'in'                         => 'الحقل [:attribute] غير صالح.',
  'integer'                    => 'الحقل [:attribute] يجب أن يكون عددًا صحيحًا.',
  'ip'                         => 'الحقل [:attribute] يجب أن يكون عنوان IP صحيحًا.',
  'max'                        => [
    'numeric' => 'الحقل [:attribute] يجب أن لا يتجاوز :max.',
    'file'    => 'الحقل [:attribute] يجب أن لا يتجاوز حجم الملف :max كيلوبايت.',
    'string'  => 'الحقل [:attribute] يجب أن لا يتجاوز :max أحرف.',
    'array'   => 'الحقل [:attribute] يجب أن لا يحتوي على أكثر من :max عنصر.',
  ],
  'mimes'                      => 'الحقل [:attribute] يجب أن يكون ملفًا من النوع: :values.',
  'min'                        => [
    'numeric' => 'حجم الحقل [:attribute] يجب أن يكون على الأقل :min.',
    'file'    => 'حجم الملف في الحقل [:attribute] يجب أن يكون على الأقل :min كيلوبايت.',
    'string'  => 'الحقل [:attribute] يجب أن يحتوي على على الأقل :min أحرف.',
    'array'   => 'الحقل [:attribute] يجب أن يحتوي على على الأقل :min عنصر.',
  ],
  'not_in'                     => 'الحقل [:attribute] غير صالح.',
  'not_only_digits'            => 'الحقل [:attribute] لا يجب أن يحتوي على أرقام فقط.',
  'not_special'                => 'الحقل [:attribute] لا يجب أن يحتوي على أحرف خاصة.',
  'numeric'                    => 'الحقل [:attribute] يجب أن يكون قيمة رقمية.',
  'regex'                      => 'صيغة الحقل [:attribute] غير صالحة.',
  'required'                   => 'الحقل [:attribute] مطلوب.',
  'required_if'                => 'الحقل [:attribute] مطلوب عندما :other يكون :value.',
  'required_with'              => 'الحقل [:attribute] مطلوب عندما يكون :values موجودًا.',
  'required_with_all'          => 'الحقل [:attribute] مطلوب عندما تكون :values موجودًا.',
  'required_without'           => 'الحقل [:attribute] مطلوب عندما لا يكون :values موجودًا.',
  'required_without_all'       => 'الحقل [:attribute] مطلوب عندما لا تكون أي من :values موجودًا.',
  'same'                       => 'الحقل [:attribute] و :other يجب أن يتطابقا.',
  'size'                       => [
    'numeric' => 'حجم الحقل [:attribute] يجب أن يكون :size.',
    'file'    => 'حجم الملف في الحقل [:attribute] يجب أن يكون :size كيلوبايت.',
    'string'  => 'الحقل [:attribute] يجب أن يحتوي على :size أحرف.',
    'array'   => 'الحقل [:attribute] يجب أن يحتوي على :size عنصر.',
  ],
  'timezone'                   => 'الحقل [:attribute] يجب أن يكون منطقة زمنية صحيحة.',
  'unique'                     => 'الحقل [:attribute] تم تسجيله بالفعل.',
  'unique_with'                => 'الحقل [:attribute] تم تسجيله بالفعل في :fields.',
  'url'                        => 'صيغة الحقل [:attribute] غير صالحة.',
  'youtube'                    => 'الحقل [:attribute] يجب أن يكون فيديو يوتيوب صالح.',
  'compositives'               => 'يوجد بالفعل إشارة إلى [:attribute].',
  'list_exist'                 => 'بعض القيم في القائمة [:attribute] لا تنتمي إلى الفهرس.',
  'no_duplicate_values'        => 'هناك قيم مكررة في [:attribute].',
  'user_name'                  => 'الحقل [:attribute] يقبل فقط "الأحرف" و"الأرقام" والرموز التالية: . - _ @',
  'color'                      => 'لون غير صالح',
  'rfc'                        => 'الحقل RFC ليس لديه تنسيق صحيح (3 أحرف، 6 أرقام، 2 أو 3 أحرف)',
  'key_exist'                  => 'القيمة غير متطابقة مع الفهرس',
  'in_list'                    => 'القيمة [:attribute] غير متطابقة مع القيم الصالحة',
  'no_simultaneous'            => 'القيم [:list1] في الحقل [:attribute] لا يمكن أن تتشارك مع [:list2]',
  'file_already_exist'         => 'الملف موجود بالفعل',
  'file_resource'              => 'ليس لديك الأذونات لربط هذا النوع من الملفات',
  'unique_combination'         => 'الحقل [:attribute] في التركيبة مع :fields يجب أن يكون فريدًا',
  'cv_true'                    => 'الحقل [:attribute] يجب أن يحتوي على قيمة صحيحة',
  'cv_false'                   => 'الحقل [:attribute] يجب أن يحتوي على قيمة خاطئة',
  'cv_boolean_inverse'         => 'الحقل [:attribute] يجب أن يكون [:boolean1] عندما يكون الحقل [:other] [:boolean2]',
  'cv_boolean_equal'           => 'الحقل [:attribute] يجب أن يكون [:boolean1] عندما يكون الحقل [:other] [:boolean2]',
  'cv_true_when_true'          => 'الحقل [:attribute] يجب أن يكون صحيحًا عندما يكون الحقل [:other] صحيحًا',
  'cv_true_when_false'         => 'الحقل [:attribute] يجب أن يكون صحيحًا عندما يكون الحقل [:other] خاطئًا',
  'cv_false_when_true'         => 'الحقل [:attribute] يجب أن يكون خاطئًا عندما يكون الحقل [:other] صحيحًا',
  'cv_false_when_false'        => 'الحقل [:attribute] يجب أن يكون خاطئًا عندما يكون الحقل [:other] خاطئًا',
  'cv_true_when_greater_than'  => 'الحقل [:attribute] يجب أن يكون صحيحًا عندما يكون الحقل [:other] أكبر من [:limit]',
  'cv_false_when_greater_than' => 'الحقل [:attribute] يجب أن يكون خاطئًا عندما يكون الحقل [:other] أكبر من [:limit]',
  'cv_true_when_less_than'     => 'الحقل [:attribute] يجب أن يكون صحيحًا عندما يكون الحقل [:other] أصغر من [:limit]',
  'cv_false_when_less_than'    => 'الحقل [:attribute] يجب أن يكون خاطئًا عندما يكون الحقل [:other] أصغر من [:limit]',
  'cv_greater_than_when_true'  => 'الحقل [:attribute] يجب أن يحتوي على قيمة أكبر من [:limit] عندما يكون الحقل [:other] صحيحًا',
  'cv_greater_than_when_false' => 'الحقل [:attribute] يجب أن يحتوي على قيمة أكبر من [:limit] عندما يكون الحقل [:other] خاطئًا',
  'cv_less_than_when_true'     => 'الحقل [:attribute] يجب أن يحتوي على قيمة أصغر من [:limit] عندما يكون الحقل [:other] صحيحًا',
  'cv_less_than_when_false'    => 'الحقل [:attribute] يجب أن يحتوي على قيمة أصغر من [:limit] عندما يكون الحقل [:other] خاطئًا',
  'cv_greater_than_with'       => 'الحقل [:attribute] يجب أن يحتوي على قيمة أكبر من [:limit] عندما يكون الحقل [:other] موجودًا',
  'cv_less_than_with'          => 'الحقل [:attribute] يجب أن يحتوي على قيمة أصغر من [:limit] عندما يكون الحقل [:other] موجودًا',
  'cv_greater_than'            => 'الحقل [:attribute] يجب أن يحتوي على قيمة أكبر من [:limit]',
  'cv_less_than'               => 'الحقل [:attribute] يجب أن يحتوي على قيمة أصغر من [:limit]',
  'cv_slugged'                 => 'الحقل [:attribute] يجب أن يحتوي على صيغة Slug.',
  'key_exist_v2'               => 'القيمة غير متطابقة مع المورد [:resource]',
  //crudvel acopled validations
  'cv_key_exist'               => 'القيمة غير متطابقة مع الفهرس',

  /*
  |--------------------------------------------------------------------------
  | Custom Validation Language Lines
  |--------------------------------------------------------------------------
  |
  | Here you may specify custom validation messages for attributes using the
  | convention 'attribute.rule' to name the lines. This makes it quick to
  | specify a specific custom language line for a given attribute rule.
  |
  */


  'custom' => [
    "There was an error on row :row. :message" => "حدث خطأ في الصف :row. :message",
    'attribute-name'                           => [
      "There was an error on row :row. :message" => "حدث خطأ في الصف :row. :message",
      'rule-name'                                => 'custom-message',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Custom Validation Attributes
  |--------------------------------------------------------------------------
  |
  | The following language lines are used to swap attribute place-holders
  | with something more reader friendly such as E-Mail Address instead
  | of 'email'. This simply helps us make messages a little cleaner.
  |
  */

  'attributes'                               => [],
  'message'                                  => 'تم العثور على أخطاء في المعلومات المقدمة',
  "There was an error on row :row. :message" => "حدث خطأ في الصف :row. :message",
];


return $cvValidations;
