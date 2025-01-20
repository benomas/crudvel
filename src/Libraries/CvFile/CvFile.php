<?php

namespace Crudvel\CvFile;

use Crudvel\Models\CatFile;
use Crudvel\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvFile {

  protected CvFileBridge $cvFileBridge;
  protected string       $disk = "public";

  public function __construct(CvFileBridge $cvFileBridge) {
    $this->setCvFileBridge($cvFileBridge);
  }

  public function store(): bool {
    $catFile = CatFile::id($this->getCvFileBridge()->getFields()["cat_file_id"])->first();

    $fileModel = $this->getCvFileBridge()->getFileModelInstance();

    if ($catFile->multiple)
      return $this->storeASingle();

    if (
      $fileModel->catFileId($this->getCvFileBridge()->getFields()["cat_file_id"] ?? null)
        ->resourceId($this->getCvFileBridge()->getFields()["resource_id"] ?? null)->count()) {
      return $this->updateASingle();
    }

    return $this->storeASingle($fileModel);
  }

  public function update(): bool {
    return $this->updateASingle();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return JsonResponse
   */
  public function destroy(): bool {
    $fileModel = $this->getCvFileBridge()->getFileModelInstance();

    return $this->deleteFile($fileModel) && $fileModel->delete();
  }

  protected function storeASingle(?File $fileModel = null): bool {
    $fields    = $this->getCvFileBridge()->setStamps()->addField('path', '')->addField('disk', $this->getDisk())->getFields();
    $fileModel = $fileModel ?? $this->getCvFileBridge()->getFileModelInstance();

    if (!$fileModel->fill($fields)->save())
      return false;

    [$filePath, $fileInput, $fileName] = $this->paths();

    $fileModel->path = Storage::disk($fileModel->disk)
      ->putFileAs($filePath, $this->getCvFileBridge()->getFileReference($fileInput), $fileName);

    if (!$fileModel->path) {
      return false;
    }

    $fileModel->absolute_path = $this->filePath();
    $fileModel->resourcer->touch();

    return $fileModel->save();
  }


  protected function updateASingle(): bool {
    $fields = $this->getCvFileBridge()->setStamps()->addField('path', '')->addField('disk', $this->getDisk())->getFields();
    $fileModel = $this->getCvFileBridge()->getFileModelInstance();

    if (!$this->deleteFile($fileModel))
      return false;

    if (!$fileModel->fill($fields)->save())
      return false;

    return $this->storeASingle($fileModel);
  }

  public function deleteFile(File $fileModel): bool {
    if (!Storage::disk($fileModel->disk)->exists($fileModel->path))
      return true;

    return Storage::disk($fileModel->disk)->delete($fileModel->path);
  }

  protected function paths(): array {
    $fileModel = $this->getCvFileBridge()->getFileModelInstance();
    $filePath  = "uploads/{$fileModel->catFile->resource}/{$fileModel->resource_id}";
    $fileInput = $fileModel->catFile->resource;
    $uuid      = (string)Str::uuid();
    $fileName  = "{$uuid}.{$this->getCvFileBridge()->getFileName()}";

    return [
      $filePath,
      $fileInput,
      $fileName,
    ];
  }

  public function filePath(): string {
    return  asset("storage/{$this->getCvFileBridge()->getFileModelInstance()->path}");
  }

//[Getters]
  public function getCvFileBridge(): CvFileBridge {
    return $this->cvFileBridge;
  }

  public function getDisk(): string {
    return $this->disk;
  }
// [End Getters]

//[Getters]
  public function setCvFileBridge(CvFileBridge $cvFileBridge): static {
    $this->cvFileBridge = $cvFileBridge;

    return $this;
  }

  public function setDisk(string $disk): static {
    $this->disk = $disk;

    return $this;
  }
// [End Setters]
}
