<?php

namespace Crudvel\Collection;

class BaseCollection extends \Illuminate\Database\Eloquent\Collection {

  public function makeVisibleOnly(...$visibleAttributes){
    if(!count($this->items))
      return $this;

    $attributes = array_keys($this->items[0]->toArray());

    return $this->each->makeHidden($attributes)->makeVisible($visibleAttributes);
  }
}
