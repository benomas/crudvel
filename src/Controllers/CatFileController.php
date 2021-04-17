<?php namespace Crudvel\Controllers;

class CatFileController extends \Customs\Crudvel\Controllers\ApiController{

  protected $selectables = [
    'active',
    'created_at',
    'description',
    'multiple',
    'group',
    'cv_group',
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
    $this->addAction('resources','resourcer','zippedResourceFiles')->addRowActions('resources')->addRowsActions('resourcer','zippedResourceFiles');
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

  public function zippedResourceFiles($resource, $key){
    if($this->getPaginated())
      $this->getPaginatorInstance()->processPaginatedResponse();

    if(!$this->getModelBuilderInstance()->count())
      return $this->apiFailResponse(['message'=> 'error', 'errorMessage'=>trans("crudvel.api.no_files_to_zip")]);

    $catFiles      = $this->getModelBuilderInstance()->get();
    $zip           = null;
    $resourceModel = null;

    foreach ($catFiles as $catFile){
      foreach ($catFile->files??[] as $file){
        if (!$zip){
          $resourceModel = $file->resourcer;
          $zip           = new \ZipArchive();
          $zip->open($resourceModel->fileZipOptPath($resource), \ZipArchive::CREATE);
        }

        $path     = storage_path("app{$this->cvDs()}public{$this->cvDs()}{$file->path}");
        $fileInfo = pathinfo($path);
        $zip->addFile($path, "{$file->catFile->name}.{$fileInfo['extension']}");
      }
    }

    if (!$resourceModel)
      return $this->apiFailResponse(['message'=> 'error', 'errorMessage'=>trans("crudvel.api.no_files_to_zip")]);

    $zip->close();
    return response()->download($resourceModel->fileZipOptPath($resource))
      ->deleteFileAfterSend($resourceModel->fileZipOptDeleteFileAfterSend());
  }
  // [End Actions]

  // [Methods]
  public function addedCvGroup(){
    return $this->selfPreBuilder('cf')->selectRaw('cf.group');
  }

  public function resourcerBeforeFlowControl(){
    $this->getModelBuilderInstance()->resource($this->getCallActionParameters()['resource'])->with(['files'=>function($query) {
      $query->resourceKey($this->getCallActionParameters()['key']);
    }]);
  }

  public function zippedResourceFilesBeforeFlowControl(){
    $paginate = $this->getFields()['paginate'] ?? [];
    $paginate['limit'] = null;
    $this->addField('paginate', $paginate);
    $this->getModelBuilderInstance()->resource($this->getCallActionParameters()['resource'])->with(['files'=>function($query) {
      $query->resourceKey($this->getCallActionParameters()['key']);
    }]);
  }

  public function resourcesBeforeFlowControl(){
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
