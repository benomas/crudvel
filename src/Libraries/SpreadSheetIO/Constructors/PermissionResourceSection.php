<?php

namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

/**
 * This class is used to build permissions spreadsheet data for (Resource/Sections) spreadsheet type
 */
class PermissionResourceSection
extends PermissionBase
implements \Crudvel\Interfaces\SpreadSheetIO\ConstructorInterface
{
  public $postfixFilename = 'section-permissions';
  public $spreadSheetTitle= 'Secciones/Permiso';
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
    // get sections from
    $sections = $this->getSysLangArrayByKeyName('sections');
    foreach ($sections as $section) {
      $a[] = [$section, '0'];
    }
    $count = count($sections)+1;
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
    // get new sections from system
    $newSections = $this->getSysLangArrayByKeyName('sections');
    // get collection for old data
    $oldDataCollection = $this->data;
    $nRow = 2;
    foreach ($newSections as $section) {
      $data = [];
      $data[0] = $section;
      // does section exists in old data collection ?
      if(is_null($oldDataCollection->get($section))){
        $data[1] = '0';
        $init = 'B'.$nRow;
        $finish = $init;
        $this->bgRanges[] = ['range'=> $init.':'.$finish, 'bgColor'=>$this->bgColors['toModify'], 'protection'=>false];
      }else{
        $row = $oldDataCollection->get($section);
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

