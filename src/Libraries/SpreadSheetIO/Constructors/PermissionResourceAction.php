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
  public $bgRanges = [];

  public function __construct($filename, $format = '.xlsx'){
    $this->format = $format;
    $this->filename = $filename.'-'.$this->postfixFilename.$format;
  }

  public function build(){
    $header [0]= $this->getSpreadSheetTitle();
    // $actions = $this->getSysLangArrayByKeyName('actions');
    // $specials = $this->getSysLangArrayByKeyName('specials');
    $actions = cvActions();
    $specials = $this->getSysSpecials('specials');
    $specials['(Acceso)']= '(Acceso)';
    // Set headers: actions and specials actions
    foreach ($actions as $action)
      array_push($header, $action);
    foreach ($specials as $special=> $value)
      array_push($header, $special);
    // Set row to heading in data
    $data[0] = $header;
    // get resources
    $resources = cvResources();
    $countActions = count($data[0]);
    $countResources= count($resources)+1;
    foreach ($resources as $resource) {
      $rowData = [];
      $rowData []= $resource;
      for($e=1; $e < $countActions; $e++){
        $rowData[] = '0';
      }
      $data[] = $rowData;
    }
    $this->data = $data;
    $finish = $this->num2alpha($countActions-1).$countResources;
    $this->bgRanges = ['A1'.':'.$finish];
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
    // get new resources from system
    $newResources = cvResources();
    // get new actions from system
    $newActions = cvActions();
    // pdd(cvActions(), cvActions('users'));
    // add special actions
    $specials = $this->getSysSpecials('specials');
    $specials['(Acceso)']= '(Acceso)';
    foreach ($specials as $special=> $value)
      array_push($newActions, $special);

    // get collection from data
    $c = $this->data;
    $bgRanges = [];
    // counters for row and col
    $nRow = 1;
    $nCol = 1;
    $countResources = count($newResources)+1;
    $countActions = count($newActions);
    // syncData to return
    $syncData = [];
    $syncData [] = $newActions;
    array_unshift($syncData[0], $this->getSpreadSheetTitle());
    // get first row (Action headers)
    $oldActions = $c->first();
    foreach ($newActions as $action) {
      $index = array_search($action, $oldActions);
      if($index === false){
        $init = $this->num2alpha($nCol)."1";
        $finish = $this->num2alpha($nCol).$countResources;
        $bgRanges[] = $init.':'.$finish;
      }
      $nCol++;
    }
    // counters for row and col
    $nRow = 2;
    $nCol = 0;
    // iter new resources
    foreach($newResources as $resource){
      $data = [];
      $data[0] = $resource;
      foreach ($newActions as $action) {
        $finish = '';
        $init = '';
        // does resource name exist in old data collection ?
        if(is_null($c->get($resource))){
          $init = 'A'.$nRow;
          $finish = $this->num2alpha($countActions).$nRow;
          // mark as new cell to identificate with background color
          $bgRanges[] = $init.':'.$finish;
          // fill all resource data row with zero
          for($e = 1; $e <= $countActions; $e++){
            $data[$e] = '0';
          }
          break;
        }else{
          $row = $c->get($resource);
          if(is_null($row) || !isset($row[$action])){
            // Set 0, cuz I dont have old value for this resource/action
            $data[] = '0';
          }else{
            // Set the old resource/action value in the new data for spreadsheet
            $data[] = (string)$row[$action];
          }
        }
        $nCol++;
      }
      $syncData[] = $data;
      $nRow++;
      $nCol = 0;
    }
    $this->data = $syncData;
    $this->bgRanges= $bgRanges;
  }
}
