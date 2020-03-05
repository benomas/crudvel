<?php

namespace Crudvel\Libraries;

/**
 * @author Energy1011 energy1011@gmail.com
 * @date   2020-03-03
 */
class PermissionSpreadSheetResourceAction implements PermissionSpreadSheetConstructorInterface
{
  use \Crudvel\Libraries\Helpers\SysInstancesTrait;

  public function constructSpreadSheet(){
    $temp = [];
    $temp[] = 'Recursos/Acciones';
    $actions = $this->getSysActions();
    $specials = $this->getSysSpecials();
    // Set headers: actions
    foreach ($actions as $action => $value)
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
    return $a;
  }

}
