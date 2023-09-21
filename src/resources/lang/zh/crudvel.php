<?php

$cvGlobalSpecials = [
  'client-web-app'    => '通过Web客户端应用程序请求令牌的权限',
  'client-mobile-app' => '通过移动客户端应用程序请求令牌的权限',
  'code-hooks'        => '代码挂钩管理权限',
  'inactives'         => '访问非活动记录的权限',
];

$cvSections =  [
  'additionals' => '附加功能',
  'catalogs'    => '目录',
  'dashboard'   => '仪表板',
  'files'       => '文件',
  'main'        => '主要',
  'profile'     => '个人资料',
  'security'    => '安全',
  'settings'    => '设置',
];

$cvActions = [
  'index'  => [
    'call_message'   =>'列表',
    'called_message' =>'列表',
    'for'            =>'（API和客户端）',
  ],

  'show'   => [
    'call_message'   =>'查看',
    'called_message' =>'查看',
    'for'            =>'（API和客户端）',
  ],

  'create' => [
    'call_message'   =>'创建视图',
    'called_message' =>'创建',
    'next_message'   =>'保存',
    'success'        =>'创建成功',
    'for'            =>'（客户端）',
  ],

  'add' => [
    'call_message'   =>'添加',
    'called_message' =>'添加',
    'success'        =>'添加成功',
    'for'            =>'（API和客户端）',
  ],

  'store'  => [
    'call_message'   =>'创建',
    'called_message' =>'创建',
    'success'        =>'创建成功',
    'for'            =>'（API）',
  ],

  'edit'   => [
    'call_message'   =>'编辑视图',
    'called_message' =>'编辑',
    'next_message'   =>'更新',
    'for'            =>'（客户端）',
  ],

  'update' => [
    'call_message'   =>'更新',
    'called_message' =>'更新',
    'success'        =>'更新成功',
    'for'            =>'（API）',
  ],

  'delete' => [
    'call_message'       =>'删除视图',
    'success'            =>'已删除',
    'confirmation_alert' =>'记录将被删除',
    'for'                =>'（客户端）',
  ],

  'destroy' => [
    'call_message'       =>'删除',
    'success'            =>'已删除',
    'confirmation_alert' =>'记录将被删除',
    'for'                =>'（API）',
  ],

  'activate' => [
    'call_message' =>'激活',
    'success'      =>'已激活',
    'for'          =>'（API和客户端）',
  ],

  'deactivate' => [
    'call_message' =>'停用',
    'success'      =>'已停用',
    'for'          =>'（API和客户端）',
  ],

  'import' => [
    'call_message'   =>'导入视图',
    'called_message' =>'导入',
    'main_label'     =>'要导入的文件（Excel）',
    'next_message'   =>'导入',
    'for'            =>'（客户端）',
  ],

  'importing' => [
    'call_message'   =>'导入',
    'success'        =>'已成功导入',
    'for'            =>'（API）',
  ],

  'export' => [
    'call_message'   =>'导出视图',
    'called_message' =>'导出',
    'next_message'   =>'生成导出Excel',
    'success'        =>'已成功导出',
    'for'            =>'（客户端）',
  ],

  'exporting' => [
    'call_message'   =>'导出',
    'called_message' =>'导出',
    'next_message'   =>'生成导出Excel',
    'success'        =>'已成功导出',
    'for'            =>'（API）',
  ],

  'exportings' => [
    'call_message'   =>'全部导出',
    'called_message' =>'全部导出',
    'next_message'   =>'生成全部导出Excel',
    'success'        =>'已成功全部导出',
    'for'            =>'（API）',
  ],

  'relatedIndex' => [
    'call_message'   =>'关联索引',
    'called_message' =>'关联中',
    'next_message'   =>'保存关联',
    'success'        =>'关联记录',
    'for'            =>'（API和客户端）',
  ],

  'indexOwnedBy' => [
    'call_message'   =>'记录所有者',
    'called_message' =>'关联中',
    'next_message'   =>'保存关联',
    'success'        =>'关联记录',
    'for'            =>'（API和客户端）',
  ],

  'sluged' => [
    'call_message'   =>'按Slug组织的列表',
    'called_message' =>'按Slug获取列表',
    'for'            =>'（API和客户端）',
  ],
  'unauthorized' => [
    'call_message'   =>'加载权限限制',
    'called_message' =>'加载权限限制',
    'for'            =>'（API和客户端）',
  ],

  'permissions' => [
    'call_message'   =>'按角色加载权限',
    'called_message' =>'按角色加载权限',
    'for'            =>'（API和客户端）',
  ],

  'roles' => [
    'call_message'   =>'按用户加载角色',
    'called_message' =>'按用户加载角色',
    'for'            =>'（API和客户端）',
  ],

  'profile' => [
    'call_message'   =>'加载用户信息',
    'called_message' =>'加载用户信息',
    'for'            =>'（API和客户端）',
  ],

  'logout' => [
    'call_message'   =>'注销',
    'called_message' =>'注销中',
    'next_message'   =>'注销',
    'success'        =>'会话已关闭',
    'for'            =>'（API和客户端）',
  ],

  'updateProfile' => [
    'call_message'   =>'更新用户信息',
    'called_message' =>'更新用户信息',
    'next_message'   =>'更新用户信息',
    'success'        =>'用户信息已更新',
    'for'            =>'（API和客户端）',
  ],

  'dashboardInfo' => [
    'call_message'   =>'加载管理信息',
    'called_message' =>'加载管理信息',
    'for'            =>'（API和客户端）',
  ],

  'resources' => [
    'call_message'   =>'获取相关资源',
    'called_message' =>'获取相关资源',
    'for'            =>'（API.客户端）',
  ],

  'resourcer' => [
    'call_message'   =>'获取资源文件',
    'called_message' =>'获取资源文件',
    'for'            =>'（API.客户端）',
  ],

  'register' => [
    'call_message'   =>'注册',
    'called_message' =>'注册中',
    'success'        =>'已注册',
    'for'            =>'（API和客户端）',
  ],

  'recovery' => [
    'call_message'   =>'恢复',
    'called_message' =>'恢复中',
    'success'        =>'已恢复',
    'for'            =>'（API和客户端）',
  ],

  'storeUpdate'  => [
    'call_message'   =>'创建或编辑',
    'called_message' =>'创建或编辑',
    'success'        =>'编辑成功',
    'for'            =>'（API）',
  ],

  'zippedResource' => [
    'call_message'   => '下载压缩文件',
    'called_message' => '下载压缩文件中',
    'success'        => '已成功下载压缩文件',
    'for'            => '(API和客户端)',
  ],
];

