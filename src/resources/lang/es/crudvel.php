<?php

return [
    'actions' => [
        "index"  => [
            "call_message"   =>"Listar",
            "called_message" =>"Listado"
        ],
        "show"   => [
            "call_message"   =>"Ver",                                                 
            "called_message" =>"Vista",
        ],
        "create" => [
            "call_message"   =>"Crear",
            "called_message" =>"Creando",
            "next_message"   =>"Guardar",
        ],
        "store"  => [
            "success"      =>"Se ha Creado",
        ],
        "edit"   => [
            "call_message"   =>"Editar",
            "called_message" =>"Editando",
            "next_message"   =>"Actualizar",
        ],
        "update" => [
            "success"      =>"Se ha Actualizado",
        ],
        "destroy" => [
            "call_message"       =>"Eliminar",
            "success"            =>"Eliminado",
            "confirmation_alert" =>"El registro sera eliminado",
        ],
        "active" => [
            "call_message" =>"Activar",
            "success"      =>"Se ha Activado",
        ],
        "deactive" => [
            "call_message" =>"Desactivar",
            "success"      =>"Se ha Desactivado",
        ],
        "import" => [
            "call_message"   =>"Importar",
            "called_message" =>"Importando",
            "main_label"     =>"Archivo a Importar (Excel)",
            "next_message"   =>"Importar",
        ],
        "importing" => [
            "success"        =>"Se han Importado", 
        ],
        "export" => [
            "call_message"   =>"Exportar",
            "called_message" =>"Exportando",
            "next_message"   =>"Generar excel de exportación",
            "success"        =>"Se han Exportado",
        ],
        "common"=>[
            "cancel"    => "Cancelar",
            "back"      => "Regresar",
            "confirm"   => "Estas seguro?",
            "correctly" => "Correctamente",
            "of"        => "de",
        ],
        "label"=>"Acciones",
        "status"=>[
            "yes" =>"Si",
            "no"  =>"No",
        ]
    ],
    "web"=>[
        'unautorized'         => 'No tienes permisos para utilizar esta acción',
        'has_no_permsissions' => 'No tienes permisos para utilizar esta acción',
        'operation_error'     => 'No se ha podido realizar la operación, intenta mas tarde',
        'transaction-error'   => 'Ocurrió un error al realizar la transacción',
        'success'             => 'Acción completada',
        'not_found'           => 'no se encontro el recurso',
        'error'               => 'Ocurrió un error inesperado',
        'file_error'          => 'Ocurrió un error al intentar acceder al archivo',
        'already_exist'       => 'ya ha sido registrado',
        "validation_errors"   => 'No se han superado las validaciones',
    ],
    "api"=>[
        'unautorized'         => 'No tienes permisos para utilizar esta acción',
        'has_no_permsissions' => 'No tienes permisos para utilizar esta acción',
        'operation_error'     => 'No se ha podido realizar la operación, intenta mas tarde',
        'transaction-error'   => 'Ocurrió un error al realizar la transacción',
        'not_found'           => 'no se encontro el recurso',
        'error'               => 'Ocurrió un error inesperado',
        'file_error'          => 'Ocurrió un error al intentar acceder al archivo',
        'already_exist'       => 'ya ha sido registrado',
        "validation_errors"   => 'No se han superado las validaciones',
    ],
];
