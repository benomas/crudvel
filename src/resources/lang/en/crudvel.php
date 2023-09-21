<?php

$cvGlobalSpecials = [
  'client-web-app'    => 'Permission to request token via web client app',
  'client-mobile-app' => 'Permission to request token via mobile client app',
  'code-hooks'        => 'Permission for code hooks management',
  'inactives'         => 'Permission to access inactive records',
];

$cvSections =  [
  'additionals' => 'Additionals',
  'catalogs'    => 'Catalogs',
  'dashboard'   => 'Dashboard',
  'files'       => 'Files',
  'main'        => 'Main',
  'profile'     => 'Profile',
  'security'    => 'Security',
  'settings'    => 'Settings',
];

$cvActions = [
  'index'  => [
    'call_message'   =>'List',
    'called_message' =>'Listing',
    'for'            =>'(api and client)',
  ],

  'show'   => [
    'call_message'   =>'View',
    'called_message' =>'View',
    'for'            =>'(api and client)',
  ],

  'create' => [
    'call_message'   =>'Create view',
    'called_message' =>'Creating',
    'next_message'   =>'Save',
    'success'        =>'Created',
    'for'            =>'(client)',
  ],

  'add' => [
    'call_message'   =>'Add',
    'called_message' =>'Adding',
    'success'        =>'Added',
    'for'            =>'(api and client)',
  ],

  'store'  => [
    'call_message'   =>'Create',
    'called_message' =>'Creating',
    'success'        =>'Created',
    'for'            =>'(api)',
  ],

  'edit'   => [
    'call_message'   =>'Edit view',
    'called_message' =>'Editing',
    'next_message'   =>'Update',
    'for'            =>'(client)',
  ],

  'update' => [
    'call_message'   =>'Update',
    'called_message' =>'Updating',
    'success'        =>'Updated',
    'for'            =>'(api)',
  ],

  'delete' => [
    'call_message'       =>'Delete view',
    'success'            =>'Deleted',
    'confirmation_alert' =>'The record will be deleted',
    'for'                =>'(client)',
  ],

  'destroy' => [
    'call_message'       =>'Delete',
    'success'            =>'Deleted',
    'confirmation_alert' =>'The record will be deleted',
    'for'                =>'(api)',
  ],

  'activate' => [
    'call_message' =>'Activate',
    'success'      =>'Activated',
    'for'          =>'(api and client)',
  ],

  'deactivate' => [
    'call_message' =>'Deactivate',
    'success'      =>'Deactivated',
    'for'          =>'(api and client)',
  ],

  'import' => [
    'call_message'   =>'Import view',
    'called_message' =>'Importing',
    'main_label'     =>'File to Import (Excel)',
    'next_message'   =>'Import',
    'for'            =>'(client)',
  ],

  'importing' => [
    'call_message'   =>'Import',
    'success'        =>'Imported',
    'for'            =>'(api)',
  ],

  'export' => [
    'call_message'   =>'Export view',
    'called_message' =>'Exporting',
    'next_message'   =>'Generate export Excel',
    'success'        =>'Exported',
    'for'            =>'(client)',
  ],

  'exporting' => [
    'call_message'   =>'Export',
    'called_message' =>'Exporting',
    'next_message'   =>'Generate export Excel',
    'success'        =>'Exported',
    'for'            =>'(api)',
  ],

  'exportings' => [
    'call_message'   =>'Export all',
    'called_message' =>'Exporting all',
    'next_message'   =>'Generate export Excel',
    'success'        =>'Exported all',
    'for'            =>'(api)',
  ],

  'relatedIndex' => [
    'call_message'   =>'Relator',
    'called_message' =>'Relating',
    'next_message'   =>'Save related',
    'success'        =>'Related records',
    'for'            =>'(api and client)',
  ],

  'indexOwnedBy' => [
    'call_message'   =>'Owner of the records',
    'called_message' =>'Relating',
    'next_message'   =>'Save related',
    'success'        =>'Related records',
    'for'            =>'(api and client)',
  ],

  'sluged' => [
    'call_message'   =>'List organized by slugs',
    'called_message' =>'Getting list by slugs',
    'for'            =>'(api and client)',
  ],
  'unauthorized' => [
    'call_message'   =>'Load permission restrictions',
    'called_message' =>'Loading permission restrictions',
    'for'            =>'(api and client)',
  ],

  'permissions' => [
    'call_message'   =>'Load permissions by role',
    'called_message' =>'Loading permissions by role',
    'for'            =>'(api and client)',
  ],

  'roles' => [
    'call_message'   =>'Load roles by user',
    'called_message' =>'Loading roles by user',
    'for'            =>'(api and client)',
  ],

  'profile' => [
    'call_message'   =>'Load user information',
    'called_message' =>'Loading user information',
    'for'            =>'(api and client)',
  ],

  'logout' => [
    'call_message'   =>'Logout',
    'called_message' =>'Logging out',
    'next_message'   =>'Logout',
    'success'        =>'Logged out',
    'for'            =>'(api and client)',
  ],

  'updateProfile' => [
    'call_message'   =>'Update user information',
    'called_message' =>'Updating user information',
    'next_message'   =>'Update user information',
    'success'        =>'User information updated',
    'for'            =>'(api and client)',
  ],

  'dashboardInfo' => [
    'call_message'   =>'Load administrative information',
    'called_message' =>'Loading administrative information',
    'for'            =>'(api and client)',
  ],

  'resources' => [
    'call_message'   =>'Get related resources',
    'called_message' =>'Getting related resources',
    'for'            =>'(api.client)',
  ],

  'resourcer' => [
    'call_message'   =>'Get resource files',
    'called_message' =>'Getting resource files',
    'for'            =>'(api.client)',
  ],

  'register' => [
    'call_message'   =>'Register',
    'called_message' =>'Registering',
    'success'        =>'Registering',
    'for'            =>'(api and client)',
  ],

  'recovery' => [
    'call_message'   =>'Recover',
    'called_message' =>'Recovering',
    'success'        =>'Recovered',
    'for'            =>'(api and client)',
  ],

  'storeUpdate'  => [
    'call_message'   =>'Create or edit',
    'called_message' =>'Creating or editing',
    'success'        =>'Edited',
    'for'            =>'(api)',
  ],

  'zippedResource' => [
    'call_message'   => 'Download compressed files',
    'called_message' => 'Downloading compressed files',
    'success'        => 'Downloaded compressed files',
    'for'            => '(api and client)',
  ],
];