$cvSpecials=[
  'inactives'            => '非活动',
  'general-owner'        => '普通所有者',
  'particular-owner'     => '特定所有者',
  'files-settings'       => '资源文件设置',
  'index-files'          => '列出资源文件',
  'show-files'           => '查看资源文件',
  'create-files'         => '资源文件创建视图',
  'store-files'          => '创建资源文件',
  'edit-files'           => '资源文件编辑视图',
  'update-files'         => '更新资源文件',
  'delete-files'         => '资源文件删除视图',
  'destroy-files'        => '删除资源文件',
  'zippedResource-files' => '下载压缩资源文件',
  'code-hooks'           => '设置代码挂钩',
];

$cvActionsExtra = [
  'common'=>[
    'cancel'    => '取消',
    'back'      => '返回',
    'confirm'   => '确定吗？',
    'correctly' => '正确',
    'of'        => '的',
  ],

  'label'=>'动作',

  'status'=>[
    'yes' =>'是',
    'no'  =>'否',
  ],
];

$cvCrudvel = [
  'context_permission'=>'权限适用于',
];

$cvWeb = [
  'unauthorized'         => '您没有权限执行此操作',
  'has_no_permsissions' => '您没有权限执行此操作',
  'operation_error'     => '无法执行操作，请稍后重试',
  'transaction-error'   => '执行事务时发生错误',
  'success'             => '操作完成',
  'not_found'           => '未找到资源',
  'error'               => '发生意外错误',
  'file_error'          => '尝试访问文件时发生错误',
  'already_exist'       => '已经注册',
  'validation_errors'   => '未通过验证',
];

$cvApi = [
  'unauthorized'           => '您没有权限执行此操作',
  'has_no_permsissions'   => '您没有权限执行此操作',
  'operation_error'       => '无法执行操作，请稍后重试',
  'transaction-error'     => '执行事务时发生错误',
  'not_found'             => '未找到资源',
  'error'                 => '发生意外错误',
  'file_error'            => '尝试访问文件时发生错误',
  'already_exist'         => '已经注册',
  'validation_errors'     => '未通过验证',
  'logget_out'            => '您已注销',
  'success'               => '操作完成',
  'incomplete'            => '操作不完整',
  'bad_paginate_petition' => '分页参数不正确，将使用默认值进行响应',
  'unproccesable'         => '信息不一致',
  'miss_configuration'    => '服务配置错误',
  'true'                  => '是',
  'false'                 => '否',
  'no_files_to_zip'       => '没有资源文件。',
  'no_cache_property'     => '无法访问属性',
];
