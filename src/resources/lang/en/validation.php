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

	'accepted'                          => '[:attribute] debe ser aceptado.',
	'active_url'                        => '[:attribute] no es una URL válida.',
	'after'                             => '[:attribute] debe ser una fecha posterior a :date.',
	'alpha'                             => '[:attribute] solo debe contener letras.',
	'alpha_dash'                        => '[:attribute] solo debe contener letras, números y guiones.',
	'alpha_num'                         => '[:attribute] solo debe contener letras y números.',
	'alpha_spaces'                      => '[:attribute] solo debe contener letras y espacios.',
	'array'                             => '[:attribute] debe ser un conjunto.',
	'before'                            => '[:attribute] debe ser una fecha anterior a :date.',
	'between'                           => [
		'numeric' => '[:attribute] tiene que estar entre :min - :max.',
		'file'    => '[:attribute] debe pesar entre :min - :max kilobytes.',
		'string'  => '[:attribute] tiene que tener entre :min - :max caracteres.',
		'array'   => '[:attribute] tiene que tener entre :min - :max ítems.',
	],
	'boolean'                           => 'El campo [:attribute] debe tener un valor verdadero o falso.',
	'confirmed'                         => 'La confirmación de [:attribute] no coincide.',
	'date'                              => '[:attribute] no es una fecha válida.',
	'date_format'                       => '[:attribute] no corresponde al formato :format.',
	'different'                         => '[:attribute] y :other deben ser diferentes.',
	'digits'                            => '[:attribute] debe tener :digits dígitos.',
	'digits_between'                    => '[:attribute] debe tener entre :min y :max dígitos.',
	'email'                             => '[:attribute] no es un correo válido',
	'exists'                            => '[:attribute] es inválido.',
	'filled'                            => 'El campo [:attribute] es obligatorio.',
	'hex_color'                         => 'El campo [:attribute] debe ser un valor hexadecimal válido.',
	'image'                             => 'El campo [:attribute] debe ser un archivo de tipo imagen.',
	'in'                                => '[:attribute] es inválido.',
	'integer'                           => '[:attribute] debe ser un número entero.',
	'ip'                                => '[:attribute] debe ser una dirección IP válida.',
	'max'                               => [
		'numeric' => '[:attribute] no debe ser mayor a :max.',
		'file'    => '[:attribute] no debe ser mayor que :max kilobytes.',
		'string'  => '[:attribute] no debe ser mayor que :max caracteres.',
		'array'   => '[:attribute] no debe tener más de :max elementos.',
	],
	'mimes'                             => 'El campo[:attribute] debe ser un archivo con formato: :values.',
	'min'                               => [
		'numeric' => 'El tamaño de [:attribute] debe ser de al menos :min.',
		'file'    => 'El tamaño de [:attribute] debe ser de al menos :min kilobytes.',
		'string'  => '[:attribute] debe contener al menos :min caracteres.',
		'array'   => '[:attribute] debe tener al menos :min elementos.',
	],
	'not_in'                            => '[:attribute] es inválido.',
	'not_only_digits'                   => '[:attribute] no debe contener solo números.',
	'not_special'                       => '[:attribute] no debe contener caracteres especiales.',
	'numeric'                           => '[:attribute] debe ser numérico.',
	'regex'                             => 'El formato de [:attribute] es inválido.',
	'required'                          => 'El campo [:attribute] es obligatorio.',
	'required_if'                       => 'El campo [:attribute] es obligatorio cuando :other es :value.',
	'required_with'                     => 'El campo [:attribute] es obligatorio cuando :values está presente.',
	'required_with_all'                 => 'El campo [:attribute] es obligatorio cuando :values está presente.',
	'required_without'                  => 'El campo [:attribute] es obligatorio cuando :values no está presente.',
	'required_without_all'              => 'El campo [:attribute] es obligatorio cuando ninguno de :values estén presentes.',
	'same'                              => '[:attribute] y :other deben coincidir.',
	'size'                              => [
		'numeric' => 'El tamaño de [:attribute] debe ser :size.',
		'file'    => 'El tamaño de [:attribute] debe ser :size kilobytes.',
		'string'  => '[:attribute] debe contener :size caracteres.',
		'array'   => '[:attribute] debe contener :size elementos.',
	],
	'timezone'            => 'El [:attribute] debe ser una zona válida.',
	'unique'              => '[:attribute] ya ha sido registrado.',
	'unique_with'         => '[:attribute] ya ha sido registrado en :fields.',
	'url'                 => 'El formato [:attribute] es inválido.',
	'youtube'             => '[:attribute] debe ser un video de YouTube válido.',
	'compositives'        => 'Ya existe una referencia a [:attribute] .',
	'list_exist'          => 'Algun valor de la lista [:attribute], no pertenece al catalogo',
	'no_duplicate_values' => 'Hay algunos valores duplicados en [:attribute]',
	'user_name'           => 'El campo [:attribute] solo acepta \'letras\', \'numeros\', y los siguientes caracteres: . - _ @ ',
	'color'               => 'Color invalido',
	'rfc'                 => 'El campo rfc no tiene un formato valido (3 letras, 6 digitos, 2 o 3 letras)',
	'key_exist'           => 'El valor no corresponde con el catalogo',
	'in_list'             => 'El valor [:attribute] no corresponde con los valores validos',
	'no_simultaneus'      => 'Los valores [:list1] en el campo [:attribute] no pueden coexistir con [:list2]',
	'file_already_exist'  => 'Ya existe el archivo',
	'file_resource'       => 'No tienes permisos para asociar este tipo de archivo',
	'key_exist_v2'        => 'El valor no corresponde con el recurso [:resource]',
	'cv_greater_than'     => 'El campo [:attribute] debe tener un valor mayor que [:limit]',
	'cv_less_than'        => 'El campo [:attribute] debe tener un valor menor que [:limit]',
  //crudvel acopled validations
	'cv_key_exist'       => 'El valor no corresponde con el catalogo',

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

	'custom'                   => [
		'attribute-name'           => [
			'rule-name'                => 'custom-message',
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

	'attributes'               => [
	],
	'message'=>'Se han encontrado errores en la información enviada',
];

return $cvValidations;
