<?php

namespace Crudvel\Libraries\DbEngine;

interface EngineInterface
{
  public function setFilterQueryString();

  public function getFilterQueryString();
}
