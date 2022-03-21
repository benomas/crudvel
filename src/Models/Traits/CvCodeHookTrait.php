<?php

namespace Crudvel\Models\Traits;

use stdClass;

trait CvCodeHookTrait
{
  protected static function boot(){
    parent::boot();

    self::creating(function($model){
      if(!$model->cvHasCodeHook)
        return;

      if(!empty($model->code_hook))
        return;

      $columnList      = $model->getTableColumns();
      $fixedColumnList = [];

      foreach($columnList as $key=>$value)
        $fixedColumnList[$value]=$value;

      $fallbackColumns = ['slug','name','type'];

      if(($fixedColumnList['code_hook']??null) && empty($model->code_hook)){
        foreach($fallbackColumns as $fallbackColumn){
          if(!($cColumn = $fixedColumnList[$fallbackColumn] ?? null))
            continue;

          if(empty($model->{$cColumn}))
            continue;

          $model->code_hook = cvSlugCase($model->{$fallbackColumn});
          break;
        }
      }
    });
  }
}

//creating and created: sent before and after records have been created.
//updating and updated: sent before and after records are updated.
//saving and saved: sent before and after records are saved (i.e created or updated).
//deleting and deleted: sent before and after records are deleted or soft-deleted.
//restoring and restored: sent before and after soft-deleted records are restored.
//retrieved: sent after records have been retrieved.
