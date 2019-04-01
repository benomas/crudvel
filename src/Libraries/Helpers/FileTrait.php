<?php
namespace Crudvel\Libraries\Helpers;

trait FileTrait
{
  public function base64Src($filePath){
    return file_exists($filePath)?'data:'.mime_content_type($filePath).';base64,'.base64_encode(file_get_contents($filePath)):null;
  }
}
