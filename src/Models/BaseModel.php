<?php 
namespace Crudvel\Models;

use Illuminate\Database\Eloquent\Model;
use Crudvel\Traits\CrudTrait;

class BaseModel extends Model {
    use CrudTrait;
    protected $schema;
    protected $hasPropertyActive;
    
    public function __construct($attributes = array())  {
        parent::__construct($attributes);
        $this->hasPropertyActive=true;
    }

// Scopes

    public function scopeInStatus($query, $status){
        if(is_array($status))
            $query->whereIn($this->getTable().".status",$status);
        else
            $query->whereIn($this->getTable().".status",[$status]);
    }

    public function scopeNotInStatus($query, $status){
        if(is_array($status))
            $query->whereNotIn($this->getTable().".status",$status);
        else
            $query->whereNotIn($this->getTable().".status",[$status]);
    }

    public function scopeStatus($query, $status){
        $query->where($this->getTable().".status",$status);
    }

    public function scopeActives($query){
        if($query->getModel()->hasPropertyActive)
            $query->where($this->getTable().".active",1);
    }

    public function scopeNoFilters($query){
        $query->whereNotNull($this->getTable().".id");
    }

    public function scopeNullFilter($query){
        $query->whereNull($this->getTable().".id");
    }

    public function scopeId($query,$id){
        $query->where($this->getTable().".id",$id);
    }

    public function scopeIds($query,$ids){
        $query->whereIn($this->getTable().".id",$ids);
    }

    public function scopeNoIds($query,$ids){
        $query->whereNotIn($this->getTable().".id",$ids);
    }

    public function scopeUnActives($query){
        $query->where($this->getTable().".status",0);
    }

    public function scopeName($query,$name){
        $query->where($this->getTable().".name", $name);
    }

    public function scopeSlug($query,$name){
        $query->where($this->getTable().".slug", $name);
    }

    public function scopeOfLevel($query,$level_id){
        $query->where($this->getTable().".level_id", $level_id);
    }

    public function scopeOfParent($query,$parent_id){
        $query->where($this->getTable().".parent_id", $parent_id);
    }

    public function scopeOfSublevel($query,$sublevel_id){
        $query->where($this->getTable().".sublevel_id", $sublevel_id);
    }

// End Scopes

// Others

    public function getTable(){
        return ($this->schema?$this->schema:"").parent::getTable();
    }

    public function manyToManyToMany($firstLevelRelation,$secondLevelRelation,$secondLevelModel){
        if(!is_callable(array($secondLevelModel,"nullFilter")))
            return null;

        if(!method_exists($this,$firstLevelRelation))
            return null;

        $firstLevelRelationInstace = $this->{$firstLevelRelation};

        if(!$firstLevelRelationInstace)
            return $secondLevelModel::nullFilter();

        $secondLevelRelationArray=[];
        foreach ($firstLevelRelationInstace as $firstLevelRelationItem)
            if(method_exists($firstLevelRelationItem,$secondLevelRelation) && $firstLevelRelationItem->{$secondLevelRelation}()->count())
                $secondLevelRelationArray += $firstLevelRelationItem->{$secondLevelRelation}()->get()->pluck("id")->toArray();

        return $secondLevelModel::ids($secondLevelRelationArray);
    }

    public function shadow(){
        $clonedInstacse = new \Illuminate\Database\Eloquent\Builder(clone $this->getQuery());
        $clonedInstace->setModel($this->getModel());
        return $clonedInstace;
    }

// Others
}
