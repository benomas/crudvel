<?php

$cvValidations = [

  /*
  */

  'accepted'                   => 'Le champ [:attribute] doit être accepté.',
  'active_url'                 => 'Le champ [:attribute] n\'est pas une URL valide.',
  'after'                      => 'Le champ [:attribute] doit être une date postérieure à :date.',
  'alpha'                      => 'Le champ [:attribute] ne peut contenir que des lettres.',
  'alpha_dash'                 => 'Le champ [:attribute] ne peut contenir que des lettres, des chiffres et des tirets.',
  'alpha_num'                  => 'Le champ [:attribute] ne peut contenir que des lettres et des chiffres.',
  'alpha_spaces'               => 'Le champ [:attribute] ne peut contenir que des lettres et des espaces.',
  'array'                      => 'Le champ [:attribute] doit être un tableau.',
  'before'                     => 'Le champ [:attribute] doit être une date antérieure à :date.',
  'between'                    => [
    'numeric' => 'Le champ [:attribute] doit être compris entre :min et :max.',
    'file'    => 'Le champ [:attribute] doit peser entre :min et :max kilooctets.',
    'string'  => 'Le champ [:attribute] doit contenir entre :min et :max caractères.',
    'array'   => 'Le champ [:attribute] doit contenir entre :min et :max éléments.',
  ],
  'boolean'                    => 'Le champ [:attribute] doit avoir une valeur vraie ou fausse.',
  'confirmed'                  => 'La confirmation du [:attribute] ne correspond pas.',
  'date'                       => 'Le champ [:attribute] n\'est pas une date valide.',
  'date_format'                => 'Le champ [:attribute] ne correspond pas au format :format.',
  'different'                  => 'Le champ [:attribute] et :other doivent être différents.',
  'digits'                     => 'Le champ [:attribute] doit contenir :digits chiffres.',
  'digits_between'             => 'Le champ [:attribute] doit contenir entre :min et :max chiffres.',
  'email'                      => 'Le champ [:attribute] n\'est pas une adresse e-mail valide.',
  'exists'                     => 'Le champ [:attribute] est invalide.',
  'filled'                     => 'Le champ [:attribute] est obligatoire.',
  'hex_color'                  => 'Le champ [:attribute] doit être une valeur hexadécimale valide.',
  'image'                      => 'Le champ [:attribute] doit être une image.',
  'in'                         => 'Le champ [:attribute] est invalide.',
  'integer'                    => 'Le champ [:attribute] doit être un nombre entier.',
  'ip'                         => 'Le champ [:attribute] doit être une adresse IP valide.',
  'max'                        => [
    'numeric' => 'Le champ [:attribute] ne doit pas être supérieur à :max.',
    'file'    => 'Le champ [:attribute] ne doit pas dépasser :max kilooctets.',
    'string'  => 'Le champ [:attribute] ne doit pas dépasser :max caractères.',
    'array'   => 'Le champ [:attribute] ne doit pas contenir plus de :max éléments.',
  ],
  'mimes'                      => 'Le champ [:attribute] doit être un fichier de type :values.',
  'min'                        => [
    'numeric' => 'La taille du [:attribute] doit être d\'au moins :min.',
    'file'    => 'La taille du [:attribute] doit être d\'au moins :min kilooctets.',
    'string'  => 'Le champ [:attribute] doit contenir au moins :min caractères.',
    'array'   => 'Le champ [:attribute] doit contenir au moins :min éléments.',
  ],
  'not_in'                     => 'Le champ [:attribute] est invalide.',
  'not_only_digits'            => 'Le champ [:attribute] ne doit pas contenir que des chiffres.',
  'not_special'                => 'Le champ [:attribute] ne doit pas contenir de caractères spéciaux.',
  'numeric'                    => 'Le champ [:attribute] doit être un nombre.',
  'regex'                      => 'Le format de [:attribute] est invalide.',
  'required'                   => 'Le champ [:attribute] est obligatoire.',
  'required_if'                => 'Le champ [:attribute] est obligatoire lorsque :other est :value.',
  'required_with'              => 'Le champ [:attribute] est obligatoire lorsque :values est présent.',
  'required_with_all'          => 'Le champ [:attribute] est obligatoire lorsque :values sont présents.',
  'required_without'           => 'Le champ [:attribute] est obligatoire lorsque :values n\'est pas présent.',
  'required_without_all'       => 'Le champ [:attribute] est obligatoire lorsque aucun des :values n\'est présent.',
  'same'                       => 'Le champ [:attribute] et :other doivent correspondre.',
  'size'                       => [
    'numeric' => 'La taille du [:attribute] doit être de :size.',
    'file'    => 'La taille du [:attribute] doit être de :size kilooctets.',
    'string'  => 'Le champ [:attribute] doit contenir :size caractères.',
    'array'   => 'Le champ [:attribute] doit contenir :size éléments.',
  ],
  'timezone'                   => 'Le [:attribute] doit être un fuseau horaire valide.',
  'unique'                     => 'Le champ [:attribute] a déjà été enregistré.',
  'unique_with'                => 'Le champ [:attribute] a déjà été enregistré avec :fields.',
  'url'                        => 'Le format [:attribute] est invalide.',
  'youtube'                    => 'Le champ [:attribute] doit être une vidéo YouTube valide.',
  'compositives'               => 'Une référence à [:attribute] existe déjà.',
  'list_exist'                 => 'Certaines valeurs de la liste [:attribute] ne correspondent pas au catalogue.',
  'no_duplicate_values'        => 'Il y a des valeurs en double dans [:attribute].',
  'user_name'                  => 'Le champ [:attribute] n\'accepte que les lettres, les chiffres et les caractères suivants : . - _ @ ',
  'color'                      => 'Couleur invalide',
  'rfc'                        => 'Le champ RFC n\'a pas un format valide (3 lettres, 6 chiffres, 2 ou 3 lettres)',
  'key_exist'                  => 'La valeur ne correspond pas au catalogue',
  'in_list'                    => 'La valeur [:attribute] ne correspond pas aux valeurs valides',
  'no_simultaneus'             => 'Les valeurs [:list1] dans le champ [:attribute] ne peuvent pas coexister avec [:list2]',
  'file_already_exist'         => 'Le fichier existe déjà',
  'file_resource'              => 'Vous n\'avez pas les autorisations pour associer ce type de fichier',
  'unique_combination'         => 'Le champ [:attribute] en combinaison avec :fields doit être unique',
  'cv_true'                    => 'Le champ [:attribute] doit être vrai',
  'cv_false'                   => 'Le champ [:attribute] doit être faux',
  'cv_boolean_inverse'         => 'Le champ [:attribute] doit être [:boolean1] lorsque le champ [:other] est [:boolean2]',
  'cv_boolean_equal'           => 'Le champ [:attribute] doit être [:boolean1] lorsque le champ [:other] est [:boolean2]',
  'cv_true_when_true'          => 'Le champ [:attribute] doit être vrai lorsque le champ [:other] est vrai',
  'cv_true_when_false'         => 'Le champ [:attribute] doit être vrai lorsque le champ [:other] est faux',
  'cv_false_when_true'         => 'Le champ [:attribute] doit être faux lorsque le champ [:other] est vrai',
  'cv_false_when_false'        => 'Le champ [:attribute] doit être faux lorsque le champ [:other] est faux',
  'cv_true_when_greater_than'  => 'Le champ [:attribute] doit être vrai lorsque le champ [:other] est supérieur à [:limit]',
  'cv_false_when_greater_than' => 'Le champ [:attribute] doit être faux lorsque le champ [:other] est supérieur à [:limit]',
  'cv_true_when_less_than'     => 'Le champ [:attribute] doit être vrai lorsque le champ [:other] est inférieur à [:limit]',
  'cv_false_when_less_than'    => 'Le champ [:attribute] doit être faux lorsque le champ [:other] est inférieur à [:limit]',
  'cv_greater_than_when_true'  => 'Le champ [:attribute] doit avoir une valeur supérieure à [:limit] lorsque le champ [:other] est vrai',
  'cv_greater_than_when_false' => 'Le champ [:attribute] doit avoir une valeur supérieure à [:limit] lorsque le champ [:other] est faux',
  'cv_less_than_when_true'     => 'Le champ [:attribute] doit avoir une valeur inférieure à [:limit] lorsque le champ [:other] est vrai',
  'cv_less_than_when_false'    => 'Le champ [:attribute] doit avoir une valeur inférieure à [:limit] lorsque le champ [:other] est faux',
  'cv_greater_than_with'       => 'Le champ [:attribute] doit avoir une valeur supérieure à [:limit] lorsque le champ [:other] existe',
  'cv_less_than_with'          => 'Le champ [:attribute] doit avoir une valeur inférieure à [:limit] lorsque le champ [:other] existe',
  'cv_greater_than'            => 'Le champ [:attribute] doit avoir une valeur supérieure à [:limit]',
  'cv_less_than'               => 'Le champ [:attribute] doit avoir une valeur inférieure à [:limit]',
  'cv_slugged'                 => 'Le champ [:attribute] doit avoir un format de slug',
  'key_exist_v2'               => 'La valeur ne correspond pas à la ressource [:resource]',
  // Validation intégrée à Crudvel
  'cv_key_exist'               => 'La valeur ne correspond pas au catalogue.',

  /*
  */

  'custom' => [
    "There was an error on row :row. :message" => "Il y a eu une erreur à la ligne :row. :message",
    'attribute-name'                           => [
      "There was an error on row :row. :message" => "Il y a eu une erreur à la ligne :row. :message",
      'rule-name'                                => 'message-personnalisé',
    ],
  ],

  /*
  */

  'attributes'                               => [],
  'message'                                  => 'Des erreurs ont été trouvées dans les informations soumises.',
  "There was an error on row :row. :message" => "Il y a eu une erreur à la ligne :row. :message",
];


return $cvValidations;
