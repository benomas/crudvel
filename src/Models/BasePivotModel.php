<?php

namespace Crudvel\Models;

use DB;
use \Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

class BasePivotModel extends BaseModel
{
  use AsPivot;
  public $incrementing = false;
  protected $guarded = [];
}
