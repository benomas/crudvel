<?php

namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

/**
 * This class is used to build permissions spreadsheet data for (Resource/Sections) spreadsheet type
 */
class PermissionGlobalSpecials
extends PermissionBase
implements \Crudvel\Interfaces\SpreadSheetIO\ConstructorInterface
{
  public $postfixFilename = 'global-specials';
  public $spreadSheetTitle= 'GlobalEspecial/Permiso';
  public $data = [];

  public function __construct($filename, $format = '.xlsx'){
    $this->format = $format;
    $this->filename = $filename.'-'.$this->postfixFilename.$format;
  }

  public function build()
  {
    $a = [];
    // Set headers
    $a[0] = [$this->getSpreadSheetTitle(), "Acceso"];
    $gSpecials= $this->getSysLangArrayByKeyName('globalSpecials');
    foreach ($gSpecials as $special) {
      $a[] = [$special];
    }
    return $a;
  }

  public function synchronize()
  {

  }

  public function getSpreadSheetTitle(){
    return $this->spreadSheetTitle;
  }

  public function getData(){
    return $this->data;
  }

}

