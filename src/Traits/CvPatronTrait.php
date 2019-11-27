<?php

namespace Crudvel\Traits;

trait CvPatronTrait
{
  public static function cvIam($force=false)
  {
    return $GLOBALS['cv-singleton-'.($selfClass = get_called_class())] = $force?new $selfClass():$GLOBALS['cv-singleton-'.$selfClass]??new $selfClass();
  }
}
