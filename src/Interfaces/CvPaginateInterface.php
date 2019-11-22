<?php

namespace Crudvel\Interfaces;

interface CvPaginateInterface
{
  public function getFlexPaginable();
  public function getPaginable();
  public function getFilterables();
  public function getOrderables();
  public function getSelectables();
  public function getBadPaginablePetition();
  public function setBadPaginablePetition();
  //public function setBasicPropertys();
}
