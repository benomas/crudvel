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

  'accepted'                   => '字段[:attribute]必须被接受。',
  'active_url'                 => '字段[:attribute]不是一个有效的URL。',
  'after'                      => '字段[:attribute]必须是日期大于:date。',
  'alpha'                      => '字段[:attribute]只能包含字母。',
  'alpha_dash'                 => '字段[:attribute]只能包含字母、数字和破折号。',
  'alpha_num'                  => '字段[:attribute]只能包含字母和数字。',
  'alpha_spaces'               => '字段[:attribute]只能包含字母和空格。',
  'array'                      => '字段[:attribute]必须是数组。',
  'before'                     => '字段[:attribute]必须是日期早于:date。',
  'between'                    => [
    'numeric' => '字段[:attribute]必须在:min - :max之间。',
    'file'    => '字段[:attribute]必须在:min - :max千字节之间。',
    'string'  => '字段[:attribute]必须在:min - :max个字符之间。',
    'array'   => '字段[:attribute]必须包含:min - :max个项目。',
  ],
  'boolean'                    => '字段[:attribute]必须是真或假。',
  'confirmed'                  => '[:attribute]的确认不匹配。',
  'date'                       => '字段[:attribute]不是有效的日期。',
  'date_format'                => '字段[:attribute]不符合格式:format。',
  'different'                  => '字段[:attribute]和:other必须不同。',
  'digits'                     => '字段[:attribute]必须有:digits位数字。',
  'digits_between'             => '字段[:attribute]必须在:min和:max位数字之间。',
  'email'                      => '字段[:attribute]不是有效的电子邮件地址',
  'exists'                     => '字段[:attribute]是无效的。',
  'filled'                     => '字段[:attribute]是必需的。',
  'hex_color'                  => '字段[:attribute]必须是有效的十六进制值。',
  'image'                      => '字段[:attribute]必须是图像文件。',
  'in'                         => '字段[:attribute]是无效的。',
  'integer'                    => '字段[:attribute]必须是整数。',
  'ip'                         => '字段[:attribute]必须是有效的IP地址。',
  'max'                        => [
    'numeric' => '字段[:attribute]不得大于:max。',
    'file'    => '字段[:attribute]不得大于:max千字节。',
    'string'  => '字段[:attribute]不得大于:max个字符。',
    'array'   => '字段[:attribute]不得包含多于:max个项目。',
  ],
  'mimes'                      => '字段[:attribute]必须是格式为:values的文件。',
  'min'                        => [
    'numeric' => '字段[:attribute]大小必须至少为:min。',
    'file'    => '字段[:attribute]大小必须至少为:min千字节。',
    'string'  => '字段[:attribute]必须包含至少:min个字符。',
    'array'   => '字段[:attribute]必须包含至少:min个项目。',
  ],
  'not_in'                     => '字段[:attribute]是无效的。',
  'not_only_digits'            => '字段[:attribute]不能仅包含数字。',
  'not_special'                => '字段[:attribute]不能包含特殊字符。',
  'numeric'                    => '字段[:attribute]必须是数字。',
  'regex'                      => '字段[:attribute]的格式无效。',
  'required'                   => '字段[:attribute]是必需的。',
  'required_if'                => '当:other为:value时，字段[:attribute]是必需的。',
  'required_with'              => '当:values存在时，字段[:attribute]是必需的。',
  'required_with_all'          => '当:values存在时，字段[:attribute]是必需的。',
  'required_without'           => '当:values不存在时，字段[:attribute]是必需的。',
  'required_without_all'       => '当:values都不存在时，字段[:attribute]是必需的。',
  'same'                       => '字段[:attribute]和:other必须匹配。',
  'size'                       => [
    'numeric' => '字段[:attribute]大小必须为:size。',
    'file'    => '字段[:attribute]大小必须为:size千字节。',
    'string'  => '字段[:attribute]必须包含:size个字符。',
    'array'   => '字段[:attribute]必须包含:size个项目。',
  ],
  'timezone'                   => '[:attribute]必须是有效的时区。',
  'unique'                     => '字段[:attribute]已经被注册。',
  'unique_with'                => '字段[:attribute]在:fields中已经被注册。',
  'url'                        => '[:attribute]的格式无效。',
  'youtube'                    => '字段[:attribute]必须是有效的YouTube视频。',
  'compositives'               => '[:attribute]已经有一个引用。',
  'list_exist'                 => '列表[:attribute]中的某些值不属于目录',
  'no_duplicate_values'        => '[:attribute]中有一些重复的值',
  'user_name'                  => '字段[:attribute]仅接受字母、数字和以下字符: . - _ @ ',
  'color'                      => '无效颜色',
  'rfc'                        => 'RFC字段不具有有效格式（3个字母，6位数字，2或3个字母）',
  'key_exist'                  => '该值与目录不匹配',
  'in_list'                    => '值[:attribute]不与有效值相符',
  'no_simultaneus'             => '字段[:attribute]中的值[:list1]不能与[:list2]同时存在',
  'file_already_exist'         => '文件已经存在',
  'file_resource'              => '您没有权限关联此类型的文件',
  'unique_combination'         => '字段[:attribute]与:fields的组合必须是唯一的',
  'cv_true'                    => '字段[:attribute]必须为true',
  'cv_false'                   => '字段[:attribute]必须为false',
  'cv_boolean_inverse'         => '当字段[:other]为[:boolean2]时，字段[:attribute]必须为[:boolean1]',
  'cv_boolean_equal'           => '当字段[:other]为[:boolean2]时，字段[:attribute]必须为[:boolean1]',
  'cv_true_when_true'          => '当字段[:other]为true时，字段[:attribute]必须为true',
  'cv_true_when_false'         => '当字段[:other]为false时，字段[:attribute]必须为true',
  'cv_false_when_true'         => '当字段[:other]为true时，字段[:attribute]必须为false',
  'cv_false_when_false'        => '当字段[:other]为false时，字段[:attribute]必须为false',
  'cv_true_when_greater_than'  => '当字段[:other]大于[:limit]时，字段[:attribute]必须为true',
  'cv_false_when_greater_than' => '当字段[:other]大于[:limit]时，字段[:attribute]必须为false',
  'cv_true_when_less_than'     => '当字段[:other]小于[:limit]时，字段[:attribute]必须为true',
  'cv_false_when_less_than'    => '当字段[:other]小于[:limit]时，字段[:attribute]必须为false',
  'cv_greater_than_when_true'  => '当字段[:other]为true时，字段[:attribute]必须大于[:limit]',
  'cv_greater_than_when_false' => '当字段[:other]为false时，字段[:attribute]必须大于[:limit]',
  'cv_less_than_when_true'     => '当字段[:other]为true时，字段[:attribute]必须小于[:limit]',
  'cv_less_than_when_false'    => '当字段[:other]为false时，字段[:attribute]必须小于[:limit]',
  'cv_greater_than_with'       => '当字段[:other]存在时，字段[:attribute]必须大于[:limit]',
  'cv_less_than_with'          => '当字段[:other]存在时，字段[:attribute]必须小于[:limit]',
  'cv_greater_than'            => '字段[:attribute]必须大于[:limit]',
  'cv_less_than'               => '字段[:attribute]必须小于[:limit]',
  'cv_slugged'                 => '字段[:attribute]必须具有slug格式',
  'key_exist_v2'               => '该值与资源[:resource]不匹配',
  //crudvel acopled validations
  'cv_key_exist'               => '该值与目录不匹配',

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
    "There was an error on row :row. :message" => "第:row行出现错误。:message",
    'attribute-name'                           => [
      "There was an error on row :row. :message" => "第:row行出现错误。:message",
      'rule-name'                                => '自定义消息',
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
  'message'                                  => '发送的信息中存在错误',
  "There was an error on row :row. :message" => "第:row行出现错误。:message",
];


return $cvValidations;
