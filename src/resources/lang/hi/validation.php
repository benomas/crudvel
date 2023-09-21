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

  'accepted'                   => '[:attribute] फ़ील्ड स्वीकार्य होनी चाहिए।',
  'active_url'                 => '[:attribute] फ़ील्ड मान्य URL नहीं है।',
  'after'                      => '[:attribute] फ़ील्ड [:date] के बाद की तारीख होनी चाहिए।',
  'alpha'                      => '[:attribute] फ़ील्ड में केवल अक्षर होने चाहिए।',
  'alpha_dash'                 => '[:attribute] फ़ील्ड में केवल अक्षर, अंक, और डैश होने चाहिए।',
  'alpha_num'                  => '[:attribute] फ़ील्ड में केवल अक्षर और अंक होने चाहिए।',
  'alpha_spaces'               => '[:attribute] फ़ील्ड में केवल अक्षर और स्पेसेस होने चाहिए।',
  'array'                      => '[:attribute] फ़ील्ड एक सरणी होनी चाहिए।',
  'before'                     => '[:attribute] फ़ील्ड [:date] के पहले की तारीख होनी चाहिए।',
  'between'                    => [
    'numeric' => '[:attribute] फ़ील्ड [:min] और [:max] के बीच होनी चाहिए।',
    'file'    => '[:attribute] फ़ील्ड [:min] से [:max] किलोबाइट के बीच होनी चाहिए।',
    'string'  => '[:attribute] फ़ील्ड [:min] से [:max] अक्षरों के बीच होनी चाहिए।',
    'array'   => '[:attribute] फ़ील्ड [:min] से [:max] आइटमों के बीच होनी चाहिए।',
  ],
  'boolean'                    => '[:attribute] फ़ील्ड सच या झूठा होना चाहिए।',
  'confirmed'                  => '[:attribute] की पुष्टि मेल नहीं खाती।',
  'date'                       => '[:attribute] फ़ील्ड मान्य तारीख नहीं है।',
  'date_format'                => '[:attribute] फ़ील्ड [:format] स्वीकार्य नहीं है।',
  'different'                  => '[:attribute] फ़ील्ड और :other अलग होने चाहिए।',
  'digits'                     => '[:attribute] फ़ील्ड [:digits] अंकों का होना चाहिए।',
  'digits_between'             => '[:attribute] फ़ील्ड [:min] और [:max] अंकों के बीच होना चाहिए।',
  'email'                      => '[:attribute] फ़ील्ड मान्य ईमेल पता नहीं है',
  'exists'                     => '[:attribute] फ़ील्ड मान्य नहीं है।',
  'filled'                     => '[:attribute] फ़ील्ड आवश्यक है।',
  'hex_color'                  => '[:attribute] फ़ील्ड मान्य हेक्साडेसिमल मान नहीं है।',
  'image'                      => '[:attribute] फ़ील्ड एक छवि फ़ाइल होनी चाहिए।',
  'in'                         => '[:attribute] फ़ील्ड मान्य नहीं है।',
  'integer'                    => '[:attribute] फ़ील्ड पूर्णांक होना चाहिए।',
  'ip'                         => '[:attribute] फ़ील्ड मान्य IP पता नहीं है।',
  'max'                        => [
    'numeric' => '[:attribute] फ़ील्ड [:max] से अधिक नहीं होना चाहिए।',
    'file'    => '[:attribute] फ़ील्ड [:max] किलोबाइट से अधिक नहीं होना चाहिए।',
    'string'  => '[:attribute] फ़ील्ड [:max] अक्षरों से अधिक नहीं होना चाहिए।',
    'array'   => '[:attribute] फ़ील्ड [:max] आइटमों से अधिक नहीं होना चाहिए।',
  ],
  'mimes'                      => '[:attribute] फ़ील्ड [:values] प्रारूप की फ़ाइल होनी चाहिए।',
  'min'                        => [
    'numeric' => '[:attribute] का आकार कम से कम [:min] होना चाहिए।',
    'file'    => '[:attribute] फ़ील्ड का आकार कम से कम [:min] किलोबाइट होना चाहिए।',
    'string'  => '[:attribute] फ़ील्ड में कम से कम [:min] अक्षर होने चाहिए।',
    'array'   => '[:attribute] फ़ील्ड में कम से कम [:min] आइटम होने चाहिए।',
  ],
  'not_in'                     => '[:attribute] फ़ील्ड मान्य नहीं है।',
  'not_only_digits'            => '[:attribute] फ़ील्ड में केवल अंक नहीं होने चाहिए।',
  'not_special'                => '[:attribute] फ़ील्ड में विशेष वर्ण नहीं होने चाहिए।',
  'numeric'                    => '[:attribute] फ़ील्ड संख्यात्मक होना चाहिए।',
  'regex'                      => '[:attribute] का प्रारूप मान्य नहीं है।',
  'required'                   => '[:attribute] फ़ील्ड आवश्यक है।',
  'required_if'                => '[:attribute] फ़ील्ड [:other] [:value] होने पर आवश्यक है।',
  'required_with'              => '[:attribute] फ़ील्ड [:values] मौजूद होने पर आवश्यक है।',
  'required_with_all'          => '[:attribute] फ़ील्ड [:values] मौजूद होने पर आवश्यक है।',
  'required_without'           => '[:attribute] फ़ील्ड [:values] मौजूद नहीं होने पर आवश्यक है।',
  'required_without_all'       => '[:attribute] फ़ील्ड को किसी भी [:values] के बिना मौजूद होने पर आवश्यक है।',
  'same'                       => '[:attribute] फ़ील्ड और :other मेल खाने चाहिए।',
  'size'                       => [
    'numeric' => '[:attribute] फ़ील्ड [:size] होना चाहिए।',
    'file'    => '[:attribute] फ़ील्ड [:size] किलोबाइट होना चाहिए।',
    'string'  => '[:attribute] फ़ील्ड [:size] अक्षरों का होना चाहिए।',
    'array'   => '[:attribute] फ़ील्ड में [:size] आइटम होने चाहिए।',
  ],
  'timezone'                   => '[:attribute] [:timezone] एक मान्य क्षेत्र होना चाहिए।',
  'unique'                     => '[:attribute] फ़ील्ड पहले से ही दर्ज है।',
  'unique_with'                => '[:attribute] फ़ील्ड [:fields] के साथ पहले से ही दर्ज है।',
  'url'                        => '[:attribute] का प्रारूप मान्य नहीं है।',
  'youtube'                    => '[:attribute] फ़ील्ड मान्य YouTube वीडियो नहीं है।',
  'compositives'               => '[:attribute] पर पहले से ही संदर्भ है।',
  'list_exist'                 => '[:attribute] सूची में कुछ मान एक सूची में नहीं है',
  'no_duplicate_values'        => '[:attribute] में कुछ मूल्य दोहराए गए हैं',
  'user_name'                  => '[:attribute] फ़ील्ड केवल अक्षर, अंक, और निम्नलिखित विशेष वर्णों (. - _ @ ) को स्वीकार करता है',
  'color'                      => 'अमान्य रंग',
  'rfc'                        => '[:attribute] फ़ील्ड मान्य RFC प्रारूप नहीं है (3 अक्षर, 6 अंक, 2 या 3 अक्षर)',
  'key_exist'                  => '[:attribute] मूल्य सूची से मेल नहीं खाता',
  'in_list'                    => '[:attribute] मूल्य वैध मूल्यों के साथ मेल नहीं खाता',
  'no_simultaneus'             => '[:attribute] में मौजूद [:list1] मूल्य [:list2] के साथ साथ नहीं हो सकते',
  'file_already_exist'         => 'फ़ाइल पहले से ही मौजूद है',
  'file_resource'              => 'इस प्रकार की फ़ाइल को जोड़ने के लिए आपकी अनुमति नहीं है',
  'unique_combination'         => '[:attribute] फ़ील्ड [:fields] के साथ एकत्र मौजूद होना चाहिए',
  'cv_true'                    => '[:attribute] फ़ील्ड सच होना चाहिए',
  'cv_false'                   => '[:attribute] फ़ील्ड झूठा होना चाहिए',
  'cv_boolean_inverse'         => '[:attribute] फ़ील्ड [:other] [:boolean2] होने पर [:boolean1] होना चाहिए',
  'cv_boolean_equal'           => '[:attribute] फ़ील्ड [:other] [:boolean2] होने पर [:boolean1] होना चाहिए',
  'cv_true_when_true'          => '[:attribute] फ़ील्ड [:other] सच होने पर सच होना चाहिए',
  'cv_true_when_false'         => '[:attribute] फ़ील्ड [:other] झूठा होने पर सच होना चाहिए',
  'cv_false_when_true'         => '[:attribute] फ़ील्ड [:other] सच होने पर झूठा होना चाहिए',
  'cv_false_when_false'        => '[:attribute] फ़ील्ड [:other] झूठा होने पर झूठा होना चाहिए',
  'cv_true_when_greater_than'  => '[:attribute] फ़ील्ड [:other] से अधिक होने पर सच होना चाहिए [:limit] के समय',
  'cv_false_when_greater_than' => '[:attribute] फ़ील्ड [:other] से अधिक होने पर झूठा होना चाहिए [:limit] के समय',
  'cv_true_when_less_than'     => '[:attribute] फ़ील्ड [:other] से कम होने पर सच होना चाहिए [:limit] के समय',
  'cv_false_when_less_than'    => '[:attribute] फ़ील्ड [:other] से कम होने पर झूठा होना चाहिए [:limit] के समय',
  'cv_greater_than_when_true'  => '[:attribute] फ़ील्ड सच होने पर [:limit] से अधिक मूल्य होना चाहिए',
  'cv_greater_than_when_false' => '[:attribute] फ़ील्ड झूठा होने पर [:limit] से अधिक मूल्य होना चाहिए',
  'cv_less_than_when_true'     => '[:attribute] फ़ील्ड सच होने पर [:limit] से कम मूल्य होना चाहिए',
  'cv_less_than_when_false'    => '[:attribute] फ़ील्ड झूठा होने पर [:limit] से कम मूल्य होना चाहिए',
  'cv_greater_than_with'       => '[:attribute] फ़ील्ड मौजूद होने पर [:other] से अधिक मूल्य होना चाहिए [:limit] के समय',
  'cv_less_than_with'          => '[:attribute] फ़ील्ड मौजूद होने पर [:other] से कम मूल्य होना चाहिए [:limit] के समय',
  'cv_greater_than'            => '[:attribute] फ़ील्ड [:limit] से अधिक मूल्य होना चाहिए',
  'cv_less_than'               => '[:attribute] फ़ील्ड [:limit] से कम मूल्य होना चाहिए',
  'cv_slugged'                 => '[:attribute] फ़ील्ड स्लग प्रारूप में होना चाहिए',
  'key_exist_v2'               => '[:attribute] मूल्य [:resource] से मेल नहीं खाता',
  //crudvel acopled validations
  'cv_key_exist'               => '[:attribute] मूल्य सूची से मेल नहीं खाता',

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
    "There was an error on row :row. :message" => "पंक्ति [:row] पर त्रुटि थी। :message",
    'attribute-name'                           => [
      "There was an error on row :row. :message" => "पंक्ति [:row] पर त्रुटि थी। :message",
      'rule-name'                                => 'कस्टम-संदेश',
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
  'message'                                  => 'भेजी गई जानकारी में त्रुटियाँ मिलीं',
  "There was an error on row :row. :message" => "पंक्ति [:row] पर त्रुटि थी। :message",
];


return $cvValidations;
