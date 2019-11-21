<?php

namespace Crudvel\Interfaces;

interface CvPaginateInterface
{
  public function getFlexPaginable();
  public function getSelectables();
  public function getRequestInstance();
  public function getModelBuilderInstance();
  public function getModelInstance();
  public function getPaginable();
  //public function setBasicPropertys();
}
