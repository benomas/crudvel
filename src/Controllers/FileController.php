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
    'cat_file_resorce',
    'resource_id',
    'created_at',
    'id',
    'path',
    'updated_at',
    'search_field'
  ];
  protected $filterables = [
    'absolute_path',
    'active',
    'cat_file_id',
    'cat_file_multiple',
    'cat_file_name',
    'cat_file_slug',
    'cat_file_resorce',
    'resource_id',
    'created_at',
    'id',
    'path',
    'updated_at',
    'search_field'
  ];
  protected $orderables = [
    'absolute_path',
    'active',
    'cat_file_id',
    'cat_file_multiple',
    'cat_file_name',
    'cat_file_slug',
    'cat_file_resorce',
    'resource_id',
    'created_at',
    'id',
    'path',
    'updated_at',
    'search_field'
  ];

  protected $joinables =[
    'cat_file_name'     => 'cat_files.name',
    'cat_file_multiple' => 'cat_files.multiple',
    'cat_file_slug'     => 'cat_files.slug',
    'cat_file_resorce'  => 'cat_files.resource'
  ];

  protected $disk = "public";

  public function joins(){
    $this->model->join('cat_files', 'files.cat_file_id', '=', 'cat_files.id');
  }

  public function __construct(){
    parent::__construct();
    $this->addActions('storeUpdate');
  }

  public function unions(){
    $catFiles = CatFile::hasFile()->get();
    if(!$catFiles || !count($catFiles)){
      if (($key = array_search('search_field', $this->selectables)) !== false)
        unset($this->selectables[$key]);
      if (($key = array_search('search_field', $this->filterables)) !== false)
        unset($this->filterables[$key]);
      if (($key = array_search('search_field', $this->orderables)) !== false)
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
    if(!$this->paginable || !$this->currentPaginator->extractPaginate())
      return  $this->noPaginatedResponse();

    $this->currentPaginator->processPaginatedResponse();
    $this->paginateData=$this->model->with('catFile');
    return $this->currentPaginator->paginateResponder();
  }

  public function saveFile($clean=null){
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function() use($clean){
      $catFile  = CatFile::id($this->fields["cat_file_id"])->first();

      if(!$catFile->multiple || $clean){
        foreach ($this->modelInstance as $file) {
          $this->deleteFile($file);
          if($catFile->multiple)
            $file->delete();
        }
      }

    if($this->currentAction==='store')
      $this->modelInstanciator(true);

    $this->modelInstance = $this->modelInstance->first()??$this->modelInstanciator(true);
    $this->setStamps();
    $this->fields["path"]="";
    $this->fields["disk"]=$this->disk;
    $this->modelInstance->fill($this->fields);
    $this->dirtyPropertys = $this->modelInstance->getDirty();
    if(!$this->modelInstance->save())
      return false;

    $filePath  = 'uploads/'.$this->modelInstance->catFile->resource.'/'.$this->modelInstance->resource_id;
    $fileInput = $this->modelInstance->catFile->resource;
    $fileName  = str_slug($this->modelInstance->catFile()->first()->name)."-".$this->modelInstance->id.".".$this->requestInstance->{$fileInput}->extension();
    if(!$this->modelInstance->path = Storage::disk($this->modelInstance->disk)->putFileAs($filePath, $this->requestInstance->{$fileInput},$fileName))
      return false;
    $this->modelInstance->absolute_path = $this->filePath();
      return $this->modelInstance->save();
    });
    $this->modelInstance->resource->touch();
    $this->transactionComplete();
    if(!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    if($this->paginable && $this->currentPaginator->extractPaginate()){
      $this->currentPaginator->processPaginatedResponse();
      $this->paginateData=$this->model->with('catFile');
      return $this->currentPaginator->paginateResponder();
    }

    return $this->noPaginatedResponse();
  }

  public function store(){
    $catFile  = CatFile::id($this->fields["cat_file_id"])->first();
    $this->modelInstance = $this->model->catFileId($this->fields["cat_file_id"])->resourceId($this->fields["resource_id"])->get();
    return $this->saveFile();
  }

  public function update($id){
    $this->modelInstance = $this->model->get();
    return $this->saveFile(true);
  }

  public function storeUpdate(){
    $this->modelInstance = $this->model->catFileId($this->fields["cat_file_id"])->resourceId($this->fields["resource_id"])->get();
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
    return $this->deleteFile() && $this->model->first()->delete()?
      $this->apiSuccessResponse():
      $this->apiFailResponse();
  }

  public function filePath(){
    return $this->modelInstance? asset("storage/".$this->modelInstance->path):"";
  }

  public function deleteFile($file = null){
    $file=$file??$this->modelInstance;
    if(!$file)
      return true;
    if(!Storage::disk($file->disk)->exists($file->path))
      return true;

    return Storage::disk($file->disk)->delete($file->path);
  }
}
