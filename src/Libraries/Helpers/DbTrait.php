<?php
namespace Crudvel\Libraries\Helpers;

trait DbTrait
{

  public function cvDbBuilder($sql){
    return \DB::query()->selectRaw($sql);
  }
}
