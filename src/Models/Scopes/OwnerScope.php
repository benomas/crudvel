<?php

namespace Crudvel\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use CvResource;

class OwnerScope implements Scope
{

  public function apply(Builder $builder, Model $model)
  {
    if (!method_exists($model,'getUserModelBuilderInstance'))
      return;
    //pdd(CvResource::assignUser()->getUserModelCollectionInstance());
    //$user = $model->getUserModelBuilderInstance()->withoutGlobalScope(OwnerScope::class)->first();
    //pdd($user);
    //pdd($builder->getModel()->getUserModelBuilderInstance()->withoutGlobalScope('owner')->first());
    //$builder->where('age', '>', 200);
  }
}
