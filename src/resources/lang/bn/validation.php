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

  'accepted'                   => '[:attribute] ফিল্ডটি গ্রহণযোগ্য হতে হবে।',
  'active_url'                 => '[:attribute] ফিল্ডটি একটি বৈধ URL নয়।',
  'after'                      => '[:attribute] ফিল্ডটি [:date] তারিখের পরে হতে হবে।',
  'alpha'                      => '[:attribute] ফিল্ডটি কেবলমাত্র অক্ষর ধারণ করতে পারে।',
  'alpha_dash'                 => '[:attribute] ফিল্ডটি কেবলমাত্র অক্ষর, সংখ্যা এবং ড্যাশ ধারণ করতে পারে।',
  'alpha_num'                  => '[:attribute] ফিল্ডটি কেবলমাত্র অক্ষর এবং সংখ্যা ধারণ করতে পারে।',
  'alpha_spaces'               => '[:attribute] ফিল্ডটি কেবলমাত্র অক্ষর এবং স্পেস ধারণ করতে পারে।',
  'array'                      => '[:attribute] ফিল্ডটি একটি অ্যারে হতে হবে।',
  'before'                     => '[:attribute] ফিল্ডটি [:date] তারিখের আগে হতে হবে।',
  'between'                    => [
    'numeric' => '[:attribute] ফিল্ডটি [:min] - [:max] মধ্যে হতে হবে।',
    'file'    => '[:attribute] ফিল্ডটি [:min] - [:max] কিলোবাইটের মধ্যে হতে হবে।',
    'string'  => '[:attribute] ফিল্ডটি [:min] - [:max] টি অক্ষরের মধ্যে হতে হবে।',
    'array'   => '[:attribute] ফিল্ডটি [:min] - [:max] টি আইটেম ধারণ করতে হবে।',
  ],
  'boolean'                    => '[:attribute] ফিল্ডটি সত্য বা মিথ্যা মান ধারণ করতে হবে।',
  'confirmed'                  => '[:attribute] নিশ্চিতকরণ [:attribute] সাথে মিলছে না।',
  'date'                       => '[:attribute] ফিল্ডটি একটি বৈধ তারিখ নয়',
  'date_format'                => '[:attribute] ফিল্ডটি ফরম্যাট [:format] এর সাথে মেলে না।',
  'different'                  => '[:attribute] এবং :other ফিল্ডগুলি আলাদা হতে হবে।',
  'digits'                     => '[:attribute] ফিল্ডটি [:digits] সংখ্যার হতে হবে।',
  'digits_between'             => '[:attribute] ফিল্ডটি [:min] এবং [:max] সংখ্যার মধ্যে হতে হবে।',
  'email'                      => '[:attribute] ফিল্ডটি একটি বৈধ ইমেইল নয়',
  'exists'                     => '[:attribute] ফিল্ডটি অবৈধ',
  'filled'                     => '[:attribute] ফিল্ডটি আবশ্যক।',
  'hex_color'                  => '[:attribute] ফিল্ডটি একটি বৈধ হেক্সাডেসিম্যাল মান ধারণ করতে হবে।',
  'image'                      => '[:attribute] ফিল্ডটি একটি চিত্র ফাইল হতে হবে।',
  'in'                         => '[:attribute] ফিল্ডটি অবৈধ।',
  'integer'                    => '[:attribute] ফিল্ডটি একটি পূর্ণাংক হতে হবে।',
  'ip'                         => '[:attribute] ফিল্ডটি একটি বৈধ IP ঠিকানা হতে হবে।',
  'max'                        => [
    'numeric' => '[:attribute] ফিল্ডটি [:max] এর চেয়ে বড় হতে পারে না।',
    'file'    => '[:attribute] ফিল্ডটি [:max] কিলোবাইটের চেয়ে বড় হতে পারে না।',
    'string'  => '[:attribute] ফিল্ডটি [:max] টি অক্ষরের চেয়ে বড় হতে পারে না।',
    'array'   => '[:attribute] ফিল্ডটি [:max] আইটেমের চেয়ে বেশি সংখ্যক আইটেম ধারণ করতে পারে না।',
  ],
  'mimes'                      => '[:attribute] ফিল্ডটি একটি ফরম্যাটে ফাইল হতে হবে: :values।',
  'min'                        => [
    'numeric' => '[:attribute] ফিল্ডটি অন্তত [:min] হতে হবে।',
    'file'    => '[:attribute] ফিল্ডটি অন্তত [:min] কিলোবাইটের হতে হবে।',
    'string'  => '[:attribute] ফিল্ডটি অন্তত [:min] টি অক্ষরের হতে হবে।',
    'array'   => '[:attribute] ফিল্ডটি অন্তত [:min] টি আইটেম ধারণ করতে হবে।',
  ],
  'not_in'                     => '[:attribute] ফিল্ডটি অবৈধ।',
  'not_only_digits'            => '[:attribute] ফিল্ডটি কেবলমাত্র সংখ্যা ধারণ করতে পারে না।',
  'not_special'                => '[:attribute] ফিল্ডটি বিশেষ বর্ণ ধারণ করতে পারে না।',
  'numeric'                    => '[:attribute] ফিল্ডটি সংখ্যাত্মক হতে হবে।',
  'regex'                      => '[:attribute] ফরম্যাটটি অবৈধ।',
  'required'                   => '[:attribute] ফিল্ডটি প্রয়োজন।',
  'required_if'                => '[:other] ফিল্ডটি [:value] হলে [:attribute] ফিল্ডটি প্রয়োজন।',
  'required_with'              => '[:values] উপস্থিত হলে [:attribute] ফিল্ডটি প্রয়োজন।',
  'required_with_all'          => '[:values] উপস্থিত হলে [:attribute] ফিল্ডটি প্রয়োজন।',
  'required_without'           => '[:values] উপস্থিত না হলে [:attribute] ফিল্ডটি প্রয়োজন।',
  'required_without_all'       => '[:values] উপস্থিত না হলে [:attribute] ফিল্ডটি প্রয়োজন।',
  'same'                       => '[:attribute] এবং :other ফিল্ডগুলি একই হতে হবে।',
  'size'                       => [
    'numeric' => '[:attribute] ফিল্ডটি [:size] হতে হবে।',
    'file'    => '[:attribute] ফিল্ডটি [:size] কিলোবাইটের হতে হবে।',
    'string'  => '[:attribute] ফিল্ডটি [:size] টি অক্ষরের হতে হবে।',
    'array'   => '[:attribute] ফিল্ডটি [:size] আইটেম ধারণ করতে হবে।',
  ],
  'timezone'                   => '[:attribute] একটি বৈধ টাইমজোন হতে হবে।',
  'unique'                     => '[:attribute] ফিল্ডটি ইতিমধ্যে নিবন্ধিত হয়েছে।',
  'unique_with'                => '[:attribute] ফিল্ডটি [:fields] এর সাথে ইতিমধ্যে নিবন্ধিত হয়েছে।',
  'url'                        => '[:attribute] ফরম্যাট অবৈধ।',
  'youtube'                    => '[:attribute] ফিল্ডটি একটি বৈধ ইউটিউব ভিডিও হতে হবে।',
  'compositives'               => '[:attribute] এর প্রতিস্থাপন আছে।',
  'list_exist'                 => '[:attribute] এর তালিকা একটি ক্যাটালগে অস্তিত্ব রাখে না',
  'no_duplicate_values'        => '[:attribute] এ কিছু সদুপরি মৌলিক মান আছে',
  'user_name'                  => '[:attribute] ফিল্ডটি কেবল \'অক্ষর\', \'সংখ্যা\' এবং নিম্নলিখিত চরণগুলি ধারণ করতে পারে: . - _ @ ',
  'color'                      => 'অবৈধ রং',
  'rfc'                        => '[:attribute] ফিল্ডটি বৈধ বিন্যাস নেই (3 অক্ষর, 6 সংখ্যা, 2 বা 3 অক্ষর)',
  'key_exist'                  => '[:attribute] মানটি ক্যাটালগ সাথে মেলে না',
  'in_list'                    => '[:attribute] মানটি বৈধ মানগুলির সাথে মেলে না',
  'no_simultaneus'             => '[:attribute] ফিল্ডে [:list1] মানগুলি [:list2] সাথে সমতল থাকতে পারে না',
  'file_already_exist'         => 'ফাইলটি ইতিমধ্যে বিদ্যমান',
  'file_resource'              => 'আপনার এই প্রকারের ফাইল সংযুক্ত করার অনুমতি নেই',
  'unique_combination'         => '[:attribute] ফিল্ডটি [:fields] এর সাথে ইতিমধ্যে একটি অননুমতিপ্রাপ্ত',
  'cv_true'                    => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে',
  'cv_false'                   => '[:attribute] ফিল্ডটি মিথ্যা মান ধারণ করতে হবে',
  'cv_boolean_inverse'         => '[:attribute] ফিল্ডটি যখন [:other] ফিল্ডটি [:boolean2] হলে [:boolean1] হতে হবে',
  'cv_boolean_equal'           => '[:attribute] ফিল্ডটি যখন [:other] ফিল্ডটি [:boolean2] হলে [:boolean1] হতে হবে',
  'cv_true_when_true'          => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি সত্য হতে হবে',
  'cv_true_when_false'         => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি মিথ্যা হতে হবে',
  'cv_false_when_true'         => '[:attribute] ফিল্ডটি মিথ্যা মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি সত্য হতে হবে',
  'cv_false_when_false'        => '[:attribute] ফিল্ডটি মিথ্যা মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি মিথ্যা হতে হবে',
  'cv_true_when_greater_than'  => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে বড় হতে হবে',
  'cv_false_when_greater_than' => '[:attribute] ফিল্ডটি মিথ্যা মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে বড় হতে হবে',
  'cv_true_when_less_than'     => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে ছোট হতে হবে',
  'cv_false_when_less_than'    => '[:attribute] ফিল্ডটি মিথ্যা মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে ছোট হতে হবে',
  'cv_greater_than_when_true'  => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে বড় হতে হবে',
  'cv_greater_than_when_false' => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে বড় হতে হবে',
  'cv_less_than_when_true'     => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে ছোট হতে হবে',
  'cv_less_than_when_false'    => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি [:limit] এর চেয়ে ছোট হতে হবে',
  'cv_greater_than_with'       => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি উপস্থিত হবে',
  'cv_less_than_with'          => '[:attribute] ফিল্ডটি সত্য মান ধারণ করতে হবে, যখন [:other] ফিল্ডটি উপস্থিত হবে',
  'cv_greater_than'            => '[:attribute] ফিল্ডটি [:limit] এর চেয়ে বড় হতে হবে',
  'cv_less_than'               => '[:attribute] ফিল্ডটি [:limit] এর চেয়ে ছোট হতে হবে',
  'cv_slugged'                 => '[:attribute] ফিল্ডটি একটি স্লাগ ফরম্যাট ধারণ করতে হবে',
  'key_exist_v2'               => '[:attribute] মানটি [:resource] সাথে মেলে না',
  //crudvel acopled validations
  'cv_key_exist'               => '[:attribute] মানটি ক্যাটালগ সাথে মেলে না',

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
  "There was an error on row :row. :message" => "[:row] সারিতে একটি ত্রুটি ছিল। :message",
  'attribute-name'                           => [
    "There was an error on row :row. :message" => "[:row] সারিতে একটি ত্রুটি ছিল। :message",
    'rule-name'                                => 'কাস্টম-বার্তা',
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
  'message'                                  => 'প্রেরিত তথ্যে ত্রুটি পাওয়া গেছে',
  "There was an error on row :row. :message" => "[:row] সারিতে একটি ত্রুটি ছিল। :message",
];

return $cvValidations;
