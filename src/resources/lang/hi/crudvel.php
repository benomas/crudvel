<?php

$cvGlobalSpecials = [
  'client-web-app'    => 'वेब ऐप क्लाइंट के माध्यम से टोकन अनुरोध करने की अनुमति',
  'client-mobile-app' => 'मोबाइल ऐप क्लाइंट के माध्यम से टोकन अनुरोध करने की अनुमति',
  'code-hooks'        => 'कोड में एंकर प्रबंधन की अनुमति',
  'inactives'         => 'निष्क्रिय रिकॉर्ड तक पहुँच की अनुमति',
];

$cvSections = [
  'additionals' => 'अतिरिक्त',
  'catalogs'    => 'कैटलॉग',
  'dashboard'   => 'डैशबोर्ड',
  'files'       => 'फ़ाइलें',
  'main'        => 'मुख्य',
  'profile'     => 'प्रोफ़ाइल',
  'security'    => 'सुरक्षा',
  'settings'    => 'सेटिंग्स',
];

$cvActions = [
  'index' => [
    'call_message'   => 'सूची',
    'called_message' => 'सूचीकरण',
    'for'            => '(API और क्लाइंट)',
  ],

  'show' => [
    'call_message'   => 'देखें',
    'called_message' => 'देख रहा है',
    'for'            => '(API और क्लाइंट)',
  ],

  'create' => [
    'call_message'   => 'नई दृश्य',
    'called_message' => 'बना रहा है',
    'next_message'   => 'सहेजें',
    'success'        => 'सफलतापूर्वक बनाया गया',
    'for'            => '(क्लाइंट)',
  ],

  'add' => [
    'call_message'   => 'जोड़ें',
    'called_message' => 'जोड़ रहा है',
    'success'        => 'सफलतापूर्वक जोड़ा गया',
    'for'            => '(API और क्लाइंट)',
  ],

  'store' => [
    'call_message'   => 'बनाएँ',
    'called_message' => 'बना रहा है',
    'success'        => 'सफलतापूर्वक बनाया गया',
    'for'            => '(API)',
  ],

  'edit' => [
    'call_message'   => 'संपादन दृश्य',
    'called_message' => 'संपादन कर रहा है',
    'next_message'   => 'अपडेट',
    'for'            => '(क्लाइंट)',
  ],

  'update' => [
    'call_message'   => 'अपडेट',
    'called_message' => 'अपडेट कर रहा है',
    'success'        => 'सफलतापूर्वक अपडेट किया गया',
    'for'            => '(API)',
  ],

  'delete' => [
    'call_message'       => 'मिटाने का दृश्य',
    'success'            => 'मिटाया गया',
    'confirmation_alert' => 'रिकॉर्ड हटा दिया जाएगा',
    'for'                => '(क्लाइंट)',
  ],

  'destroy' => [
    'call_message'       => 'मिटाएँ',
    'success'            => 'मिटाया गया',
    'confirmation_alert' => 'रिकॉर्ड हटा दिया जाएगा',
    'for'                => '(API)',
  ],

  'activate' => [
    'call_message' => 'सक्रिय करें',
    'success'      => 'सफलतापूर्वक सक्रिय किया गया',
    'for'          => '(API और क्लाइंट)',
  ],

  'deactivate' => [
    'call_message' => 'निष्क्रिय करें',
    'success'      => 'सफलतापूर्वक निष्क्रिय किया गया',
    'for'          => '(API और क्लाइंट)',
  ],

  'import' => [
    'call_message'   => 'आयात दृश्य',
    'called_message' => 'आयात कर रहा है',
    'main_label'     => 'आयात करने के लिए फ़ाइल (एक्सेल)',
    'next_message'   => 'आयात करें',
    'for'            => '(क्लाइंट)',
  ],

  'importing' => [
    'call_message' => 'आयात',
    'success'      => 'सफलतापूर्वक आयात किया गया',
    'for'          => '(API)',
  ],

  'export' => [
    'call_message'   => 'निर्यात दृश्य',
    'called_message' => 'निर्यात कर रहा है',
    'next_message'   => 'निर्यात एक्सेल बनाएँ',
    'success'        => 'सफलतापूर्वक निर्यात किया गया',
    'for'            => '(क्लाइंट)',
  ],

  'exporting' => [
    'call_message'   => 'निर्यात',
    'called_message' => 'निर्यात कर रहा है',
    'next_message'   => 'निर्यात एक्सेल बनाएँ',
    'success'        => 'सफलतापूर्वक निर्यात किया गया',
    'for'            => '(API)',
  ],

  'exportings' => [
    'call_message'   => 'सभी को निर्यात करें',
    'called_message' => 'सभी को निर्यात कर रहा है',
    'next_message'   => 'निर्यात एक्सेल बनाएँ',
    'success'        => 'सभी को सफलतापूर्वक निर्यात किया गया',
    'for'            => '(API)',
  ],

  'relatedIndex' => [
    'call_message'   => 'संबंधित',
    'called_message' => 'संबंधित',
    'next_message'   => 'संबंधित सहेजें',
    'success'        => 'संबंधित रिकॉर्ड्स',
    'for'            => '(API और क्लाइंट)',
  ],

  'indexOwnedBy' => [
    'call_message'   => 'रिकॉर्ड के मालिक',
    'called_message' => 'संबंधित',
    'next_message'   => 'संबंधित सहेजें',
    'success'        => 'संबंधित रिकॉर्ड्स',
    'for'            => '(API और क्लाइंट)',
  ],

  'sluged'       => [
    'call_message'   => 'स्लग्स द्वारा संगठित सूची',
    'called_message' => 'स्लग्स द्वारा सूची प्राप्त कर रहा है',
    'for'            => '(API और क्लाइंट)',
  ],
  'unauthorized' => [
    'call_message'   => 'अनुमति प्रतिबंधों को लोड करें',
    'called_message' => 'अनुमति प्रतिबंधों को लोड कर रहा है',
    'for'            => '(API और क्लाइंट)',
  ],

  'permissions' => [
    'call_message'   => 'भूमिका द्वारा अनुमतियाँ लोड करें',
    'called_message' => 'भूमिका द्वारा अनुमतियाँ लोड कर रहा है',
    'for'            => '(API और क्लाइंट)',
  ],

  'roles' => [
    'call_message'   => 'उपयोगकर्ता द्वारा भूमिकाएँ लोड करें',
    'called_message' => 'उपयोगकर्ता द्वारा भूमिकाएँ लोड कर रहा है',
    'for'            => '(API और क्लाइंट)',
  ],

  'profile' => [
    'call_message'   => 'उपयोगकर्ता जानकारी लोड करें',
    'called_message' => 'उपयोगकर्ता जानकारी लोड कर रहा है',
    'for'            => '(API और क्लाइंट)',
  ],

  'logout' => [
    'call_message'   => 'लॉग आउट',
    'called_message' => 'लॉग आउट हो रहा है',
    'next_message'   => 'लॉग आउट',
    'success'        => 'सत्र बंद हो गया',
    'for'            => '(API और क्लाइंट)',
  ],

  'updateProfile' => [
    'call_message'   => 'उपयोगकर्ता जानकारी अपडेट करें',
    'called_message' => 'उपयोगकर्ता जानकारी अपडेट कर रहा है',
    'next_message'   => 'उपयोगकर्ता जानकारी अपडेट करें',
    'success'        => 'उपयोगकर्ता जानकारी अपडेट हो गई',
    'for'            => '(API और क्लाइंट)',
  ],

  'dashboardInfo' => [
    'call_message'   => 'प्रशासनिक जानकारी लोड करें',
    'called_message' => 'प्रशासनिक जानकारी लोड कर रहा है',
    'for'            => '(API और क्लाइंट)',
  ],

  'resources' => [
    'call_message'   => 'संबंधित संसाधन प्राप्त करें',
    'called_message' => 'संबंधित संसाधन प्राप्त कर रहा है',
    'for'            => '(API.क्लाइंट)',
  ],

  'resourcer' => [
    'call_message'   => 'संसाधन फ़ाइलें प्राप्त करें',
    'called_message' => 'संसाधन फ़ाइलें प्राप्त कर रहा है',
    'for'            => '(API.क्लाइंट)',
  ],

  'register' => [
    'call_message'   => 'रजिस्टर',
    'called_message' => 'रजिस्टर हो रहा है',
    'success'        => 'रजिस्टर हो गया',
    'for'            => '(API और क्लाइंट)',
  ],

  'recovery' => [
    'call_message'   => 'पुनर्प्राप्त करें',
    'called_message' => 'पुनर्प्राप्त हो रहा है',
    'success'        => 'पुनर्प्राप्त हो गया',
    'for'            => '(API और क्लाइंट)',
  ],

  'storeUpdate' => [
    'call_message'   => 'बनाएँ या संपादन करें',
    'called_message' => 'बनाने या संपादन कर रहा है',
    'success'        => 'सफलतापूर्वक संपादित किया गया',
    'for'            => '(API)',
  ],

  'zippedResource' => [
    'call_message'   => 'संपीड़ित फ़ाइलें डाउनलोड करें',
    'called_message' => 'संपीड़ित फ़ाइलें डाउनलोड कर रहा है',
    'success'        => 'संपीड़ित फ़ाइलें सफलतापूर्वक डाउनलोड की गईं',
    'for'            => '(API और क्लाइंट)',
  ],
];

