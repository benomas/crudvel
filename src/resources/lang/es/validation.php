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

  'accepted'                   => 'El campo [:attribute] debe ser aceptado.',
  'active_url'                 => 'El campo [:attribute] no es una URL válida.',
  'after'                      => 'El campo [:attribute] debe ser una fecha posterior a :date.',
  'alpha'                      => 'El campo [:attribute] solo debe contener letras.',
  'alpha_dash'                 => 'El campo [:attribute] solo debe contener letras, números y guiones.',
  'alpha_num'                  => 'El campo [:attribute] solo debe contener letras y números.',
  'alpha_spaces'               => 'El campo [:attribute] solo debe contener letras y espacios.',
  'array'                      => 'El campo [:attribute] debe ser un conjunto.',
  'before'                     => 'El campo [:attribute] debe ser una fecha anterior a :date.',
  'between'                    => [
    'numeric' => 'El campo [:attribute] tiene que estar entre :min - :max.',
    'file'    => 'El campo [:attribute] debe pesar entre :min - :max kilobytes.',
    'string'  => 'El campo [:attribute] tiene que tener entre :min - :max caracteres.',
    'array'   => 'El campo [:attribute] tiene que tener entre :min - :max ítems.',
  ],
  'boolean'                    => 'El campo [:attribute] debe tener un valor verdadero o falso.',
  'confirmed'                  => 'La confirmación de [:attribute] no coincide.',
  'date'                       => 'El campo [:attribute] no es una fecha válida.',
  'date_format'                => 'El campo [:attribute] no corresponde al formato :format.',
  'different'                  => 'El campo [:attribute] y :other deben ser diferentes.',
  'digits'                     => 'El campo [:attribute] debe tener :digits dígitos.',
  'digits_between'             => 'El campo [:attribute] debe tener entre :min y :max dígitos.',
  'email'                      => 'El campo [:attribute] no es un correo válido',
  'exists'                     => 'El campo [:attribute] es inválido.',
  'filled'                     => 'El campo [:attribute] es obligatorio.',
  'hex_color'                  => 'El campo [:attribute] debe ser un valor hexadecimal válido.',
  'image'                      => 'El campo [:attribute] debe ser un archivo de tipo imagen.',
  'in'                         => 'El campo [:attribute] es inválido.',
  'integer'                    => 'El campo [:attribute] debe ser un número entero.',
  'string'                    => 'El campo [:attribute] debe ser una cadena.',
  'ip'                         => 'El campo [:attribute] debe ser una dirección IP válida.',
  'max'                        => [
    'numeric' => 'El campo [:attribute] no debe ser mayor a :max.',
    'file'    => 'El campo [:attribute] no debe ser mayor que :max kilobytes.',
    'string'  => 'El campo [:attribute] no debe ser mayor que :max caracteres.',
    'array'   => 'El campo [:attribute] no debe tener más de :max elementos.',
  ],
  'mimes'                      => 'El campo [:attribute] debe ser un archivo con formato: :values.',
  'min'                        => [
    'numeric' => 'El tamaño de [:attribute] debe ser de al menos :min.',
    'file'    => 'El tamaño de [:attribute] debe ser de al menos :min kilobytes.',
    'string'  => 'El campo [:attribute] debe contener al menos :min caracteres.',
    'array'   => 'El campo [:attribute] debe tener al menos :min elementos.',
  ],
  'not_in'                     => 'El campo [:attribute] es inválido.',
  'not_only_digits'            => 'El campo [:attribute] no debe contener solo números.',
  'not_special'                => 'El campo [:attribute] no debe contener caracteres especiales.',
  'numeric'                    => 'El campo [:attribute] debe ser numérico.',
  'regex'                      => 'El formato de [:attribute] es inválido.',
  'required'                   => 'El campo [:attribute] es obligatorio.',
  'required_if'                => 'El campo [:attribute] es obligatorio cuando :other es :value.',
  'required_with'              => 'El campo [:attribute] es obligatorio cuando :values está presente.',
  'required_with_all'          => 'El campo [:attribute] es obligatorio cuando :values está presente.',
  'required_without'           => 'El campo [:attribute] es obligatorio cuando :values no está presente.',
  'required_without_all'       => 'El campo [:attribute] es obligatorio cuando ninguno de :values estén presentes.',
  'same'                       => 'El campo [:attribute] y :other deben coincidir.',
  'size'                       => [
    'numeric' => 'El tamaño de [:attribute] debe ser :size.',
    'file'    => 'El tamaño de [:attribute] debe ser :size kilobytes.',
    'string'  => 'El campo [:attribute] debe contener :size caracteres.',
    'array'   => 'El campo [:attribute] debe contener :size elementos.',
  ],
  'timezone'                   => 'El [:attribute] debe ser una zona válida.',
  'unique'                     => 'El campo [:attribute] ya ha sido registrado.',
  'unique_with'                => 'El campo [:attribute] ya ha sido registrado en :fields.',
  'url'                        => 'El formato [:attribute] es inválido.',
  'youtube'                    => 'El campo [:attribute] debe ser un video de YouTube válido.',
  'compositives'               => 'Ya existe una referencia a [:attribute] .',
  'list_exist'                 => 'Algun valor de la lista [:attribute], no pertenece al catalogo',
  'no_duplicate_values'        => 'Hay algunos valores duplicados en [:attribute]',
  'user_name'                  => 'El campo [:attribute] solo acepta \'letras\', \'numeros\', y los siguientes caracteres: . - _ @ ',
  'color'                      => 'Color invalido',
  'rfc'                        => 'El campo rfc no tiene un formato valido (3 letras, 6 digitos, 2 o 3 letras)',
  'key_exist'                  => 'El valor no corresponde con el catalogo',
  'in_list'                    => 'El valor [:attribute] no corresponde con los valores validos',
  'no_simultaneus'             => 'Los valores [:list1] en el campo [:attribute] no pueden coexistir con [:list2]',
  'file_already_exist'         => 'Ya existe el archivo',
  'file_resource'              => 'No tienes permisos para asociar este tipo de archivo',
  'unique_combination'         => 'El campo [:attribute] en combinacion con :fields. debe ser unico',
  'cv_true'                    => 'El campo [:attribute] debe tener un valor verdadero',
  'cv_false'                   => 'El campo [:attribute] debe tener un valor falso',
  'cv_boolean_inverse'         => 'El campo [:attribute] debe ser [:boolean1], cuando el campo [:other] es [:boolean2]',
  'cv_boolean_equal'           => 'El campo [:attribute] debe ser [:boolean1], cuando el campo [:other] es [:boolean2]',
  'cv_true_when_true'          => 'El campo [:attribute] debe ser verdadero, cuando el campo [:other] es verdadero',
  'cv_true_when_false'         => 'El campo [:attribute] debe ser verdadero, cuando el campo [:other] es falso',
  'cv_false_when_true'         => 'El campo [:attribute] debe ser falso, cuando el campo [:other] es verdadero',
  'cv_false_when_false'        => 'El campo [:attribute] debe ser falso, cuando el campo [:other] es falso',
  'cv_true_when_greater_than'  => 'El campo [:attribute] debe ser verdadero, cuando el campo [:other] es mayor que [:limit]',
  'cv_false_when_greater_than' => 'El campo [:attribute] debe ser falso, cuando el campo [:other] es mayor que [:limit]',
  'cv_true_when_less_than'     => 'El campo [:attribute] debe ser verdadero, cuando el campo [:other] es menor que [:limit]',
  'cv_false_when_less_than'    => 'El campo [:attribute] debe ser falso, cuando el campo [:other] es menor que [:limit]',
  'cv_greater_than_when_true'  => 'El campo [:attribute] debe tener un valor mayor que [:limit], cuando el campo [:other] es verdadero',
  'cv_greater_than_when_false' => 'El campo [:attribute] debe tener un valor mayor que [:limit], cuando el campo [:other] es falso',
  'cv_less_than_when_true'     => 'El campo [:attribute] debe tener un valor menor que [:limit], cuando el campo [:other] es verdadero',
  'cv_less_than_when_false'    => 'El campo [:attribute] debe tener un valor menor que [:limit], cuando el campo [:other] es falso',
  'cv_greater_than_with'       => 'El campo [:attribute] debe tener un valor mayor que [:limit], cuando el campo [:other] existe',
  'cv_less_than_with'          => 'El campo [:attribute] debe tener un valor menor que [:limit], cuando el campo [:other] existe',
  'cv_greater_than'            => 'El campo [:attribute] debe tener un valor mayor que [:limit]',
  'cv_less_than'               => 'El campo [:attribute] debe tener un valor menor que [:limit]',
  'cv_slugged'                 => 'El campo [:attribute] debe tener un formato de slug',
  'key_exist_v2'               => 'El valor no corresponde con el recurso [:resource]',
  //crudvel acopled validations
  'cv_key_exist'               => 'El valor no corresponde con el catalogo',

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
    "There was an error on row :row. :message" => "Hubo un error en la fila :row. :message",
    'attribute-name'                           => [
      "There was an error on row :row. :message" => "Hubo un error en la fila :row. :message",
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
  'message'                                  => 'Se han encontrado errores en la información enviada',
  "There was an error on row :row. :message" => "Hubo un error en la fila :row. :message",
];

return $cvValidations;
