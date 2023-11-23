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

  'accepted'                   => 'O campo [:attribute] deve ser aceito.',
  'active_url'                 => 'O campo [:attribute] não é uma URL válida.',
  'after'                      => 'O campo [:attribute] deve ser uma data posterior a :date.',
  'alpha'                      => 'O campo [:attribute] deve conter apenas letras.',
  'alpha_dash'                 => 'O campo [:attribute] deve conter apenas letras, números e traços.',
  'alpha_num'                  => 'O campo [:attribute] deve conter apenas letras e números.',
  'alpha_spaces'               => 'O campo [:attribute] deve conter apenas letras e espaços.',
  'array'                      => 'O campo [:attribute] deve ser um array.',
  'before'                     => 'O campo [:attribute] deve ser uma data anterior a :date.',
  'between'                    => [
    'numeric' => 'O campo [:attribute] deve estar entre :min e :max.',
    'file'    => 'O campo [:attribute] deve ter entre :min e :max kilobytes.',
    'string'  => 'O campo [:attribute] deve ter entre :min e :max caracteres.',
    'array'   => 'O campo [:attribute] deve ter entre :min e :max itens.',
  ],
  'boolean'                    => 'O campo [:attribute] deve ter um valor verdadeiro ou falso.',
  'confirmed'                  => 'A confirmação de [:attribute] não corresponde.',
  'date'                       => 'O campo [:attribute] não é uma data válida.',
  'date_format'                => 'O campo [:attribute] não corresponde ao formato :formato.',
  'different'                  => 'O campo [:attribute] e :other devem ser diferentes.',
  'digits'                     => 'O campo [:attribute] deve ter :dígitos dígitos.',
  'digits_between'             => 'O campo [:attribute] deve ter entre :min e :max dígitos.',
  'email'                      => 'O campo [:attribute] não é um endereço de email válido.',
  'exists'                     => 'O campo [:attribute] é inválido.',
  'filled'                     => 'O campo [:attribute] é obrigatório.',
  'hex_color'                  => 'O campo [:attribute] deve ser um valor hexadecimal válido.',
  'image'                      => 'O campo [:attribute] deve ser um arquivo de imagem.',
  'in'                         => 'O campo [:attribute] é inválido.',
  'integer'                    => 'O campo [:attribute] deve ser um número inteiro.',
  'string'                     => 'O campo [:attribute] deve ser uma string.',
  'ip'                         => 'O campo [:attribute] deve ser um endereço IP válido.',
  'max'                        => [
    'numeric' => 'O campo [:attribute] não deve ser maior que :max.',
    'file'    => 'O campo [:attribute] não deve ser maior que :max kilobytes.',
    'string'  => 'O campo [:attribute] não deve ter mais de :max caracteres.',
    'array'   => 'O campo [:attribute] não deve ter mais de :max elementos.',
  ],
  'mimes'                      => 'O campo [:attribute] deve ser um arquivo do tipo: :valores.',
  'min'                        => [
    'numeric' => 'O tamanho de [:attribute] deve ser no mínimo :min.',
    'file'    => 'O tamanho de [:attribute] deve ser no mínimo :min kilobytes.',
    'string'  => 'O campo [:attribute] deve conter pelo menos :min caracteres.',
    'array'   => 'O campo [:attribute] deve ter pelo menos :min elementos.',
  ],
  'not_in'                     => 'O campo [:attribute] é inválido.',
  'not_only_digits'            => 'O campo [:attribute] não deve conter apenas números.',
  'not_special'                => 'O campo [:attribute] não deve conter caracteres especiais.',
  'numeric'                    => 'O campo [:attribute] deve ser numérico.',
  'regex'                      => 'O formato de [:attribute] é inválido.',
  'required'                   => 'O campo [:attribute] é obrigatório.',
  'required_if'                => 'O campo [:attribute] é obrigatório quando :other é :valor.',
  'required_with'              => 'O campo [:attribute] é obrigatório quando :valores estão presentes.',
  'required_with_all'          => 'O campo [:attribute] é obrigatório quando :valores estão presentes.',
  'required_without'           => 'O campo [:attribute] é obrigatório quando :valores não estão presentes.',
  'required_without_all'       => 'O campo [:attribute] é obrigatório quando nenhum dos :valores está presente.',
  'same'                       => 'O campo [:attribute] e :other devem coincidir.',
  'size'                       => [
    'numeric' => 'O tamanho de [:attribute] deve ser :tamanho.',
    'file'    => 'O tamanho de [:attribute] deve ser :tamanho kilobytes.',
    'string'  => 'O campo [:attribute] deve conter :tamanho caracteres.',
    'array'   => 'O campo [:attribute] deve conter :tamanho elementos.',
  ],
  'timezone'                   => 'O [:attribute] deve ser uma zona válida.',
  'unique'                     => 'O campo [:attribute] já foi registrado.',
  'unique_with'                => 'O campo [:attribute] já foi registrado em :campos.',
  'url'                        => 'O formato [:attribute] é inválido.',
  'youtube'                    => 'O campo [:attribute] deve ser um vídeo do YouTube válido.',
  'compositives'               => 'Já existe uma referência a [:attribute] .',
  'list_exist'                 => 'Algun valor da lista [:attribute], não pertence ao catálogo',
  'no_duplicate_values'        => 'Existem valores duplicados em [:attribute]',
  'user_name'                  => 'O campo [:attribute] só aceita letras, números e os seguintes caracteres: . - _ @ ',
  'color'                      => 'Cor inválida',
  'rfc'                        => 'O campo RFC não possui um formato válido (3 letras, 6 dígitos, 2 ou 3 letras)',
  'key_exist'                  => 'O valor não corresponde ao catálogo',
  'in_list'                    => 'O valor [:attribute] não corresponde aos valores válidos',
  'no_simultaneus'             => 'Os valores [:list1] no campo [:attribute] não podem coexistir com [:list2]',
  'file_already_exist'         => 'O arquivo já existe',
  'file_resource'              => 'Você não tem permissão para associar este tipo de arquivo',
  'unique_combination'         => 'O campo [:attribute] em combinação com :campos. deve ser único',
  'cv_true'                    => 'O campo [:attribute] deve ser verdadeiro',
  'cv_false'                   => 'O campo [:attribute] deve ser falso',
  'cv_boolean_inverse'         => 'O campo [:attribute] deve ser [:boolean1], quando o campo [:other] é [:boolean2]',
  'cv_boolean_equal'           => 'O campo [:attribute] deve ser [:boolean1], quando o campo [:other] é [:boolean2]',
  'cv_true_when_true'          => 'O campo [:attribute] deve ser verdadeiro quando o campo [:other] é verdadeiro',
  'cv_true_when_false'         => 'O campo [:attribute] deve ser verdadeiro quando o campo [:other] é falso',
  'cv_false_when_true'         => 'O campo [:attribute] deve ser falso quando o campo [:other] é verdadeiro',
  'cv_false_when_false'        => 'O campo [:attribute] deve ser falso quando o campo [:other] é falso',
  'cv_true_when_greater_than'  => 'O campo [:attribute] deve ser verdadeiro quando o campo [:other] é maior que [:limit]',
  'cv_false_when_greater_than' => 'O campo [:attribute] deve ser falso quando o campo [:other] é maior que [:limit]',
  'cv_true_when_less_than'     => 'O campo [:attribute] deve ser verdadeiro quando o campo [:other] é menor que [:limit]',
  'cv_false_when_less_than'    => 'O campo [:attribute] deve ser falso quando o campo [:other] é menor que [:limit]',
  'cv_greater_than_when_true'  => 'O campo [:attribute] deve ter um valor maior que [:limit], quando o campo [:other] é verdadeiro',
  'cv_greater_than_when_false' => 'O campo [:attribute] deve ter um valor maior que [:limit], quando o campo [:other] é falso',
  'cv_less_than_when_true'     => 'O campo [:attribute] deve ter um valor menor que [:limit], quando o campo [:other] é verdadeiro',
  'cv_less_than_when_false'    => 'O campo [:attribute] deve ter um valor menor que [:limit], quando o campo [:other] é falso',
  'cv_greater_than_with'       => 'O campo [:attribute] deve ter um valor maior que [:limit], quando o campo [:other] existe',
  'cv_less_than_with'          => 'O campo [:attribute] deve ter um valor menor que [:limit], quando o campo [:other] existe',
  'cv_greater_than'            => 'O campo [:attribute] deve ter um valor maior que [:limit]',
  'cv_less_than'               => 'O campo [:attribute] deve ter um valor menor que [:limit]',
  'cv_slugged'                 => 'O campo [:attribute] deve ter um formato de slug',
  'key_exist_v2'               => 'O valor não corresponde ao recurso [:resource]',
  // Validações acopladas do Crudvel
  'cv_key_exist'               => 'O valor não corresponde ao catálogo',

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
    "There was an error on row :row. :message" => "Houve um erro na linha :row. :message",
    'attribute-name'                           => [
      "There was an error on row :row. :message" => "Houve um erro na linha :row. :message",
      'rule-name'                                => 'mensagem-personalizada',
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
  'message'                                  => 'Foram encontrados erros nas informações enviadas',
  "There was an error on row :row. :message" => "Houve um erro na linha :row. :message",
];

return $cvValidations;
