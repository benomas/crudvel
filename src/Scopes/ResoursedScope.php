<?php

namespace Crudvel\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ResoursedScope implements Scope
{
  public function apply(Builder $builder, Model $model){
  }
}