$cvSpecials=[
  'inactives'            => 'Inactives',
  'general-owner'        => 'General owner',
  'particular-owner'     => 'Particular owner',
  'files-settings'       => 'Resource file settings',
  'index-files'          => 'List resource files',
  'show-files'           => 'View resource files',
  'create-files'         => 'Resource file creation view',
  'store-files'          => 'Create resource files',
  'edit-files'           => 'Resource file edit view',
  'update-files'         => 'Update resource files',
  'delete-files'         => 'Resource file delete view',
  'destroy-files'        => 'Delete resource files',
  'zippedResource-files' => 'Download compressed resource files',
  'code-hooks'           => 'Set code hooks',
];

$cvActionsExtra = [
  'common'=>[
    'cancel'    => 'Cancel',
    'back'      => 'Back',
    'confirm'   => 'Are you sure?',
    'correctly' => 'Correctly',
    'of'        => 'of',
  ],

  'label'=>'Actions',

  'status'=>[
    'yes' =>'Yes',
    'no'  =>'No',
  ],
];

$cvCrudvel = [
  'context_permission'=>'Permission applies to',
];

$cvWeb = [
  'unauthorized'         => 'You do not have permission to perform this action',
  'has_no_permsissions' => 'You do not have permission to perform this action',
  'operation_error'     => 'The operation could not be completed, please try again later',
  'transaction-error'   => 'An error occurred while processing the transaction',
  'success'             => 'Action completed',
  'not_found'           => 'Resource not found',
  'error'               => 'An unexpected error occurred',
  'file_error'          => 'An error occurred while trying to access the file',
  'already_exist'       => 'has already been registered',
  'validation_errors'   => 'Validation did not pass',
];

$cvApi = [
  'unauthorized'           => 'You do not have permission to perform this action',
  'has_no_permsissions'   => 'You do not have permission to perform this action',
  'operation_error'       => 'The operation could not be completed, please try again later',
  'transaction-error'     => 'An error occurred while processing the transaction',
  'not_found'             => 'Resource not found',
  'error'                 => 'An unexpected error occurred',
  'file_error'            => 'An error occurred while trying to access the file',
  'already_exist'         => 'has already been registered',
  'validation_errors'     => 'Validation did not pass',
  'logget_out'            => 'You have logged out',
  'success'               => 'Action completed',
  'incomplete'            => 'Incomplete action',
  'bad_paginate_petition' => 'Incorrect pagination parameters, responding with default values',
  'unproccesable'         => 'Inconsistent information',
  'miss_configuration'    => 'Service misconfigured',
  'true'                  => 'True',
  'false'                 => 'False',
  'no_files_to_zip'       => 'There are no files for the resource.',
  'no_cache_property'     => 'Unable to access a property',
];

