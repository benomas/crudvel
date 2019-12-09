<?php namespace Crudvel\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\{File,CatFile};

class FileController extends \Crudvel\Customs\Controllers\ApiController{
  protected $slugField   = 'slug';
  protected $selectables = [
    'absolute_path',
    'active',
    'cat_file_id',
    'cat_file_multiple',
    'cat_file_name',
    'cat_file_slug',
    'cat_file_resource',
    'resource_id',
    'created_at',
    'id',
    'path',
    'updated_at',
    'cv_search'
  ];
  protected $disk = "public";

  public function __construct(){
    parent::__construct();
    $this->addActions('storeUpdate');
  }

  public function addedCvSearch(){
    return $this->selfPreBuilder('f')
      ->join('cat_files as cf', 'f.cat_file_id', '=', 'cf.id')
      ->selectRaw("CONCAT(cf.name, ' ',cf.resource, ' ',f.id)");
  }

  public function addedCatFileMultiple(){
    return CatFile::invokePosfix($this->getModelClass(),'multiple');
  }
  public function addedCatFileName(){
    return CatFile::invokePosfix($this->getModelClass(),'name');
  }
  public function addedCatFileSlug(){
    return CatFile::invokePosfix($this->getModelClass(),'slug');
  }
  public function addedCatFileResource(){
    return CatFile::invokePosfix($this->getModelClass(),'resource');
  }

  public function unions(){
    $catFiles = CatFile::hasFile()->get();
    if(!$catFiles || !count($catFiles)){
      if (($key = array_search('cv_search', $this->selectables)) !== false)
        unset($this->selectables[$key]);
      if (($key = array_search('cv_search', $this->filterables)) !== false)
        unset($this->filterables[$key]);
      if (($key = array_search('cv_search', $this->orderables)) !== false)
        unset($this->orderables[$key]);
      $this->currentPaginator->setFilterables($this->selectables);
      $this->currentPaginator->setOrderables($this->filterables);
      $this->currentPaginator->setSelectables($this->orderables);
      $this->currentPaginator->fixSelectQuery();
      $this->currentPaginator->fixFilterQuery();
      $this->currentPaginator->fixOrderables();
      return parent::unions();
    }
    $file     = new File;
    $unions   = null;
    $selectQuery = $this->currentPaginator->getSelectQuery();
    foreach ($catFiles as $catFile) {
      $modelResource = $catFile->modelClassInstance();
      $resourceUnion = kageBunshinNoJutsu($this->currentPaginator->getModel());
      $resourceUnion->join($modelResource->getTable(),function($j) use($modelResource,$file,$catFile){
        $j->on($file->getTable().'.resource_id', '=', $modelResource->getTable().'.id')->
        where($file->getTable().'.cat_file_id', '=', $catFile->id);
      });
      if($this->currentPaginator->unsolvedColumns[$modelResource->getSearchFieldColumn()]??null)
        $selectQuery[$this->currentPaginator->unsolvedColumns[$modelResource->getSearchFieldColumn()]]= $modelResource->getTable().'.'.$modelResource->getSearchFieldColumn()." AS ".$modelResource->getSearchFieldColumn();
      $resourceUnion->select($selectQuery);
      if($unions)
        $unions->union($resourceUnion);
      else
        $unions = $resourceUnion;
    }
    if($unions)
      $this->model = $this->currentPaginator->setModel($unions)->getModel();
  }

  public function show($id){
    $this->getModelBuilderInstance()->with('catFile');
    if(
      $this->getRootInstance() &&
      $this->getRootInstance()->getPaginable() &&
      $this->getPaginatorInstance()->extractPaginate()
    )
      return $this->getPaginatorInstance()->paginatedResponse();
    return $this->getPaginatorInstance()->noPaginatedResponse();
  }

  public function saveFile($clean=null){
    $fields =  $this->getFields();
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function() use($clean,$fields){
      $catFile  = CatFile::id($fields["cat_file_id"])->first();

      if(!$catFile->multiple || $clean){
        foreach ($this->getModelCollectionInstance() as $file) {
          $this->deleteFile($file);
          if($catFile->multiple)
            $file->delete();
        }
      }

    if($this->getCurrentAction()==='store')
      $this->modelInstanciator(true);

    $this->setModelCollectionInstance(
      $this->getModelCollectionInstance()->first()??$this->modelInstanciator(true)
    );
    $this->setStamps();
    $fields["path"]="";
    $fields["disk"]=$this->disk;
    $this->getModelCollectionInstance()->fill($fields);
    $this->dirtyPropertys = $this->getModelCollectionInstance()->getDirty();
    if(!$this->getModelCollectionInstance()->save())
      return false;

    $filePath  = 'uploads/'.$this->getModelCollectionInstance()->catFile->resource.'/'.$this->getModelCollectionInstance()->resource_id;
    $fileInput = $this->getModelCollectionInstance()->catFile->resource;
    $fileName  = \Str::slug($this->getModelCollectionInstance()->catFile()->first()->name)."-".$this->getModelCollectionInstance()->id.".".$this->getRequestInstance()->{$fileInput}->extension();
    if(!$this->getModelCollectionInstance()->path = Storage::disk($this->getModelCollectionInstance()->disk)->putFileAs($filePath, $this->getRequestInstance()->{$fileInput},$fileName))
      return false;
    $this->getModelCollectionInstance()->absolute_path = $this->filePath();
      return $this->getModelCollectionInstance()->save();
    });
    $this->getModelCollectionInstance()->resource->touch();
    $this->transactionComplete();
    if(!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    return $this->show(0);
  }

  public function store(){
    $fields =  $this->getFields();
    $catFile  = CatFile::id($fields["cat_file_id"])->first();
    $this->setModelCollectionInstance(
      $this->getModelBuilderInstance()->catFileId($fields["cat_file_id"])->resourceId($fields["resource_id"])->get()
    );
    return $this->saveFile();
  }

  public function update($id){
    $this->setModelCollectionInstance($this->getModelBuilderInstance()->get());
    return $this->saveFile(true);
  }

  public function storeUpdate(){
    $fields =  $this->getFields();
    $this->setModelCollectionInstance(
      $this->getModelBuilderInstance()->catFileId($fields["cat_file_id"])->resourceId($fields["resource_id"])->get()
    );
    return $this->saveFile(true);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    return $this->deleteFile() && $this->getModelBuilderInstance()->first()->delete()?
      $this->apiSuccessResponse():
      $this->apiFailResponse();
  }

  public function filePath(){
    return $this->getModelCollectionInstance()? asset("storage/".$this->getModelCollectionInstance()->path):"";
  }

  public function deleteFile($file = null){
    $file=$file??$this->getModelBuilderInstance();
    if(!$file)
      return true;
    if(!Storage::disk($file->disk)->exists($file->path))
      return true;

    return Storage::disk($file->disk)->delete($file->path);
  }
}
