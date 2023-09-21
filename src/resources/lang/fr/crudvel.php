<?php

$cvGlobalSpecials = [
  'client-web-app'    => 'Permission de demander un jeton via l\'application web cliente',
  'client-mobile-app' => 'Permission de demander un jeton via l\'application mobile cliente',
  'code-hooks'        => 'Permission de gestion des accroches de code',
  'inactives'         => 'Permission d\'accéder aux enregistrements inactifs',
];

$cvSections =  [
  'additionals' => 'Compléments',
  'catalogs'    => 'Catalogues',
  'dashboard'   => 'Tableau de bord',
  'files'       => 'Fichiers',
  'main'        => 'Principal',
  'profile'     => 'Profil',
  'security'    => 'Sécurité',
  'settings'    => 'Paramètres',
];

$cvActions = [
  'index'  => [
    'call_message'   =>'Lister',
    'called_message' =>'Liste',
    'for'            =>'(API et client)',
  ],

  'show'   => [
    'call_message'   =>'Voir',
    'called_message' =>'Vue',
    'for'            =>'(API et client)',
  ],

  'create' => [
    'call_message'   =>'Vue de création',
    'called_message' =>'Création',
    'next_message'   =>'Enregistrer',
    'success'        =>'Créé avec succès',
    'for'            =>'(client)',
  ],

  'add' => [
    'call_message'   =>'Ajouter',
    'called_message' =>'Ajout',
    'success'        =>'Ajouté avec succès',
    'for'            =>'(API et client)',
  ],

  'store'  => [
    'call_message'   =>'Créer',
    'called_message' =>'Création',
    'success'        =>'Créé avec succès',
    'for'            =>'(API)',
  ],

  'edit'   => [
    'call_message'   =>'Vue d\'édition',
    'called_message' =>'Édition',
    'next_message'   =>'Mettre à jour',
    'for'            =>'(client)',
  ],

  'update' => [
  'call_message'   =>'Mettre à jour',
  'called_message' =>'Mise à jour',
  'success'        =>'Mise à jour réussie',
  'for'            =>'(API)',
],

  'delete' => [
  'call_message'       =>'Vue de suppression',
  'success'            =>'Supprimé',
  'confirmation_alert' =>'L\'enregistrement sera supprimé',
    'for'                =>'(client)',
  ],

  'destroy' => [
    'call_message'       =>'Supprimer',
    'success'            =>'Supprimé',
    'confirmation_alert' =>'L\'enregistrement sera supprimé',
    'for'                =>'(API)',
  ],

  'activate' => [
  'call_message' =>'Activer',
  'success'      =>'Activé avec succès',
  'for'          =>'(API et client)',
],

  'deactivate' => [
  'call_message' =>'Désactiver',
  'success'      =>'Désactivé avec succès',
  'for'          =>'(API et client)',
],

  'import' => [
  'call_message'   =>'Vue d\'importation',
    'called_message' =>'Importation',
    'main_label'     =>'Fichier à importer (Excel)',
    'next_message'   =>'Importer',
    'for'            =>'(client)',
  ],

  'importing' => [
    'call_message'   =>'Importer',
    'success'        =>'Importé avec succès',
    'for'            =>'(API)',
  ],

  'export' => [
    'call_message'   =>'Vue d\'exportation',
    'called_message' =>'Exportation',
    'next_message'   =>'Générer un fichier Excel d\'exportation',
    'success'        =>'Exporté avec succès',
    'for'            =>'(client)',
  ],

  'exporting' => [
    'call_message'   =>'Exporter',
    'called_message' =>'Exportation',
    'next_message'   =>'Générer un fichier Excel d\'exportation',
    'success'        =>'Exporté avec succès',
    'for'            =>'(API)',
  ],

  'exportings' => [
  'call_message'   =>'Tout exporter',
  'called_message' =>'Tout exporter',
  'next_message'   =>'Générer un fichier Excel d\'exportation',
    'success'        =>'Tout a été exporté avec succès',
    'for'            =>'(API)',
  ],

  'relatedIndex' => [
    'call_message'   =>'Relier',
    'called_message' =>'Lien',
    'next_message'   =>'Enregistrer les liens',
    'success'        =>'Enregistrements liés',
    'for'            =>'(API et client)',
  ],

  'indexOwnedBy' => [
    'call_message'   =>'Propriétaire des enregistrements',
    'called_message' =>'Lien',
    'next_message'   =>'Enregistrer les liens',
    'success'        =>'Enregistrements liés',
    'for'            =>'(API et client)',
  ],

  'sluged' => [
    'call_message'   =>'Liste organisée par slug',
    'called_message' =>'Obtention de la liste par slugs',
    'for'            =>'(API et client)',
  ],
  'unauthorized' => [
    'call_message'   =>'Charger des restrictions d\'autorisation',
    'called_message' =>'Chargement des restrictions d\'autorisation',
    'for'            =>'(API et client)',
  ],

  'permissions' => [
    'call_message'   =>'Charger les autorisations par rôle',
    'called_message' =>'Chargement des autorisations par rôle',
    'for'            =>'(API et client)',
  ],

  'roles' => [
    'call_message'   =>'Charger les rôles par utilisateur',
    'called_message' =>'Chargement des rôles par utilisateur',
    'for'            =>'(API et client)',
  ],

  'profile' => [
    'call_message'   =>'Charger les informations de l\'utilisateur',
    'called_message' =>'Chargement des informations de l\'utilisateur',
    'for'            =>'(API et client)',
  ],

  'logout' => [
    'call_message'   =>'Déconnexion',
    'called_message' =>'Déconnexion',
    'next_message'   =>'Se déconnecter',
    'success'        =>'Session fermée',
    'for'            =>'(API et client)',
  ],

  'updateProfile' => [
    'call_message'   =>'Mettre à jour les informations de l\'utilisateur',
    'called_message' =>'Mise à jour des informations de l\'utilisateur',
    'next_message'   =>'Mettre à jour les informations de l\'utilisateur',
    'success'        =>'Informations de l\'utilisateur mises à jour',
    'for'            =>'(API et client)',
  ],

  'dashboardInfo' => [
    'call_message'   =>'Charger les informations administratives',
    'called_message' =>'Chargement des informations administratives',
    'for'            =>'(API et client)',
  ],

  'resources' => [
    'call_message'   =>'Obtenir des ressources liées',
    'called_message' =>'Obtention des ressources liées',
    'for'            =>'(API.client)',
  ],

  'resourcer' => [
    'call_message'   =>'Obtenir des fichiers de ressources',
    'called_message' =>'Obtention des fichiers de ressources',
    'for'            =>'(API.client)',
  ],

  'register' => [
    'call_message'   =>'S\'inscrire',
    'called_message' =>'Inscription',
    'success'        =>'Inscription',
    'for'            =>'(API et client)',
  ],

  'recovery' => [
  'call_message'   =>'Récupérer',
  'called_message' =>'Récupération',
  'success'        =>'Récupéré',
  'for'            =>'(API et client)',
],

  'storeUpdate'  => [
  'call_message'   =>'Créer ou mettre à jour',
  'called_message' =>'Création ou mise à jour',
  'success'        =>'Mise à jour réussie',
  'for'            =>'(API)',
],

  'zippedResource' => [
  'call_message'   => 'Télécharger des fichiers compressés',
  'called_message' => 'Téléchargement de fichiers compressés',
  'success'        => 'Téléchargement réussi des fichiers compressés',
  'for'            => '(API et client)',
],
];

