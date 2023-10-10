<?php

$cvGlobalSpecials = [
  'client-web-app'    => 'Permissão para solicitar token via cliente web-app',
  'client-mobile-app' => 'Permissão para solicitar token via cliente mobile-app',
  'code-hooks'        => 'Permissão para gerenciar ganchos de código',
  'inactives'         => 'Permissão para acessar registros inativos',
];

$cvSections = [
  'additionals' => 'Adicionais',
  'catalogs'    => 'Catálogos',
  'dashboard'   => 'Painel',
  'files'       => 'Arquivos',
  'main'        => 'Principal',
  'profile'     => 'Perfil',
  'security'    => 'Segurança',
  'settings'    => 'Configurações',
];

$cvActions = [
  'index' => [
    'call_message'   => 'Listar',
    'called_message' => 'Listagem',
    'for'            => '(api e cliente)',
  ],

  'show' => [
    'call_message'   => 'Ver',
    'called_message' => 'Visualização',
    'for'            => '(api e cliente)',
  ],

  'create' => [
    'call_message'   => 'Visualização de criação',
    'called_message' => 'Criando',
    'next_message'   => 'Salvar',
    'success'        => 'Criado com sucesso',
    'for'            => '(cliente)',
  ],

  'add' => [
    'call_message'   => 'Adicionar',
    'called_message' => 'Adicionando',
    'success'        => 'Adicionado com sucesso',
    'for'            => '(api e cliente)',
  ],

  'store' => [
    'call_message'   => 'Criar',
    'called_message' => 'Criando',
    'success'        => 'Criado com sucesso',
    'for'            => '(api)',
  ],

  'edit' => [
    'call_message'   => 'Visualização de edição',
    'called_message' => 'Editando',
    'next_message'   => 'Atualizar',
    'for'            => '(cliente)',
  ],

  'update' => [
    'call_message'   => 'Atualizar',
    'called_message' => 'Atualizando',
    'success'        => 'Atualizado com sucesso',
    'for'            => '(api)',
  ],

  'delete' => [
    'call_message'       => 'Visualização de exclusão',
    'success'            => 'Excluído',
    'confirmation_alert' => 'O registro será excluído',
    'for'                => '(cliente)',
  ],

  'destroy' => [
    'call_message'       => 'Excluir',
    'success'            => 'Excluído',
    'confirmation_alert' => 'O registro será excluído',
    'for'                => '(api)',
  ],

  'activate' => [
    'call_message' => 'Ativar',
    'success'      => 'Ativado com sucesso',
    'for'          => '(api e cliente)',
  ],

  'deactivate' => [
    'call_message' => 'Desativar',
    'success'      => 'Desativado com sucesso',
    'for'          => '(api e cliente)',
  ],

  'import' => [
    'call_message'   => 'Visualização de importação',
    'called_message' => 'Importando',
    'main_label'     => 'Arquivo para Importação (Excel)',
    'next_message'   => 'Importar',
    'for'            => '(cliente)',
  ],

  'importing' => [
    'call_message' => 'Importar',
    'success'      => 'Importado com sucesso',
    'for'          => '(api)',
  ],

  'export' => [
    'call_message'   => 'Visualização de exportação',
    'called_message' => 'Exportando',
    'next_message'   => 'Gerar exportação em excel',
    'success'        => 'Exportado com sucesso',
    'for'            => '(cliente)',
  ],

  'exporting' => [
    'call_message'   => 'Exportar',
    'called_message' => 'Exportando',
    'next_message'   => 'Gerar exportação em excel',
    'success'        => 'Exportado com sucesso',
    'for'            => '(api)',
  ],

  'exportings' => [
    'call_message'   => 'Exportar tudo',
    'called_message' => 'Exportando tudo',
    'next_message'   => 'Gerar exportação em excel',
    'success'        => 'Tudo exportado com sucesso',
    'for'            => '(api)',
  ],

  'relatedIndex' => [
    'call_message'   => 'Relacionar',
    'called_message' => 'Relacionando',
    'next_message'   => 'Salvar relacionados',
    'success'        => 'Registros relacionados',
    'for'            => '(api e cliente)',
  ],

  'indexOwnedBy' => [
    'call_message'   => 'Proprietário dos registros',
    'called_message' => 'Relacionando',
    'next_message'   => 'Salvar relacionados',
    'success'        => 'Registros relacionados',
    'for'            => '(api e cliente)',
  ],

  'sluged'       => [
    'call_message'   => 'Listagem organizada por slugs',
    'called_message' => 'Obtendo listagem por slugs',
    'for'            => '(api e cliente)',
  ],
  'unauthorized' => [
    'call_message'   => 'Carregar restrições de permissão',
    'called_message' => 'Carregando restrições de permissão',
    'for'            => '(api e cliente)',
  ],

  'permissions' => [
    'call_message'   => 'Carregar permissões por função',
    'called_message' => 'Carregando permissões por função',
    'for'            => '(api e cliente)',
  ],

  'roles' => [
    'call_message'   => 'Carregar funções por usuário',
    'called_message' => 'Carregando funções por usuário',
    'for'            => '(api e cliente)',
  ],

  'profile' => [
    'call_message'   => 'Carregar informações do usuário',
    'called_message' => 'Carregando informações do usuário',
    'for'            => '(api e cliente)',
  ],

  'logout' => [
    'call_message'   => 'Encerrar sessão',
    'called_message' => 'Encerrando sessão',
    'next_message'   => 'Encerrar sessão',
    'success'        => 'Sessão encerrada',
    'for'            => '(api e cliente)',
  ],

  'updateProfile' => [
    'call_message'   => 'Atualizar informações do usuário',
    'called_message' => 'Atualizando informações do usuário',
    'next_message'   => 'Atualizar informações do usuário',
    'success'        => 'Informações do usuário atualizadas',
    'for'            => '(api e cliente)',
  ],

  'dashboardInfo' => [
    'call_message'   => 'Carregar informações administrativas',
    'called_message' => 'Carregando informações administrativas',
    'for'            => '(api e cliente)',
  ],

  'resources' => [
    'call_message'   => 'Obter recursos relacionados',
    'called_message' => 'Obtendo recursos relacionados',
    'for'            => '(api.cliente)',
  ],

  'resourcer' => [
    'call_message'   => 'Obter arquivos do recurso',
    'called_message' => 'Obtendo arquivos do recurso',
    'for'            => '(api.cliente)',
  ],

  'register' => [
    'call_message'   => 'Registrar',
    'called_message' => 'Registrando',
    'success'        => 'Registrando',
    'for'            => '(api e cliente)',
  ],

  'recovery' => [
    'call_message'   => 'Recuperar',
    'called_message' => 'Recuperando',
    'success'        => 'Recuperado',
    'for'            => '(api e cliente)',
  ],

  'storeUpdate' => [
    'call_message'   => 'Criar ou editar',
    'called_message' => 'Criando ou editando',
    'success'        => 'Editado com sucesso',
    'for'            => '(api)',
  ],

  'zippedResource' => [
    'call_message'   => 'Baixar arquivos compactados',
    'called_message' => 'Baixando arquivos compactados',
    'success'        => 'Arquivos compactados baixados com sucesso',
    'for'            => '(api e cliente)',
  ],
];

