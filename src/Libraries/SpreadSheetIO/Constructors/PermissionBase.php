<?php
namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

/*BaseClass for permissions spreadsheets constructors its used to import and export permissions*/
class PermissionBase {
  use \Crudvel\Traits\SysInstancesTrait;
  public $path = 'permission-role'.DIRECTORY_SEPARATOR;
  public $fullPath= 'database'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'permission-role'.DIRECTORY_SEPARATOR;
  public $format = '.xlsx';
  public $data = [];

  public function getFilenameAttr(){
    return $this->filename;
  }

  public function getFormatAttr(){
    return $this->format;
  }

  public function getFilePath(){
    return $this->path.$this->getFilenameAttr();
  }

  public function getFullFilePath(){
    return base_path($this->fullPath.$this->getFilenameAttr());
  }

  public function getRelatedPath(){
    return $this->path;
  }

  public function num2alpha($n)
  {
    for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
        $r = chr($n%26 + 0x41) . $r;
    return $r;
  }
}
