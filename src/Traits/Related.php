<?php

namespace Crudvel\Traits;

trait Related
{

  //Relationships
  //End Relationships

  // Scopes
  public function scopeGeneralOwner($query,$userId){
  }

  public function scopeParticularOwner($query,$userId){
    $query->defParticularOwner($userId,'report');
  }
  // End Scopes
}
