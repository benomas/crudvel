<?php

namespace Crudvel\CvFile;

use Crudvel\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File as HttpFile;

Interface CvFileBridge {
  public function getFields(...$params);
  public function setStamps($enableCreateStamps = true, $enableUpdateStamps = true);
  public function addField($field = null, $value = null);
  public function getFileModelInstance(): File;
  public function getFileNameExtension(): string;
  public function getFileReference (?string $fileInput) : HttpFile|UploadedFile|string;
}

