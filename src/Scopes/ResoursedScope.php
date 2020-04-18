<?php

namespace Crudvel\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ResoursedScope implements Scope
{
  public function apply(Builder $builder, Model $model){
    $builder->addSelect(['test'=>\DB::table("{$model->getTable()} as gr")->
      whereColumn('gr.id', "{$model->getTable()}.id")
      ->limit(1)
      ->selectRaw('1 as test')
    ]);
  }
}
