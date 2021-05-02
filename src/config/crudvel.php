<?php

return [
  'default_user'=>[
    'crudvel_default_user_username'   => env('CRUDVEL_DEFAULT_USER_USERNAME','root'),
    'crudvel_default_user_first_name' => env('CRUDVEL_DEFAULT_USER_FIRST_NAME','root'),
    'crudvel_default_user_last_name'  => env('CRUDVEL_DEFAULT_USER_LAST_NAME','root'),
    'crudvel_default_user_email'      => env('CRUDVEL_DEFAULT_USER_EMAIL','root@root.com'),
    'crudvel_default_user_passsword'  => env('CRUDVEL_DEFAULT_USER_PASSSWORD','root@root.com')
  ],
  'crudvel_root_path'  => env('CRUDVEL_ROOT_PATH','/var/www/'),
  'crudvel_front_path' => env('CRUDVEL_FRONT_PATH',null),
];
