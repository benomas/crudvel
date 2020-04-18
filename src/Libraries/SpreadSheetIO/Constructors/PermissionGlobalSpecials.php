<?php

namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

/**
 * This class is used to build permissions spreadsheet data for (Resource/Sections) spreadsheet type
 */
class PermissionGlobalSpecials
extends PermissionBase
implements \Crudvel\Interfaces\SpreadSheetIO\ConstructorInterface
{
  public $postfixFilename = 'global-specials';
  public $spreadSheetTitle= 'GlobalEspecial/Permiso';
  public $data = [];
  public $bgColors = ['toModify' =>'b3b3cc', 'toIgnore' => '000000', 'none' => null];
  public $bgRanges = [];
  public $lastActionName = '(Acceso)';
  public $role = '';

  public function __construct($filename, $format = '.xlsx'){
    $this->format = $format;
    $this->role = $filename;
    $this->filename = $filename.'-'.$this->postfixFilename.$format;
  }

  public function build()
  {
    $a = [];
    // Set headers
    $a[0] = [$this->spreadSheetTitle,$this->lastActionName];
    // get globalspecials from
    $globalspecials = $this->getSysLangArrayByKeyName('globalSpecials');
    foreach ($globalspecials as $globalspecial) {
      $a[] = [$globalspecial, '0'];
    }
    $count = count($globalspecials)+1;
    $finish = 'B'.$count;
    $this->bgRanges[] = ['range'=>'B2:'.$finish, 'bgColor'=>$this->bgColors['toModify'], 'protection'=>false];
    return $a;
  }

  public function synchronize()
  {
    // syncData to return
    $syncData = [];
    // set headers
    $syncData []= [$this->spreadSheetTitle, $this->lastActionName];
    // get new globalSpecials from system
    $newGlobal = $this->getSysLangArrayByKeyName('globalSpecials');
    // get collection for old data
    $oldDataCollection = $this->data;
    $nRow = 2;
    foreach ($newGlobal as $global) {
      $data = [];
      $data[0] = $global;
      // does globalSpecial exists in old data collection ?
      if(is_null($oldDataCollection->get($global))){
        $data[1] = '0';
        $init = 'B'.$nRow;
        $finish = $init;
        $this->bgRanges[] = ['range'=> $init.':'.$finish, 'bgColor'=>$this->bgColors['toModify'], 'protection'=>false];
      }else{
        $row = $oldDataCollection->get($global);
        $init = 'B';
        $init.=$nRow;
        $finish = $init;
        if(is_null($row) || !isset($row[$this->lastActionName])){
          // Set 0, cuz I dont have old value for this
          $data[0] = '0';
          $this->bgRanges[] = ['range'=>$init.':'.$finish, 'bgColor'=>$this->bgColors['toModify'], 'protection'=>false];
        }else{
          // I have old value
          $data[1] = (string)$row[$this->lastActionName];
          $this->bgRanges[] = ['range'=>$init.':'.$finish, 'bgColor'=>$this->bgColors['none'], 'protection'=>false];
        }
      }
      $syncData[] = $data;
      $nRow++;
    }
    $this->data = $syncData;
  }

  public function getSpreadSheetTitle(){
    return $this->spreadSheetTitle;
  }

  public function getData(){
    return $this->data;
  }

}

