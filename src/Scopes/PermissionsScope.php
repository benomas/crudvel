<?php
namespace Crudvel\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PermissionsScope implements Scope
{
  public function apply(Builder $builder, Model $model){
    if(!$this->modelExceptions($model)){
      $GLOBALS[get_class($model)] = false;
      $model->scopeCvOwner($builder);
      $GLOBALS[get_class($model)] = true;
    }
  }

  public function modelExceptions($currentModel){
    if(class_basename($currentModel) === 'CatPermissionType')
      return true;
    return !($GLOBALS[get_class($currentModel)] = $GLOBALS[get_class($currentModel)] ?? true);
  }
}
