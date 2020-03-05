<?php namespace Crudvel\Controllers;

class CatFileController extends \Customs\Crudvel\Controllers\ApiController{

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
    'cv_search',
  ];

  public function __construct(){
    parent::__construct();
  }
}
