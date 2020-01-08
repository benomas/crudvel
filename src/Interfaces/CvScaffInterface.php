<?php

namespace Crudvel\Interfaces;

interface CvScaffInterface
{
  public function setConsoleInstance($consoleInstance=null);
  public function getConsoleInstance();
  public function setResource($resource=null);
  public function setScaffParams($scaffParams=null);
  public function stablishResource($resource=null);
  public function force();
  public function getForce();
  public function scaff();

  //public function askAditionalParams();
  //public function loadTemplate();
  //public function fixTemplate();
  //public function inyectFixedTemplate();
}
