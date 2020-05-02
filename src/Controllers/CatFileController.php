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
    $this->addAction('resources','resourcer')->addRowActions('resources')->addRowsActions('resourcer');
  }

  public function callAction($method,$parameters=[]){
    if ($method === 'resources')
      $this->setSkipCollectionValidation(true);

    return parent::callAction($method,$parameters);
  }

  // [Actions]
  public function resourcer($resource,$key){
    return $this->index();
  }

  public function resources(){
    $this->getPaginatorInstance()->setCollectionData(collect(\App\Models\CatFile::cvIam()->resourceCatalogs()));

    return $this->actionResponse();
  }
  // [End Actions]

  // [Methods]
  protected function resourcerBeforePaginate($parameters){
    $this->getModelBuilderInstance()->resource($parameters['resource'])->with(['files'=>function($query) use($parameters){
      $query->resourceKey($parameters['key']);
    }]);
  }

  protected function resourcesBeforeFlowControl(){
    $this->setSelectables(['label','value']);
  }

  public function syncCvSearch(){
    \DB::transaction(function () {
      foreach(\App\Models\CatFile::all() as $catFile){
        $catFile->resource = $catFile->resource;
        $catFile->save();
      }
    });
  }
  // [End Methods]
}
