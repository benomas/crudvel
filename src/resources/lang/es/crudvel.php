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
    'for'            =>'(api y cliente)',
  ],

  'show'   => [
    'call_message'   =>'Ver',
    'called_message' =>'Vista',
    'for'            =>'(api y cliente)',
  ],

  'create' => [
    'call_message'   =>'Vista de creación',
    'called_message' =>'Creando',
    'next_message'   =>'Guardar',
    'success'        =>'Se ha Creado',
    'for'            =>'(cliente)',
  ],

  'add' => [
    'call_message'   =>'Agregar',
    'called_message' =>'Agregando',
    'success'        =>'Se ha Agregado',
    'for'            =>'(api y cliente)',
  ],

  'store'  => [
    'call_message'   =>'Crear',
    'called_message' =>'Creando',
    'success'        =>'Se ha Creado',
    'for'            =>'(api)',
  ],

  'edit'   => [
    'call_message'   =>'Vista de edición',
    'called_message' =>'Editando',
    'next_message'   =>'Actualizar',
    'for'            =>'(cliente)',
  ],

  'update' => [
    'call_message'   =>'Actualizar',
    'called_message' =>'Actualizando',
    'success'        =>'Se ha Actualizado',
    'for'            =>'(api)',
  ],

  'delete' => [
    'call_message'       =>'Vista de eliminación',
    'success'            =>'Eliminado',
    'confirmation_alert' =>'El registro sera eliminado',
    'for'                =>'(cliente)',
  ],

  'destroy' => [
    'call_message'       =>'Eliminar',
    'success'            =>'Eliminado',
    'confirmation_alert' =>'El registro sera eliminado',
    'for'                =>'(api)',
  ],

  'activate' => [
    'call_message' =>'Activar',
    'success'      =>'Se ha Activado',
    'for'          =>'(api y cliente)',
  ],

  'deactivate' => [
    'call_message' =>'Desactivar',
    'success'      =>'Se ha Desactivado',
    'for'          =>'(api y cliente)',
  ],

  'import' => [
    'call_message'   =>'Vista de importación',
    'called_message' =>'Importando',
    'main_label'     =>'Archivo a Importar (Excel)',
    'next_message'   =>'Importar',
    'for'            =>'(cliente)',
  ],

  'importing' => [
    'call_message'   =>'Importar',
    'success'        =>'Se han Importado',
    'for'            =>'(api)',
  ],

  'export' => [
    'call_message'   =>'Vista de exportación',
    'called_message' =>'Exportando',
    'next_message'   =>'Generar excel de exportación',
    'success'        =>'Se han Exportado',
    'for'            =>'(cliente)',
  ],

  'exporting' => [
    'call_message'   =>'Exportar',
    'called_message' =>'Exportando',
    'next_message'   =>'Generar excel de exportación',
    'success'        =>'Se han Exportado',
    'for'            =>'(api)',
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
    'for'            =>'(api y cliente)',
  ],

  'sluged' => [
    'call_message'   =>'Listado organizado por slugs',
    'called_message' =>'Obteniendo listado por slugs',
    'for'            =>'(api y cliente)',
  ],
  'unauthorized' => [
    'call_message'   =>'Cargar restricciones de permisos',
    'called_message' =>'Cargarndo restricciones de permisos',
    'for'            =>'(api y cliente)',
  ],

  'permissions' => [
    'call_message'   =>'Cargar permisos por rol',
    'called_message' =>'Cargarndo permisos por rol',
    'for'            =>'(api y cliente)',
  ],

  'roles' => [
    'call_message'   =>'Cargar roles por usuario',
    'called_message' =>'Cargarndo roles por usuario',
    'for'            =>'(api y cliente)',
  ],

  'profile' => [
    'call_message'   =>'Cargar información de usuario',
    'called_message' =>'Cargarndo información de usuario',
    'for'            =>'(api y cliente)',
  ],

  'logout' => [
    'call_message'   =>'Cerrar sesión',
    'called_message' =>'Cerrando sesión',
    'next_message'   =>'Cerrar sesión',
    'success'        =>'Sesión cerrada',
    'for'            =>'(api y cliente)',
  ],

  'updateProfile' => [
    'call_message'   =>'Actualizar información de usuario',
    'called_message' =>'Actualizando información de usuario',
    'next_message'   =>'Actualizar información de usuario',
    'success'        =>'Información de usuario actualizada',
    'for'            =>'(api y cliente)',
  ],

  'dashboardInfo' => [
    'call_message'   =>'Cargar información administrativa',
    'called_message' =>'Cargarndo información administrativa',
    'for'            =>'(api y cliente)',
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
    'for'            =>'(api y cliente)',
  ],

  'recovery' => [
    'call_message'   =>'Recuperar',
    'called_message' =>'Recuperando',
    'success'        =>'Recuperado',
    'for'            =>'(api y cliente)',
  ],

  'storeUpdate'  => [
    'call_message'   =>'Crear o editar',
    'called_message' =>'Creando o editando',
    'success'        =>'Se ha editado',
    'for'            =>'(api)',
  ],

  'zippedResource' => [
    'call_message'   => 'Descargar archivos comprimidos',
    'called_message' => 'Descargando archivos comprimidos',
    'success'        => 'Se han descargado los archivos comprimidos',
    'for'            => '(api y cliente)',
  ],
];

$cvSpecials=[
  'inactives'            => 'Inactivos',
  'general-owner'        => 'De propiedad general',
  'particular-owner'     => 'De propiedad particular',
  'files-settings'       => 'Configuración de archivos del recurso',
  'index-files'          => 'Listar archivos del recurso',
  'show-files'           => 'Ver archivos del recurso',
  'create-files'         => 'Vista de creación de archivos del recurso',
  'store-files'          => 'Crear archivos del recurso',
  'edit-files'           => 'Vista de edición de archivos del recurso',
  'update-files'         => 'Actualizar archivos del recurso',
  'delete-files'         => 'Vista de eliminación de archivos del recurso',
  'destroy-files'        => 'Eliminar archivos del recurso',
  'zippedResource-files' => 'Descargar archivos comprimidos',
  'code-hooks'           => 'Establecer Enganches de codigo',

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
  'unauthorized'         => 'No tienes permisos para utilizar esta acción',
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
  'unauthorized'           => 'No tienes permisos para utilizar esta acción',
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
