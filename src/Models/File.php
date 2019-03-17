<?php namespace Crudvel\Models;

use Crudvel\Customs\Models\BaseModel;

class File extends \Crudvel\Customs\Models\BaseModel{
  use \App\Traits\ToVReport;

  protected $fillable = [
    "active",
    "cat_file_id",
    "path",
    "disk",
    "resource_id",
  ];

//Relationships

  public function catFile(){
    return $this->belongsTo("\App\Models\CatFile");
  }

  public function resource(){
    customLog($this->catFile()->first()->resource);
    return $this->belongsTo(
      "\App\Models\\".studly_case(str_singular($this->catFile()->first()->resource)),
      'resource_id',
      'id'
    );
  }

//End Relationships

//Scopes

  public function scopeCatFileId($query,$catFileId){
    $query->where($this->getTable().'.cat_file_id',$catFileId);
  }

  public function scopeCatFileSlug($query,$catFileSlug){
    $query->whereHas('catFile',function($query) use($catFileSlug) {
      $query->where('cat_files.slug',$catFileSlug);
    });
  }

  public function scopeResourceId($query,$resourceId){
    $query->where($this->getTable().'.resource_id',$resourceId);
  }

  public function scopeParticularOwner($query,$userId){
    $query->whereHas('request',function($query) use($userId) {
      $query->particularOwner($userId);
    });
  }

// End Scopes

//Scopes



// End Scopes

//Others



//End Others
}
