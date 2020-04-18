<?php

namespace Crudvel\Libraries\SpreadSheetIO\Constructors;

use Hamcrest\Type\IsNumeric;
use Maatwebsite\Excel\Facades\Excel;

/**
 * This class is used to build permissions spreadsheet data for (Resource/Action) spreadsheet type
 */
class PermissionResourceAction
extends PermissionBase
implements \Crudvel\Interfaces\SpreadSheetIO\ConstructorInterface
{
  public $postfixFilename = 'resource-actions';
  public $spreadSheetTitle= 'Recursos/Acciones';
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

  public function build(){
    $header [0]= $this->getSpreadSheetTitle();
    $actions = cvActions();
    $specials = $this->getSysSpecials('specials');
    $specials[$this->lastActionName]= $this->lastActionName;
    $openSpecials = [];
    // Set headers: actions and specials actions
    foreach ($actions as $action)
      array_push($header, $action);
    foreach ($specials as $special=> $value){
      array_push($header, $special);
      array_push($openSpecials, $special);
    }
    // Set row to heading in data
    $data[0] = $header;
    // get resources
    $resources = cvResources();
    $countActions = count($data[0]);

    // iter over resources to define data and bgColors
    foreach ($resources as $rIndex => $resource) {
      $rowData = [];
      $rowData []= $resource;
      // for Data:
      for($e=1; $e < $countActions; $e++){
        $rowData[] = '0';
      }
      $data[] = $rowData;

      // for bgColor: check toIgnore bgColor, getting actions by resource
      $resourceActions = array_merge(cvActions($resource), $openSpecials);
      foreach ($header as $aIndex => $action) {
        // Omit first col and last col (Acceso)
        if($aIndex == 0) continue;
          $init = $this->num2alpha($aIndex);
          $init.=$rIndex+2;
          $finish = $init;
        if(array_search($action, $resourceActions) === false && $action != $this->lastActionName){
          $this->bgRanges[] = ['range'=>$init.':'.$finish, 'bgColor'=>$this->bgColors['toIgnore'], 'protection'=>true];
        }else{
          $this->bgRanges[] = ['range'=>$init.':'.$finish, 'bgColor'=>$this->bgColors['toModify'], 'protection'=>false];
        }
      }
    }
    $this->data = $data;
    return $this->data;
  }

  public function getData(){
    return $this->data;
  }

  public function getSpreadSheetTitle(){
    return $this->spreadSheetTitle;
  }

  public function synchronize()
  {
    // get new actions from system
    $newActions = cvActions();
    // add special actions
    $specials = $this->getSysSpecials('specials');
    $specials[$this->lastActionName]= $this->lastActionName;
    $openSpecials =[];
    foreach ($specials as $special=> $value){
      array_push($newActions, $special);
      array_push($openSpecials, $special);
    }
    // get collection from data
    $oldDataCollection = $this->data;
    // counters for row and col
    $nRow = 1;
    $nCol = 1;
    $countActions = count($newActions);
    // syncData to return
    $syncData = [];
    $syncData [] = $newActions;
    array_unshift($syncData[0], $this->getSpreadSheetTitle());
    // counters for row and col
    $nRow = 2;
    $nCol = 0;
    // get new resources from system
    $newResources = cvResources();
    // iter new resources
    foreach($newResources as $resource){
      $data = [];
      $data[0] = $resource;
      $resourceActions = array_merge(cvActions($resource), $openSpecials);
      foreach ($newActions as $action) {
        $finish = '';
        $init = '';
        // does resource name exist in old data collection ?
        if(is_null($oldDataCollection->get($resource))){
          // fill all resource data row with zero
          for($e = 1; $e <= $countActions; $e++){
            $init = 'A'.$nRow;
            $finish = $this->num2alpha($e).$nRow;
            $this->bgRanges[] = ['range'=> $init.':'.$finish, 'bgColor'=>$this->bgColors['toModify'], 'protection'=>false];
            $data[$e] = '0';
          }
          break;
        }else{
          $row = $oldDataCollection->get($resource);
          if(is_null($row) || !isset($row[$action])){
            // Set 0, cuz I dont have old value for this resource/action
            $data[] = '0';
            $init = $this->num2alpha($nCol);
            $init.=$nRow;
            $finish = $init;
            $this->bgRanges[] = ['range'=>$init.':'.$finish, 'bgColor'=>$this->bgColors['toModify'], 'protection'=>false];
          }else{
            // Set the old resource/action value in the new data for spreadsheet
            $data[] = (string)$row[$action];
          }
          $nCol++;
        }
        $init = $this->num2alpha($nCol);
        $init.=$nRow;
        $finish = $init;
        // check if action exist in resource actions to bgs
        if(array_search($action, $resourceActions) === false && $nCol < count($newActions)-1){
          $this->bgRanges[] = ['range'=>$init.':'.$finish, 'bgColor'=>$this->bgColors['toIgnore'], 'protection'=>true];
        }else{
          $finish = $init;$this->bgRanges[] = ['range'=>$init.':'.$finish, 'bgColor'=>$this->bgColors['none'], 'protection'=>false];
        }
      }
      $syncData[] = $data;
      $nRow++;
      $nCol = 0;
    }
    $this->data = $syncData;
  }
}