$cvSpecials = [
  'inactives'            => 'Inativos',
  'general-owner'        => 'Proprietário Geral',
  'particular-owner'     => 'Proprietário Particular',
  'files-settings'       => 'Configurações de Arquivos do Recurso',
  'index-files'          => 'Listar Arquivos do Recurso',
  'show-files'           => 'Ver Arquivos do Recurso',
  'create-files'         => 'Visualização de Criação de Arquivos do Recurso',
  'store-files'          => 'Criar Arquivos do Recurso',
  'edit-files'           => 'Visualização de Edição de Arquivos do Recurso',
  'update-files'         => 'Atualizar Arquivos do Recurso',
  'delete-files'         => 'Visualização de Exclusão de Arquivos do Recurso',
  'destroy-files'        => 'Excluir Arquivos do Recurso',
  'zippedResource-files' => 'Baixar Arquivos do Recurso Compactados',
  'code-hooks'           => 'Configurar Ganchos de Código',
];

$cvActionsExtra = [
  'common' => [
    'cancel'    => 'Cancelar',
    'back'      => 'Voltar',
    'confirm'   => 'Você tem certeza?',
    'correctly' => 'Corretamente',
    'of'        => 'de',
  ],

  'label' => 'Ações',

  'status' => [
    'yes' => 'Sim',
    'no'  => 'Não',
  ],
];

$cvCrudvel = [
  'context_permission' => 'A permissão se aplica a',
  'now'                => 'Agora',
];

$cvWeb = [
  'unauthorized'        => 'Você não tem permissão para usar esta ação',
  'has_no_permsissions' => 'Você não tem permissão para usar esta ação',
  'operation_error'     => 'A operação não pôde ser realizada, tente novamente mais tarde',
  'transaction-error'   => 'Ocorreu um erro ao realizar a transação',
  'success'             => 'Ação concluída',
  'not_found'           => 'Recurso não encontrado',
  'error'               => 'Ocorreu um erro inesperado',
  'file_error'          => 'Ocorreu um erro ao tentar acessar o arquivo',
  'already_exist'       => 'já foi registrado',
  'validation_errors'   => 'As validações não foram superadas',
];

$cvApi = [
  'unauthorized'          => 'Você não tem permissão para usar esta ação',
  'has_no_permsissions'   => 'Você não tem permissão para usar esta ação',
  'operation_error'       => 'A operação não pôde ser realizada, tente novamente mais tarde',
  'transaction-error'     => 'Ocorreu um erro ao realizar a transação',
  'not_found'             => 'recurso não encontrado',
  'error'                 => 'Ocorreu um erro inesperado',
  'file_error'            => 'Ocorreu um erro ao tentar acessar o arquivo',
  'already_exist'         => 'já foi registrado',
  'validation_errors'     => 'As validações não foram superadas',
  'logget_out'            => 'Você saiu',
  'success'               => 'Ação concluída',
  'incomplete'            => 'Ação incompleta',
  'bad_paginate_petition' => 'Parâmetros de paginação incorretos, respondendo com valores padrão',
  'unproccesable'         => 'Informação inconsistente',
  'miss_configuration'    => 'Serviço mal configurado',
  'true'                  => 'Verdadeiro',
  'false'                 => 'Falso',
  'no_files_to_zip'       => 'Não há arquivos para o recurso.',
  'no_cache_property'     => 'Não foi possível acessar uma propriedade',
];
