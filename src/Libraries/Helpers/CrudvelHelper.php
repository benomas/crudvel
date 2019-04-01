<?php

namespace Crudvel\Libraries\Helpers;

class CrudvelHelper{
  protected $debuggExpresion   = null;
  protected $debuggScriptTime  = null;
  protected $debuggScriptStamp = null;
  use DebuggTrait;
  use ObjectTrait;
  use FileTrait;
}
