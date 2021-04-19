<?php

namespace Crudvel\Libraries\Helpers;

class CvHelper{
  protected $debuggExpresion   = null;
  protected $debuggScriptTime  = null;
  protected $debuggScriptStamp = null;
  protected $uCprop            = null;
  protected $controllers       = null;
  protected $seeds             = null;
  protected $actions           = null;
  use DebuggTrait;
  use ObjectTrait;
  use FileTrait;
  use FunctionTrait;
  use TerminalTrait;
  use CasesTrait;
  use ResourceTrait;
  use DbTrait;
  use LangTrait;
  use CacheTrait;
}
