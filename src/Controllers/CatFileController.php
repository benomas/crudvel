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
    'resource_label',
  ];

  public function __construct(){
    parent::__construct();

    $this->addAction('resources')->addRowActions('resources');
  }
  // [Actions]
  public function resources(){
    $resourcesCatalog = [];
    if(method_exists($this,'getUserModelCollectionInstance') && $this->getUserModelCollectionInstance()){
      foreach(cvResourcesCatalog() AS $resource){
        if(!$this->actionAccess($resource['value'].'.update') || !$this->actionAccess($resource['value'].'.create') || !$this->actionAccess($resource['value'].'.index'))
          continue;

        $resourcesCatalog[] = $resource;
      }
    }
    $this->getPaginatorInstance()->setCollectionData(collect($resourcesCatalog));

    return $this->actionResponse();
  }
  // [End Actions]

  // [Methods]
  protected function resourcesBeforePaginate(){
    $this->setSelectables(['label','value']);
  }

  public function beforePaginate($method,$parameters){
    $this->getModelBuilderInstance()->setQuery(\App\Models\CatFile::cvIam()->selfDinamic());
  }
  // [End Methods]
}
