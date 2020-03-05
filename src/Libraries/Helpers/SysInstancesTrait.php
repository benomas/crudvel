<?php
namespace Crudvel\Libraries\Helpers;

trait SysInstancesTrait
{

  public function getSysSections()
  {
    $items = trans('crudvel.sections');
    return $items;
  }

  public function getSysResources($lang='en')
  {
    $controllers  = scandir(app_path('../resources/lang/' . $lang . '/crudvel'));
    $items = [];
    foreach ($controllers as $ctrl) {
      $ctrl = preg_replace("/.php$/", "", $ctrl);
      if (strlen($ctrl) > 2)
        array_push($items, $ctrl);
    }
    ksort($items);
    return $items;
  }

  public function getSysActions()
  {
    $items = trans('crudvel.actions');
    ksort($items);
    return $items;
  }

  public function getSysSpecials()
  {
    $items = trans('crudvel.specials');
    ksort($items);
    return $items;
  }

  /** Get resource list from api controllers folder */
  public function getSysResourcesFromApiControllers()
  {
    //TODO: Complete this function
    return false;
    $controllers  = scandir(app_path('Http/Controllers/Api'));
    $temp = [];
    foreach ($controllers as $ctrl) {
      $ctrl = Str::kebab(preg_replace("/Controller.php$/", "", $ctrl), '-');
      if (strlen($ctrl) > 2)
        array_push($temp, $ctrl);
    }
    return $temp;
  }
}
