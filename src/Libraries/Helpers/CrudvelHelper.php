<?php

namespace Crudvel\Libraries\Helpers;

class CvHelper{
  protected $debuggExpresion   = null;
  protected $debuggScriptTime  = null;
  protected $debuggScriptStamp = null;
  protected $uCprop            = null;
  use DebuggTrait;
  use ObjectTrait;
  use FileTrait;
  use FunctionTrait;
  use TerminalTrait;
}
