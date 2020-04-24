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

  public function beforePaginate($method,$parameters){
    $this->getModelBuilderInstance()->aditionalParticularOwner();
    //pendent to be implementent, doest work well with laravel 6, but does with laravel 7, wait to upgrade before enable it
    //$this->getModelBuilderInstance()->with('resourcer')->solveSearches();
  }

  // [Actions]
  public function show($id){
    $this->getModelBuilderInstance()->with('catFile');

    return $this->actionResponse();
  }

  public function store(){
    $catFile  = CatFile::id($this->getFields()["cat_file_id"])->first();

    if ($catFile->multiple)
      return $this->storeAMultiple($catFile);

    if ($this->getModelBuilderInstance()->catFileId($this->getFields()["cat_file_id"])->resourceId($this->getFields()["resource_id"])->count())
      return $this->updateASingle($catFile);

    return $this->storeASingle($catFile);
  }

  public function update($id){
    $catFile  = CatFile::id($this->getFields()["cat_file_id"])->first();

    if ($catFile->multiple)
      return $this->updateAMultiple($catFile);

    return $this->updateASingle($catFile);
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

  //Refactoring

  protected function storeAMultiple ($catFile) {
    return $this->storeASingle($catFile);
  }

  protected function updateAMultiple ($catFile) {
    return $this->updateASingle($catFile);
  }

  protected function destroyAMultiple ($catFile) {

  }

  protected function storeASingle ($catFile) {
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function() use($catFile){
      $fields   = $this->setStamps()->addField('path','')->addField('disk',$this->getDisk())->getFields();

      if(!$this->setModelCollectionInstance($this->modelInstanciator(true))->getModelCollectionInstance()->fill($fields)->save())
        return false;

      [$filePath, $fileInput, $fileName] = $this->paths();

      if(!$this->getModelCollectionInstance()->path = Storage::disk($this->getModelCollectionInstance()->disk)->putFileAs($filePath, $this->getRequestInstance()->{$fileInput},$fileName))
        return false;

      $this->getModelCollectionInstance()->absolute_path = $this->filePath();

      $this->getModelCollectionInstance()->resourcer->touch();

      return $this->getModelCollectionInstance()->save();
    });

    $this->transactionComplete();

    if(!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    return $this->apiSuccessResponse([
      "data"    => $this->getModelCollectionInstance(),
      "count"   => 1,
      "message" => trans("crudvel.api.success")
    ]);
  }

  protected function updateASingle ($catFile) {
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function() use($catFile){
      $fields   = $this->setStamps()->addField('path','')->addField('disk',$this->getDisk())->getFields();

      if(!$this->deleteFile($this->getModelCollectionInstance()))
        return false;

      if(!$this->getModelCollectionInstance()->fill($fields)->save())
        return false;

      [$filePath, $fileInput, $fileName] = $this->paths();

      if(!$this->getModelCollectionInstance()->path = Storage::disk($this->getModelCollectionInstance()->disk)->putFileAs($filePath, $this->getRequestInstance()->{$fileInput},$fileName))
        return false;

      $this->getModelCollectionInstance()->absolute_path = $this->filePath();

      $this->getModelCollectionInstance()->resourcer->touch();

      return $this->getModelCollectionInstance()->save();
    });

    $this->transactionComplete();

    if(!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    return $this->apiSuccessResponse([
      "data"    => $this->getModelCollectionInstance(),
      "count"   => 1,
      "message" => trans("crudvel.api.success")
    ]);
  }

  protected function destroyASingle ($catFile) {

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

  public function saveFile($clean=false){
    $fields =  $this->getFields();
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function() use($clean,$fields){
      $catFile  = CatFile::id($fields["cat_file_id"]??$this->getModelCollectionInstance()->cat_file_id)->first();

      if($clean){
        $this->deleteFile($this->getModelCollectionInstance());
      }
      /*
      if(!$catFile->multiple || $clean){
        foreach ($this->getModelCollectionInstance() as $file) {
          $this->deleteFile($file);
          if($catFile->multiple)
            $file->delete();
        }
      }*/

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
      $fileName  = cvSlugCase($this->getModelCollectionInstance()->catFile()->first()->name)."-".$this->getModelCollectionInstance()->id.".".$this->getRequestInstance()->{$fileInput}->extension();

      if(!$this->getModelCollectionInstance()->path = Storage::disk($this->getModelCollectionInstance()->disk)->putFileAs($filePath, $this->getRequestInstance()->{$fileInput},$fileName))
        return false;

      $this->getModelCollectionInstance()->absolute_path = $this->filePath();

      return $this->getModelCollectionInstance()->save();
    });
    //$this->getModelCollectionInstance()->relatedFiles()->first()->touch();
    $this->transactionComplete();
    if(!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    return $this->apiSuccessResponse([
      "data"    => $this->getModelCollectionInstance(),
      "count"   => 1,
      "message" => trans("crudvel.api.success")
    ]);
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

  public function getDisk(){
      return $this->disk??null;
  }

  public function setDisk($disk=null){
    $this->disk = $disk??null;

    return $this;
  }

  protected function paths(){
    //$fileName  = cvSlugCase($this->getModelCollectionInstance()->catFile()->first()->name)."-{$this->getModelCollectionInstance()->id}.{$this->getRequestInstance()->{$fileInput}->extension()}";
    $filePath   = "uploads/{$this->getModelCollectionInstance()->catFile->resource}/{$this->getModelCollectionInstance()->resource_id}";
    $fileInput  = $this->getModelCollectionInstance()->catFile->resource;
    $uuid       = (string) \Illuminate\Support\Str::uuid();
    $fileName  = "{$this->getModelCollectionInstance()->mixed_cv_search}-{$uuid}.{$this->getRequestInstance()->{$fileInput}->extension()}";
    return [
      $filePath,
      $fileInput,
      $fileName,
    ];
  }
}
