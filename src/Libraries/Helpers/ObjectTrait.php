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
}
