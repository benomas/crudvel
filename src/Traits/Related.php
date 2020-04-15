<?php

namespace Crudvel\Traits;

trait Related
{

  //Relationships
  //End Relationships

  // Scopes
  public function scopeGeneralOwner($query,$userId=null){
  }

  public function scopeParticularOwner($query,$userId=null){
    $query->defParticularOwner($userId,'report');
  }
  // End Scopes
}
