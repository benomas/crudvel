<?php namespace Crudvel\Controllers;

class CatFileController extends \Crudvel\Customs\Controllers\ApiController{

  protected $selectables = [
    'active',
    'created_at',
    'description',
    'multiple',
    'group',
    'id',
    'max_size',
    'min_size',
    'name',
    'required',
    'slug',
    'types',
    'updated_at',
    'resource',
  ];

  protected $filterables = [
    'active',
    'created_at',
    'description',
    'group',
    'id',
    'max_size',
    'min_size',
    'multiple',
    'name',
    'required',
    'slug',
    'types',
    'updated_at',
    'resource',
  ];
  protected $orderables = [
    'active',
    'created_at',
    'description',
    'group',
    'id',
    'max_size',
    'min_size',
    'multiple',
    'name',
    'required',
    'slug',
    'types',
    'updated_at',
    'resource',
  ];

  public function __construct(){
    parent::__construct();
  }
}
