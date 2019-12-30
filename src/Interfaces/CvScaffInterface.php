<?php

namespace Crudvel\Interfaces;

interface CvScaffInterface
{
  public function force();
  public function getForce();
  public function setConsoleInstance($consoleInstance=null);
  public function setResource($resource=null);
  public function setMode($mode=null);
  public function askAditionalParams();
  public function loadTemplate();
  public function fixTemplate();
  public function inyectFixedTemplate();
}
