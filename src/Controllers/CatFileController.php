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

  public function __construct(){
    parent::__construct();
  }
}
