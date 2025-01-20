<?php namespace Crudvel\Controllers;

use Crudvel\CvFile\CvFileBridge;
use Crudvel\CvFile\CvFile;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\{File, CatFile};
use Customs\Crudvel\Controllers\ApiController;

class FileController extends ApiController implements CvFileBridge {
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

  public function __construct() {
    parent::__construct();
  }

  public function beforeFlowControl(): void {
    $this->getModelBuilderInstance()->aditionalParticularOwner();
    //pendent to be implementent, doest work well with laravel 6, but does with laravel 7, wait to upgrade before enable it
    //$this->getModelBuilderInstance()->with('resourcer')->solveSearches();
  }

  // [Actions]
  public function show($id) {
    $this->getModelBuilderInstance()->with('catFile');

    return $this->actionResponse();
  }

  public function store(): Response|JsonResponse {
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function () {
      $cvFile = new CvFile($this);
      return $cvFile->store();
    });

    $this->transactionComplete();

    if (!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    return $this->apiSuccessResponse([
      "data"    => $this->getModelCollectionInstance(),
      "count"   => 1,
      "message" => trans("crudvel.api.success")
    ]);
  }

  public function update($id): Response|JsonResponse {
    $this->resetTransaction();
    $this->startTranstaction();
    $this->testTransaction(function () {
      $cvFile = new CvFile($this);
      return $cvFile->update();
    });

    $this->transactionComplete();

    if (!$this->isTransactionCompleted())
      return $this->apiFailResponse();

    return $this->apiSuccessResponse([
      "data"    => $this->getModelCollectionInstance(),
      "count"   => 1,
      "message" => trans("crudvel.api.success")
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function destroy($id): JsonResponse {
    $cvFile = new CvFile($this);

    return $cvFile->destroy() ?
      $this->apiSuccessResponse() :
      $this->apiFailResponse();
  }

  public function activate($id): Response|JsonResponse {
    $this->addField('active', 1);
    $this->addField('id', $id);
    $this->setStamps();
    return parent::update($id);
  }

  public function deactivate($id): Response|JsonResponse {
    $this->addField('active', 0);
    $this->addField('id', $id);
    $this->setStamps();
    return parent::update($id);
  }
  // [End Actions]

  // [Methods]

  //Refactoring
  public function addedCatFileMultiple() {
    return CatFile::invokePosfix($this->getModelClass(), 'multiple');
  }

  public function addedCatFileName() {
    return CatFile::invokePosfix($this->getModelClass(), 'name');
  }

  public function addedCatFileSlug() {
    return CatFile::invokePosfix($this->getModelClass(), 'slug');
  }

  public function addedCatFileResource() {
    return CatFile::invokePosfix($this->getModelClass(), 'resource');
  }

  protected function resourcesBeforeFlowControl(): void {
    $this->setSelectables(['label', 'value']);
  }

  public function syncCvSearch(): void {
    \DB::transaction(function () {
      foreach (File::all() as $file) {
        $file->resource_id = $file->resource_id;
        $file->cat_file_id = $file->cat_file_id;
        $file->save();
      }
    });
  }

  public function getFileModelInstance(): File {
    if ($this->getModelCollectionInstance())
      return $this->getModelCollectionInstance();

    return $this->setModelCollectionInstance($this->modelInstantiator(true))->getModelCollectionInstance();
  }

  public function getFileName(): string {
    return $this->getRequestInstance()->{$this->getModelCollectionInstance()->catFile->resource}->extension();
  }

  public function getFileReference(?string $fileInput): HttpFile|UploadedFile|string {
    return $this->getRequestInstance()->{$this->getModelCollectionInstance()->catFile->resource};
  }
// [End Methods]
}
