<?php

namespace Crudvel\Libraries;

/**
 * @author Energy1011 energy1011@gmail.com
 * @date   2020-03-03
 */
class PermissionSpreadSheetResourceSection implements PermissionSpreadSheetConstructorInterface
{
  use \Crudvel\Libraries\Helpers\SysInstancesTrait;

  public function constructSpreadSheet(){
    $temp = [];
    $temp[] = 'Recursos/Secciones';
    // Set headers: Con acceso, Sección
    $a[0] = ['Recurso/Sección','Con acceso','Sección'];
    // get resources from
    $resources = $this->getSysResources();
    foreach ($resources as $resource) {
      $a[] = [$resource];
    }
    return $a;
  }

}
