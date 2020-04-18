<?php
namespace Crudvel\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SolveSearchScopes implements Scope
{
  public function apply(Builder $builder, Model $model){
    $model->scopeCvOwner($builder);
    return ;
    if(method_exists($model,'scopeSolveSearches'))
      $model->scopeSolveSearches($builder);
  }
}
