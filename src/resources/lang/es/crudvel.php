<?php

$cvGlobalSpecials = [
  'client-web-app'    => 'Permiso para solicitar token mediante cliente web-app',
  'client-mobile-app' => 'Permiso para solicitar token mediante cliente mobile-app',
  'code-hooks'        => 'Permiso para Manejo de anclajes en codigo',
  'inactives'         => 'Permiso para Acceso a registros inactivos',
];

$cvSections =  [
  'additionals' => 'Adicionales',
  'catalogs'    => 'Catalogos',
  'dashboard'   => 'Tablero',
  'files'       => 'Archivos',
  'main'        => 'Principal',
  'profile'     => 'Perfil',
  'security'    => 'Seguridad',
  'settings'    => 'Ajustes',
];

$cvActions = [
  'index'  => [
    'call_message'   =>'Listar',
    'called_message' =>'Listado',
    'for'            =>'(back y front)',
  ],

  'show'   => [
    'call_message'   =>'Ver',
    'called_message' =>'Vista',
    'for'            =>'(back y front)',
  ],

  'create' => [
    'call_message'   =>'Vista de creación',
    'called_message' =>'Creando',
    'next_message'   =>'Guardar',
    'success'        =>'Se ha Creado',
    'for'            =>'(front)',
  ],

  'add' => [
    'call_message'   =>'Agregar',
    'called_message' =>'Agregando',
    'success'        =>'Se ha Agregado',
    'for'            =>'(back y front)',
  ],

  'store'  => [
    'call_message'   =>'Crear',
    'called_message' =>'Creando',
    'success'        =>'Se ha Creado',
    'for'            =>'(back)',
  ],

  'edit'   => [
    'call_message'   =>'Vista de edición',
    'called_message' =>'Editando',
    'next_message'   =>'Actualizar',
    'for'            =>'(front)',
  ],

  'update' => [
    'call_message'   =>'Actualizar',
    'called_message' =>'Actualizando',
    'success'        =>'Se ha Actualizado',
    'for'            =>'(back)',
  ],

  'delete' => [
    'call_message'       =>'Vista de eliminación',
    'success'            =>'Eliminado',
    'confirmation_alert' =>'El registro sera eliminado',
    'for'                =>'(front)',
  ],

  'destroy' => [
    'call_message'       =>'Eliminar',
    'success'            =>'Eliminado',
    'confirmation_alert' =>'El registro sera eliminado',
    'for'                =>'(back)',
  ],

  'activate' => [
    'call_message' =>'Activar',
    'success'      =>'Se ha Activado',
    'for'          =>'(back y front)',
  ],

  'deactivate' => [
    'call_message' =>'Desactivar',
    'success'      =>'Se ha Desactivado',
    'for'          =>'(back y front)',
  ],

  'import' => [
    'call_message'   =>'Vista de importación',
    'called_message' =>'Importando',
    'main_label'     =>'Archivo a Importar (Excel)',
    'next_message'   =>'Importar',
    'for'            =>'(front)',
  ],

  'importing' => [
    'call_message'   =>'Importar',
    'success'        =>'Se han Importado',
    'for'            =>'(back)',
  ],

  'export' => [
    'call_message'   =>'Vista de exportación',
    'called_message' =>'Exportando',
    'next_message'   =>'Generar excel de exportación',
    'success'        =>'Se han Exportado',
    'for'            =>'(front)',
  ],

  'exporting' => [
    'call_message'   =>'Exportar',
    'called_message' =>'Exportando',
    'next_message'   =>'Generar excel de exportación',
    'success'        =>'Se han Exportado',
    'for'            =>'(back)',
  ],

  'exportings' => [
    'call_message'   =>'Exportar todo',
    'called_message' =>'Exportando todo',
    'next_message'   =>'Generar excel de exportación',
    'success'        =>'Se han Exportado todo',
    'for'            =>'(api)',
  ],

  'relatedIndex' => [
    'call_message'   =>'Relacionador',
    'called_message' =>'Relacinando',
    'next_message'   =>'Guardar relacionados',
    'success'        =>'Registros relacionados',
    'for'            =>'(back y front)',
  ],

  'sluged' => [
    'call_message'   =>'Listado organizado por slugs',
    'called_message' =>'Obteniendo listado por slugs',
    'for'            =>'(back y front)',
  ],
  'unauthorized' => [
    'call_message'   =>'Cargar restricciones de permisos',
    'called_message' =>'Cargarndo restricciones de permisos',
    'for'            =>'(back y front)',
  ],

  'permissions' => [
    'call_message'   =>'Cargar permisos por rol',
    'called_message' =>'Cargarndo permisos por rol',
    'for'            =>'(back y front)',
  ],

  'roles' => [
    'call_message'   =>'Cargar roles por usuario',
    'called_message' =>'Cargarndo roles por usuario',
    'for'            =>'(back y front)',
  ],

  'profile' => [
    'call_message'   =>'Cargar información de usuario',
    'called_message' =>'Cargarndo información de usuario',
    'for'            =>'(back y front)',
  ],

  'logout' => [
    'call_message'   =>'Cerrar sesión',
    'called_message' =>'Cerrando sesión',
    'next_message'   =>'Cerrar sesión',
    'success'        =>'Sesión cerrada',
    'for'            =>'(back y front)',
  ],

  'updateProfile' => [
    'call_message'   =>'Actualizar información de usuario',
    'called_message' =>'Actualizando información de usuario',
    'next_message'   =>'Actualizar información de usuario',
    'success'        =>'Información de usuario actualizada',
    'for'            =>'(back y front)',
  ],

  'dashboardInfo' => [
    'call_message'   =>'Cargar información administrativa',
    'called_message' =>'Cargarndo información administrativa',
    'for'            =>'(back y front)',
  ],

  'resources' => [
    'call_message'   =>'Obtener recursos relacionados',
    'called_message' =>'Obteniendo recursos relacionados',
    'for'            =>'(api.cliente)',
  ],

  'resourcer' => [
    'call_message'   =>'Obtener archivos del recurso',
    'called_message' =>'Obteniendo archivos del recurso',
    'for'            =>'(api.cliente)',
  ],

  'register' => [
    'call_message'   =>'Registrar',
    'called_message' =>'Registrando',
    'success'        =>'Registrando',
    'for'            =>'(back y front)',
  ],

  'recovery' => [
    'call_message'   =>'Recuperar',
    'called_message' =>'Recuperando',
    'success'        =>'Recuperado',
    'for'            =>'(back y front)',
  ],

  'storeUpdate'  => [
    'call_message'   =>'Crear o editar',
    'called_message' =>'Creando o editando',
    'success'        =>'Se ha editado',
    'for'            =>'(back)',
  ],
];

