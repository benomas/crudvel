<?php

namespace Crudvel\Collection;
use Illuminate\Database\Eloquent\Collection;

class BaseCollection extends Collection {

  public function makeVisibleOnly(...$visibleAttributes){
    if(!count($this->items))
      return $this;

    $attributes = array_keys($this->items[0]->toArray());

    return $this->each->makeHidden($attributes)->makeVisible($visibleAttributes);
  }
}
