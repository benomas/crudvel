<?php

namespace Crudvel\Interfaces;

interface CvScaffInterface
{
  public function stablishConsoleInstace($consoleInstance=null);
  public function stablishResource($resource=null);
  public function stablishMode($mode=null);
  public function askAditionalParams();
  public function loadTemplate();
  public function fixTemplate();
  public function inyectFixedTemplate();
}
