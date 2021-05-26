<?php
namespace Crudvel\Libraries\Helpers;

trait ObjectTrait
{
  public function caller($depth=3,$property='function'){
    return (new \Exception)->getTrace()[$depth][$property]??'';
  }

  public function classFile ($className){
    return (new \ReflectionClass($className))->getFileName();
  }

  public static function fkP ($property,$value = null) {
    $fakeStdClassProp            = new \StdClass;
    $fakeStdClassProp->$property = $value;

    return $fakeStdClassProp;
  }
}
