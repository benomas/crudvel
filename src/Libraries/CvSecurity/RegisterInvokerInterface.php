<?php

namespace Crudvel\Libraries\CvSecurity;
use Crudvel\Models\BaseModel;

interface RegisterInvokerInterface{
  public function fixUserData():RegisterInvokerInterface;
  public function persist();
  public function apiFailResponse();
  public function apiSuccessResponse();
  public function getModelCollectionInstance():BaseModel;
}