$cvSpecials=[
  'inactives'            => 'Inactifs',
  'general-owner'        => 'Propriétaire général',
  'particular-owner'     => 'Propriétaire particulier',
  'files-settings'       => 'Paramètres des fichiers de ressources',
  'index-files'          => 'Lister les fichiers de ressources',
  'show-files'           => 'Voir les fichiers de ressources',
  'create-files'         => 'Vue de création des fichiers de ressources',
  'store-files'          => 'Créer les fichiers de ressources',
  'edit-files'           => 'Vue d\'édition des fichiers de ressources',
  'update-files'         => 'Mettre à jour les fichiers de ressources',
  'delete-files'         => 'Vue de suppression des fichiers de ressources',
  'destroy-files'        => 'Supprimer les fichiers de ressources',
  'zippedResource-files' => 'Télécharger des fichiers compressés de ressources',
  'code-hooks'           => 'Définir des crochets de code',
];

$cvActionsExtra = [
  'common'=>[
    'cancel'    => 'Annuler',
    'back'      => 'Revenir',
    'confirm'   => 'Êtes-vous sûr ?',
    'correctly' => 'Correctement',
    'of'        => 'de',
  ],

  'label'=>'Actions',

  'status'=>[
    'yes' =>'Oui',
    'no'  =>'Non',
  ],
];

$cvCrudvel = [
  'context_permission'=>'L\'autorisation s\'applique à',
];

$cvWeb = [
  'unauthorized'         => 'Vous n\'avez pas la permission d\'utiliser cette action',
  'has_no_permsissions' => 'Vous n\'avez pas la permission d\'utiliser cette action',
  'operation_error'     => 'L\'opération n\'a pas pu être effectuée, veuillez réessayer plus tard',
  'transaction-error'   => 'Une erreur s\'est produite lors de la transaction',
  'success'             => 'Action terminée',
  'not_found'           => 'La ressource n\'a pas été trouvée',
  'error'               => 'Une erreur inattendue s\'est produite',
  'file_error'          => 'Une erreur s\'est produite lors de l\'accès au fichier',
  'already_exist'       => 'a déjà été enregistré',
  'validation_errors'   => 'Les validations n\'ont pas été réussies',
];

$cvApi = [
  'unauthorized'           => 'Vous n\'avez pas la permission d\'utiliser cette action',
  'has_no_permsissions'   => 'Vous n\'avez pas la permission d\'utiliser cette action',
  'operation_error'       => 'L\'opération n\'a pas pu être effectuée, veuillez réessayer plus tard',
  'transaction-error'     => 'Une erreur s\'est produite lors de la transaction',
  'not_found'             => 'La ressource n\'a pas été trouvée',
  'error'                 => 'Une erreur inattendue s\'est produite',
  'file_error'            => 'Une erreur s\'est produite lors de l\'accès au fichier',
  'already_exist'         => 'a déjà été enregistré',
  'validation_errors'     => 'Les validations n\'ont pas été réussies',
  'logget_out'            => 'Vous avez été déconnecté',
  'success'               => 'Action terminée',
  'incomplete'            => 'Action incomplète',
  'bad_paginate_petition' => 'Paramètres de pagination incorrects, répondez avec des valeurs par défaut',
  'unproccesable'         => 'Informations incohérentes',
  'miss_configuration'    => 'Service mal configuré',
  'true'                  => 'Vrai',
  'false'                 => 'Faux',
  'no_files_to_zip'       => 'Aucun fichier disponible pour la ressource.',
  'no_cache_property'     => 'Impossible d\'accéder à une propriété',
];

