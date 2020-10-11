<?php

namespace Crudvel\Libraries\CvSecurity\Interfaces;

use Crudvel\Models\BaseModel;

interface RegisterInvokerInterface{
  public function fixRegisterUserData():RegisterInvokerInterface;
  public function persist();
  public function apiFailResponse();
  public function apiSuccessResponse();
  public function getModelCollectionInstance();
  public function getFields();
}
