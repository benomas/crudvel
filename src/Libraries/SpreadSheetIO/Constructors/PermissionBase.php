<?php
namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

/*BaseClass for permissions spreadsheets constructors its used to import and export permissions*/
class PermissionBase {
  use \Crudvel\Traits\SysInstancesTrait;
  public $path = 'permissions'.DIRECTORY_SEPARATOR;
  public $fullPath= 'app'.DIRECTORY_SEPARATOR.'private'.DIRECTORY_SEPARATOR.'permissions'.DIRECTORY_SEPARATOR;
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
    return storage_path($this->fullPath.$this->getFilenameAttr());
  }
}
