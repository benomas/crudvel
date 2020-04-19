<?php namespace Crudvel\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\{File,CatFile};

class FileController extends \Customs\Crudvel\Controllers\ApiController{
  protected $slugField   = 'slug';
  protected $selectables = [
    'absolute_path',
    'active',
    'cat_file_id',
    'cat_file_multiple',
    'cat_file_name',
    'cat_file_slug',
    'cat_file_resource',
    'cat_file_cv_search',
    'resource_id',
    'resource_cv_search',
    'created_at',
    'id',
    'path',
    'updated_at',
    'disk',
    'cv_search',
  ];
  protected $disk = "public";

  public function __construct(){
    parent::__construct();
    $this->addAction('storeUpdate');
  }


  // [Actions]
  public function show($id){
    $this->getModelBuilderInstance()->with('catFile');
    return $this->actionResponse();
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
    return $this->deleteFile() && $this->getModelCollectionInstance()->delete()?
      $this->apiSuccessResponse():
      $this->apiFailResponse();
  }

  public function activate($id){
    $this->addField('active',1);
    $this->addField('id',$id);
    $this->setStamps();
    return parent::update($id);
  }

  public function deactivate($id){
    $this->addField('active',0);
    $this->addField('id',$id);
    $this->setStamps();
    return parent::update($id);
  }
  // [End Actions]

  // [Methods]

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

  public function saveFile($clean=null){
    $fields =  $this->getFields();
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function() use($clean,$fields){
      $catFile  = CatFile::id($fields["cat_file_id"]??$this->getModelCollectionInstance()->cat_file_id)->first();

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
    //$this->getModelCollectionInstance()->relatedFiles()->first()->touch();
    $this->transactionComplete();
    if(!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    return $this->show(0);
  }

  public function filePath(){
    return $this->getModelCollectionInstance()? asset("storage/".$this->getModelCollectionInstance()->path):"";
  }

  public function deleteFile($file = null){
    if(!($file = $file??$this->getModelCollectionInstance()))
      return true;
    if(!Storage::disk($file->disk)->exists($file->path))
      return true;

    return Storage::disk($file->disk)->delete($file->path);
  }

  protected function resourcesBeforePaginate(){
    $this->setSelectables(['label','value']);
  }

  public function syncCvSearch(){
    \DB::transaction(function () {
      foreach(\App\Models\File::all() as $file){
        $file->resource_id = $file->resource_id;
        $file->cat_file_id = $file->cat_file_id;
        $file->save();
      }
    });
  }
  // [End Methods]
}