$cvSpecials=[
  'inactives'        => 'Inactivos',
  'general-owner'    => 'De propiedad general',
  'particular-owner' => 'De propiedad particular',
  'files-settings'   => 'Configuración de archivos del recurso',
  'index-files'      => 'Listar archivos del recurso',
  'show-files'       => 'Ver archivos del recurso',
  'create-files'     => 'Vista de creación de archivos del recurso',
  'store-files'      => 'Crear archivos del recurso',
  'edit-files'       => 'Vista de edición de archivos del recurso',
  'update-files'     => 'Actualizar archivos del recurso',
  'delete-files'     => 'Vista de eliminación de archivos del recurso',
  'destroy-files'    => 'Eliminar archivos del recurso',
  'code-hooks'       => 'Establecer Enganches de codigo',

];

$cvActionsExtra = [
  'common'=>[
    'cancel'    => 'Cancelar',
    'back'      => 'Regresar',
    'confirm'   => 'Estas seguro?',
    'correctly' => 'Correctamente',
    'of'        => 'de',
  ],

  'label'=>'Acciones',

  'status'=>[
    'yes' =>'Si',
    'no'  =>'No',
  ],
];

$cvCrudvel = [
  'context_permission'=>'El permiso aplica en',
];

$cvWeb = [
  'unautorized'         => 'No tienes permisos para utilizar esta acción',
  'has_no_permsissions' => 'No tienes permisos para utilizar esta acción',
  'operation_error'     => 'No se ha podido realizar la operación, intenta mas tarde',
  'transaction-error'   => 'Ocurrió un error al realizar la transacción',
  'success'             => 'Acción completada',
  'not_found'           => 'no se encontro el recurso',
  'error'               => 'Ocurrió un error inesperado',
  'file_error'          => 'Ocurrió un error al intentar acceder al archivo',
  'already_exist'       => 'ya ha sido registrado',
  'validation_errors'   => 'No se han superado las validaciones',
];

$cvApi = [
  'unautorized'           => 'No tienes permisos para utilizar esta acción',
  'has_no_permsissions'   => 'No tienes permisos para utilizar esta acción',
  'operation_error'       => 'No se ha podido realizar la operación, intenta mas tarde',
  'transaction-error'     => 'Ocurrió un error al realizar la transacción',
  'not_found'             => 'no se encontro el recurso',
  'error'                 => 'Ocurrió un error inesperado',
  'file_error'            => 'Ocurrió un error al intentar acceder al archivo',
  'already_exist'         => 'ya ha sido registrado',
  'validation_errors'     => 'No se han superado las validaciones',
  'logget_out'            => 'Has cerrado sesión',
  'success'               => 'Acción completada',
  'incomplete'            => 'Acción incompleta',
  'bad_paginate_petition' => 'Parametros de paginación incorrectos, re responde con valores default',
  'unproccesable'         => 'Información inconsistente',
  'true'                  => 'Verdadero',
  'false'                 => 'Falso',
  'no_files_to_zip'       => 'No hay archivos para el recurso.',
  'no_cache_property'     => 'No se ha podido acceder a una propiedad',
];
