<?php namespace Crudvel\Traits;

trait RelationateOwnerByTrait {
  public static function relationateOwnerBy(\Crudvel\Controllers\ApiController $controller,string $ownedResource) {
    $modelCollectionInstance = $controller->getModelCollectionInstance() ?? null;

    $ownerResource = $controller->getSnakePluralName() ?? '';

    if(!$modelCollectionInstance || !$ownerResource || empty($ownedResource))
      throw new \Crudvel\Exceptions\MissConfiguration();

    $ownedByToDetachAttach = $controller->getFields()["{$ownedResource}_owned_by_{$ownerResource}_detach_attach"] ?? [];
    $ownedByToDetach       = cvGetSomeKeysAsList($ownedByToDetachAttach['detach']??[]);
    $ownedByToAttach       = cvGetSomeKeysAsList($ownedByToDetachAttach['attach']??[]);
    $onwnedModel           = 'App\Models\\'.cvCaseFixer('singular|studly',$ownedResource);
    $onwnedRelation        = 'cvOwned'.cvCaseFixer('plural|studly',$ownedResource);

    if(!$controller->getUserModelCollectionInstance()->isRoot()){
      $ownedByToDetach = $onwnedModel::keys($ownedByToDetach)->get()->pluck('id')->toArray();
      $ownedByToAttach = $onwnedModel::keys($ownedByToAttach)->get()->pluck('id')->toArray();
    }

    //------- Proccess new owned
    $newRelatedOwned  = collect($ownedByToDetachAttach['attach']??[])->filter(function($row) use($ownedByToAttach){
      return in_array($row['id'],$ownedByToAttach);
    })->sortBy('related_order');

    $relatedOwned = $modelCollectionInstance
      ->{$onwnedRelation}()
      ->ownedBy($ownerResource,$modelCollectionInstance->id)
      ->orderBy('related_order');


    if($relatedOwned && $relatedOwned->count()){
      $relatedOwned = $relatedOwned->get();

      if($newRelatedOwned->count()){
        $relatedOwned = $relatedOwned->where('related_order','>=',$newRelatedOwned->first()['related_order']??1);
      }
      else{
        $relatedOwned = collect([]);
      }

      $relatedOwned = $relatedOwned->filter(function($row) use($newRelatedOwned,$ownedByToDetach){
        return $newRelatedOwned->where('id',$row->id)->count() === 0 && !in_array($row->id,$ownedByToDetach);
      });

      $newRelatedOwned = $newRelatedOwned
        ->merge($relatedOwned->filter(function($row) use($newRelatedOwned){
          return $newRelatedOwned->where('id',$row->id)->count() === 0;
        })->toArray())
      ->sortBy('related_order');
    }

    return static::loadRelationator()
      ->setModelCollectionInstance($modelCollectionInstance)
      ->setRelatedResource(cvCaseFixer('plural|snake',$ownedResource))
      ->setRelatedResourceRelation($modelCollectionInstance
      ->{$onwnedRelation}())
      ->setFields($data ?? [])
      ->setToDetach($ownedByToDetach)
      ->setToAttach(cvGetSomeKeysAsList($newRelatedOwned->toArray()??[]))
      ->build()
      ->cvRelationateResource();
  }

  public static function syncRelationateOwnerBy($data,$model,string $ownedResource) {
    return static::loadRelationator()
      ->setModelCollectionInstance($model)
      ->setRelatedResource(cvCaseFixer('plural|snake',$ownedResource))
      ->setFields($data ?? [])
      ->build()
      ->cvSyncRelationateResource();
  }
}
