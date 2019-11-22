<?php

namespace Crudvel\Traits;

trait CvResourceTrait
{
  //Getters start
  public function getCamelPluralName(){
    return $this->camelPluralName??null;
  }
  public function getCamelSingularName(){
    return $this->camelSingularName??null;
  }
  public function getSlugPluralName(){
    return $this->slugPluralName??null;
  }
  public function getSlugSingularName(){
    return $this->slugSingularName??null;
  }
  public function getSnakePluralName(){
    return $this->snakePluralName??null;
  }
  public function getSnakeSingularName(){
    return $this->snakeSingularName??null;
  }
  public function getStudlyPluralName(){
    return $this->studlyPluralName??null;
  }
  public function getStudlySingularName(){
    return $this->studlySingularName??null;
  }
  //-----------
  public function getControllerClass(){
    return $this->controllerClass??null;
  }
  public function getControllerInstance(){
    return $this->controllerInstance??null;
  }
  public function getModelClass(){
    return $this->modelClass??null;
  }
  public function getModelBuilderInstance(){
    return $this->modelBuilderInstance??null;
  }
  public function getModelCollectionInstance(){
    return $this->modelCollectionInstance??null;
  }
  public function getRequestClass(){
    return $this->requestClass??null;
  }
  public function getRequestInstance(){
    return $this->requestInstance??null;
  }
  public function getUserModelClass(){
    return $this->userModelClass??null;
  }
  public function getUserModelBuilderInstance(){
    return $this->userModelBuilderInstance??null;
  }
  public function getUserModelCollectionInstance(){
    return $this->userModelCollectionInstance??null;
  }
  public function getPaginatorClass(){
    return $this->paginatorClass??null;
  }
  public function getPaginatorInstance(){
    return $this->paginatorInstance??null;
  }
  public function getPaginatorDefiner(){
    return $this->paginatorDefiner??null;
  }
  public function getCvResourceInstance(){
    return $this;
  }
  //-----------
  public function getRows(){
    return $this->rows??null;
  }
  public function getRow(){
    return $this->row??null;
  }
  public function getCurrentAction(){
    return $this->currentAction??null;
  }
  public function getCurrentActionKey(){
    return $this->currentActionKey??null;
  }
  public function getFields(){
    return $this->fields??null;
  }
  public function getPaginateFields(){
    return $this->fields['paginate']??null;
  }
  //Getters end

  //Setters start
  public function setCamelPluralName($camelPluralName=null){
    $this->camelPluralName = $camelPluralName;
    return $this;
  }
  public function setCamelSingularName($camelSingularName=null){
    $this->camelSingularName = $camelSingularName;
    return $this;
  }
  public function setSlugPluralName($slugPluralName=null){
    $this->slugPluralName = $slugPluralName;
    return $this;
  }
  public function setSlugSingularName($slugSingularName=null){
    $this->slugSingularName = $slugSingularName;
    return $this;
  }
  public function setSnakePluralName($snakePluralName=null){
    $this->snakePluralName = $snakePluralName;
    return $this;
  }
  public function setSnakeSingularName($snakeSingularName=null){
    $this->snakeSingularName = $snakeSingularName;
    return $this;
  }
  public function setStudlyPluralName($studlyPluralName=null){
    $this->studlyPluralName = $studlyPluralName;
    return $this;
  }
  public function setStudlySingularName($studlySingularName=null){
    $this->studlySingularName = $studlySingularName;
    return $this;
  }
  //-----------
  public function setControllerClass($controllerClass=null){
    $this->controllerClass = $controllerClass;
    return $this;
  }
  public function setControllerInstance($controllerInstance=null){
    $this->controllerInstance = $controllerInstance;
    return $this;
  }
  public function setModelClass($modelClass=null){
    $this->modelClass = $modelClass;
    return $this;
  }
  public function setModelBuilderInstance($modelBuilderInstance=null){
    $this->modelBuilderInstance = $modelBuilderInstance;
    return $this;
  }
  public function setModelCollectionInstance($modelCollectionInstance=null){
    $this->modelCollectionInstance = $modelCollectionInstance;
    return $this;
  }
  public function setRequestClass($requestClass=null){
    $this->requestClass = $requestClass;
    return $this;
  }
  public function setRequestInstance($requestInstance=null){
    $this->requestInstance = $requestInstance;
    return $this;
  }
  public function setUserModelClass($userModelClass=null){
    $this->userModelClass = $userModelClass??null;
    return $this;
  }
  public function setUserModelBuilderInstance($userModelBuilderInstance=null){
    $this->userModelBuilderInstance = $userModelBuilderInstance??null;
    return $this;
  }
  public function setUserModelCollectionInstance($userModelCollectionInstance=null){
    $this->userModelCollectionInstance = $userModelCollectionInstance??null;
    return $this;
  }
  public function setPaginatorClass($paginatorClass=null){
    $this->paginatorClass = $paginatorClass;
    return $this;
  }
  public function setPaginatorInstance($paginatorInstance=null){
    $this->paginatorInstance = $paginatorInstance;
    return $this;
  }
  public function setPaginatorDefiner($paginatorDefiner=null){
    $this->paginatorDefiner = $paginatorDefiner;
    return $this;
  }
  //-----------
  public function setRows($rows=null){
    $this->rows=$rows;
    return $this;
  }
  public function setRow($row=null){
    $this->row=$row;
    return $this;
  }
  public function setCurrentAction($currentAction=null){
    $this->currentAction=$currentAction;
    return $this;
  }
  public function setCurrentActionKey($currentActionKey=null){
    $this->currentActionKey=$currentActionKey;
    return $this;
  }
  public function setFields($fields=null){
    $this->fields=$fields;
    return $this;
  }
  public function setPaginateFields($paginate=null){
    $this->fields['paginate'] = $paginate ?? null;
    return $this;
  }
  //Setters end
}
