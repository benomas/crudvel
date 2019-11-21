<?php

namespace Crudvel\Interfaces;

interface CvPaginateInterface
{
  public function getFlexPaginable();
  public function getSelectables();
  public function getRequestInstance();
  public function getModel();
  public function getModelInstance();
  public function newModelInstance();
  public function getJoinables();
  public function setBasicPropertys();
}
