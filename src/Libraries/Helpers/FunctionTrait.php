<?php
namespace Crudvel\Libraries\Helpers;

trait FunctionTrait
{
  public function uCProp($uCprop=null){
    $this->uCprop=$uCprop;
    return $this;
  }

  public function uCSort($itemI,$nextItem){
    if($this->uCprop)
      return $itemI[$this->uCprop] === $nextItem[$this->uCprop]?
        0:(($itemI[$this->uCprop] < $nextItem[$this->uCprop]) ? -1 : 1);
    return $itemI === $nextItem?0:(($itemI < $nextItem) ? -1 : 1);
  }

  /**
  * method to permute elements with no repetitions, and with left fixed strategy
  */
  public function ptPermutations($items=null){
    $this->ptRange                   = 1;
    $this->ptRestartPermutationSerie = true;
    $this->ptPermutePath             = [];
    $this->ptCursor                  = 0;
    $this->ptLeftCursorFix           = false;
    $this->ptPermutedSerie           = false;
    $this->ptTotalElements           = count((array) $items);
    if($this->ptTotalElements === 0)
      return [];
    if($this->ptTotalElements === 1)
      return [$items];

    while($this->ptRange < $this->ptTotalElements){
      if($this->ptRestartPermutationSerie){
        $this->ptPermutePath[] = $this->ptPermutedSerie = $this->ptInitializeCollection();
        $this->ptRestartPermutationSerie = false;
        continue;
      }

      if($this->ptCanDecrese($this->ptPermutedSerie)){
        $this->ptPermutedSerie = $this->ptDecrese($this->ptPermutedSerie);
        if($this->ptLeftCursorFix){
          $this->ptLeftCursorFix = false;
          $this->ptCursor        = $this->ptRange-1;
        }
      }
      else{
        if($this->ptHasLeft()){
          $this->ptCursor--;
          $this->ptPermutedSerie = $this->ptFixRight($this->ptPermutedSerie);
          $this->ptLeftCursorFix = true;
          continue;
        }else{
          return null;
        }
      }

      $this->ptPermutePath[]  = $this->ptPermutedSerie;
      if($this->ptPathIsCompleted($this->ptPermutedSerie)){
        $this->ptCursor = $this->ptRange;
        $this->ptRange++;
        $this->ptRestartPermutationSerie = true;
      }
    }
    $permutedItems = [];
    foreach($this->ptPermutePath as $ptPermutedSerie){
      $permutedItemsSerie= [];
      foreach(array_diff(array_keys($items),$ptPermutedSerie) as $permutedPosition)
        $permutedItemsSerie[] = $items[$permutedPosition];
      $permutedItems[] = $permutedItemsSerie;
    }

    return $permutedItems;
  }

  /**
  * permution complementory method
  */
  protected function ptInitializeCollection(){
    $permutedSerie[0] = $last = $this->ptTotalElements -1;
    $elementIterator      = 0;
    while($last > 0 && $this->ptRange > count($permutedSerie)){
      $last = $permutedSerie[$elementIterator++]-1;
      $permutedSerie[$elementIterator] = $last;
    }
    return array_reverse($permutedSerie);
  }

  /**
  * permution complementory method
  */
  protected function ptHasLeft(){
    return $this->ptCursor > 0;
  }

  /**
  * permution complementory method
  */
  protected function ptHasRight($permutedSerie){
    return $this->ptCursor < count($permutedSerie) -1;
  }

  /**
  * permution complementory method
  */
  protected function ptFixRight ($permutedSerie){
    for($i = $this->ptRange -1; $i>$this->ptCursor; $i--)
      $permutedSerie[$i] = $this->ptTotalElements - $this->ptRange + $i;
    return $permutedSerie;
  }

  /**
  * permution complementory method
  */
  protected function ptCanDecrese($permutedSerie){
    if($this->ptHasLeft($this->ptCursor))
      return $permutedSerie[$this->ptCursor] - 1 > $permutedSerie[$this->ptCursor-1];
    return $permutedSerie[$this->ptCursor] > 0;
  }

  /**
  * permution complementory method
  */
  protected function ptPathIsCompleted($permutedSerie){
    return end($permutedSerie) <= $this->ptRange -1;
  }

  /**
  * permution complementory method
  */
  protected function ptDecrese($permutedSerie){
    $permutedSerie[$this->ptCursor] --;
    return $permutedSerie;
  }

  public function varexport($expression=null, $return=FALSE) {
    $export = var_export($expression, TRUE);
    $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
    $array = preg_split("/\r\n|\n|\r/", $export);
    $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
    $export = join(PHP_EOL, array_filter(["["] + $array));
    if ((bool)$return) return $export; else echo $export;
  }

  public function isAssociativeArray($arrayTest){
    return count(array_filter(array_keys($arrayTest), 'is_string')) > 0;
  }

  public function cvGetSomeKeys ($array,$byKey=null,...$keys){
    $someKeysArray = [];
    foreach ($array as $item){
      $newItem = [];
      foreach ($keys as $key){
        if (isset($item[$key]))
          $newItem[$key]=$item[$key];
      }
      if ($byKey && !empty($item[$byKey]))
        $someKeysArray[$item[$byKey]]=$newItem;
      else
        $someKeysArray[]=$newItem;
    }
    return $someKeysArray;
  }

  public function cvGetSomeKeysAsList ($array,$key='id'){
    return collect($this->cvGetSomeKeys($array,null,$key))->pluck($key)->toArray();
  }

  public function cvRandomSafeToken ($size){
    return bin2hex(openssl_random_pseudo_bytes($size, $cstrong));
  }
}
