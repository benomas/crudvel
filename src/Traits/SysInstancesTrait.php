<?php
namespace Crudvel\Traits;

trait SysInstancesTrait
{

  public function getSysLangArrayByKeyName($mainKey='', $lang = 'en')
  {
    $langFile = resource_path('lang'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.'crudvel.php');
    $langs = include ($langFile);
    $items = [];
    foreach ($langs[$mainKey] as $key => $value) {
      $key = str_replace('_','-',$key);
      $items[]=$key;
    }
    return $items;
  }
  public function getSysLangArray($mainKey='', $lang='en'){
    $langFile = resource_path('lang'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.'crudvel.php');
    $langs = include ($langFile);
    $items = [];
    foreach ($langs[$mainKey] as $key => $value) {
      $value= str_replace('_','-',$value);
      $items[]=$value;
    }
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