$cvSpecials = [
  'inactives'            => 'निष्क्रिय',
  'general-owner'        => 'सामान्य मालिक',
  'particular-owner'     => 'विशेष मालिक',
  'files-settings'       => 'संसाधन फ़ाइल सेटिंग्स',
  'index-files'          => 'संसाधन फ़ाइल सूची',
  'show-files'           => 'संसाधन फ़ाइल देखें',
  'create-files'         => 'संसाधन फ़ाइल निर्माण दृश्य',
  'store-files'          => 'संसाधन फ़ाइल बनाएं',
  'edit-files'           => 'संसाधन फ़ाइल संपादन दृश्य',
  'update-files'         => 'संसाधन फ़ाइल अपडेट करें',
  'delete-files'         => 'संसाधन फ़ाइल हटाने दृश्य',
  'destroy-files'        => 'संसाधन फ़ाइल हटाएं',
  'zippedResource-files' => 'संपीड़ित संसाधन फ़ाइल डाउनलोड करें',
  'code-hooks'           => 'कोड हुक्स सेट करें',
];

$cvActionsExtra = [
  'common' => [
    'cancel'    => 'रद्द करें',
    'back'      => 'पीछे जाएं',
    'confirm'   => 'क्या आप सुनिश्चित हैं?',
    'correctly' => 'सही तरीके से',
    'of'        => 'का',
  ],

  'label' => 'क्रियाएँ',

  'status' => [
    'yes' => 'हाँ',
    'no'  => 'नहीं',
  ],
];

