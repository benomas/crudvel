<?php
namespace Crudvel\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PermissionsScope implements Scope
{
  public function apply(Builder $builder, Model $model){
    if(($GLOBALS[get_class($model)] = $GLOBALS[get_class($model)] ?? true)){
      customLog(get_class($model));
      $GLOBALS[get_class($model)] = false;
      $model->scopeCvOwner($builder);
      $GLOBALS[get_class($model)] = true;
    }
  }
}
