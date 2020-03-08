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

  public function __construct($filename, $format = '.xlsx'){
    $this->format = $format;
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

}

