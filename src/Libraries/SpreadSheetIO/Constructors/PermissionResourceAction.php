<?php

namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

/**
 * This class is used to build permissions spreadsheet data for (Resource/Action) spreadsheet type
 */
class PermissionResourceAction
extends PermissionBase
implements \Crudvel\Interfaces\SpreadSheetIO\ConstructorInterface
{
  public $postfixFilename = 'resource-actions';

  public function __construct($filename, $format = '.xlsx'){
    $this->format = $format;
    $this->filename = $filename.'-'.$this->postfixFilename.$format;
  }

  public function build(){
    $temp = ['Recursos Acciones'];
    //change actions list from cvActions helper that collects actions direct from controllers
    //$actions = $this->getSysLangArrayByKeyName('actions');
    $actions = cvActions();
    // $specials = $this->getSysLangArrayByKeyName('specials');
    $specials = $this->getSysSpecials('specials');
    $specials['(Acceso)']= '(Acceso)';
    // Set headers: actions
    foreach ($actions as $action)
      array_push($temp, $action);
    foreach ($specials as $special=> $value)
      array_push($temp, $special);
    // Set row to heading
    $a[0] = $temp;
    // get resources from
    $resources = $this->getSysResources();
    foreach ($resources as $resource) {
      $a[] = [$resource];
    }
    $this->data = $a;
    return $this->data;
  }

}
