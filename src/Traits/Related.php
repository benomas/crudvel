<?php

namespace Crudvel\Traits;

trait Related
{

  //Relationships
  //End Relationships

  // Scopes
  public function scopeGeneralOwner($query,$user=null){
  }

  public function scopeParticularOwner($query,$user=null){
    $query->defParticularOwner($user->id,'report');
  }
  // End Scopes
}
