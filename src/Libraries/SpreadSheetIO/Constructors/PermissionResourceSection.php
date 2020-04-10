<?php

namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

/**
 * This class is used to build permissions spreadsheet data for (Resource/Sections) spreadsheet type
 */
class PermissionResourceSection
extends PermissionBase
implements \Crudvel\Interfaces\SpreadSheetIO\ConstructorInterface
{
  public $postfixFilename = 'section-permissions';
  public $spreadSheetTitle= 'Recursos/Acciones';
  public $data = [];
  public $role = '';

  public function __construct($filename, $format = '.xlsx'){
    $this->format = $format;
    $this->role = $filename;
    $this->filename = $filename.'-'.$this->postfixFilename.$format;
  }

  public function build()
  {
    $a = [];
    // Set headers
    $a[0] = ['Secciones/Permiso', "Acceso a sección"];
    // get resources from
    $resources = $this->getSysLangArrayByKeyName('sections');
    foreach ($resources as $resource) {
      $a[] = [$resource];
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

