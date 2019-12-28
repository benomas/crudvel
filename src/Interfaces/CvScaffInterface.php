<?php

namespace Crudvel\Interfaces;

interface CvScaffInterface
{
  public function stablishResource();
  public function stablishMode();
  public function loadTemplate();
  public function fixTemplate();
  public function inyectFixedTemplate();
}
