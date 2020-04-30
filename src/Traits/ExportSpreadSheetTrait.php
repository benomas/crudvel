<?php

namespace Crudvel\Traits;

trait ExportSpreadSheetTrait
{
  public function exportBeforePaginate(){
    $paginate = $this->getFields()['paginate'] ?? [];
    $paginate['limit'] = null;
    // $this->addSelectables('cat_vehicle_type_id');
    // $paginate['selectQuery'][]='cat_vehicle_type_id';
    $this->addField('paginate', $paginate);
  }

  public function export(){
    $this->processPaginatedResponse();
    $query = $this->getModelBuilderInstance();
    $dictionary = __("crudvel/{$this->getSlugPluralName()}.fields");
    $traduction = [];
    foreach ($query->first()->getAttributes() as $key => $value){
      $traduction[$key] = $dictionary[$key] ?? $key;
    }
    //TODO: Add to headers this traductions
    pdd($traduction, $query->get()->toArray());
  }

  public function processPaginatedResponse(){
    $this->getModelBuilderInstance()->solveSearches();
    $this->getPaginated();
    $this->getPaginatorInstance()->processPaginatedResponse();
    $this->getPaginatorInstance()->loadBasicPropertys();
    $this->getModelBuilderInstance()->select($this->getPaginatorInstance()->getSelectQuery());
  }
}