$cvCrudvel = [
  'context_permission' => 'अनुमति किस पर लागू होती है',
];

$cvWeb = [
  'unauthorized'        => 'आपको इस क्रिया का उपयोग करने की अनुमति नहीं है',
  'has_no_permsissions' => 'आपको इस क्रिया का उपयोग करने की अनुमति नहीं है',
  'operation_error'     => 'क्रिया को पूरा नहीं किया जा सका, कृपया बाद में पुनः प्रयास करें',
  'transaction-error'   => 'लेन-देन करते समय एक त्रुटि आई',
  'success'             => 'क्रिया पूरी हुई',
  'not_found'           => 'संसाधन नहीं मिला',
  'error'               => 'एक अप्रत्याशित त्रुटि आई',
  'file_error'          => 'फ़ाइल तक पहुँचने का प्रयास करते समय एक त्रुटि आई',
  'already_exist'       => 'पहले से ही रजिस्टर किया गया है',
  'validation_errors'   => 'मान्यताएँ पार नहीं की गई हैं',
];

$cvApi = [
  'unauthorized'          => 'आपको इस क्रिया का उपयोग करने की अनुमति नहीं है',
  'has_no_permsissions'   => 'आपको इस क्रिया का उपयोग करने की अनुमति नहीं है',
  'operation_error'       => 'क्रिया को पूरा नहीं किया जा सका, कृपया बाद में पुनः प्रयास करें',
  'transaction-error'     => 'लेन-देन करते समय एक त्रुटि आई',
  'not_found'             => 'संसाधन नहीं मिला',
  'error'                 => 'एक अप्रत्याशित त्रुटि आई',
  'file_error'            => 'फ़ाइल तक पहुँचने का प्रयास करते समय एक त्रुटि आई',
  'already_exist'         => 'पहले से ही रजिस्टर किया गया है',
  'validation_errors'     => 'मान्यताएँ पार नहीं की गई हैं',
  'logget_out'            => 'आपने लॉग आउट किया है',
  'success'               => 'क्रिया पूरी हुई',
  'incomplete'            => 'अधूरी क्रिया',
  'bad_paginate_petition' => 'गलत पैज़िनेट मांग, डिफ़ॉल्ट मूल्यों के साथ जवाब दिया जा रहा है',
  'unproccesable'         => 'असंगत जानकारी',
  'miss_configuration'    => 'सेवा गलती से कॉन्फ़िगर की गई',
  'true'                  => 'सच',
  'false'                 => 'झूठ',
  'no_files_to_zip'       => 'संसाधन के लिए कोई फ़ाइलें नहीं हैं।',
  'no_cache_property'     => 'एक संपत्ति तक पहुँच नहीं पा सकते',
];

