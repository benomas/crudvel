<?php

namespace Crudvel\Interfaces\SpreadSheetIO;

interface ConstructorInterface
{
  // build all spreadsheets from nothing
  public function build();
  // get Title
  public function getSpreadSheetTitle();
  // build all spreadsheets from existing spreadsheets (update them)
  public function synchronize(bool $syncFromDb = false) :void;
  // get loaded data to build spreadsheet
  public function getData();
}
