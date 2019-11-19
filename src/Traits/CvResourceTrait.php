<?php

namespace Crudvel\Traits;

trait CvResourceTrait
{
  //Getters start
  public function getControllerClass(){
    return $this->controllerClass;
  }
  public function getControllerInstance(){
    return $this->controllerInstance;
  }
  public function getModelClass(){
    return $this->modelClass;
  }
  public function getModelBuilderInstance(){
    return $this->modelBuilderInstance;
  }
  public function getModelCollectionInstance(){
    return $this->modelCollectionInstance;
  }
  public function getRequestClass(){
    return $this->requestClass;
  }
  public function getRequestInstance(){
    return $this->requestInstance;
  }
  public function getCamelPluralName(){
    return $this->camelPluralName;
  }
  public function getCamelSingularName(){
    return $this->camelSingularName;
  }
  public function getSlugPluralName(){
    return $this->slugPluralName;
  }
  public function getSlugSingularName(){
    return $this->slugSingularName;
  }
  public function getSnakePluralName(){
    return $this->snakePluralName;
  }
  public function getSnakeSingularName(){
    return $this->snakeSingularName;
  }
  public function getStudlyPluralName(){
    return $this->studlyPluralName;
  }
  public function getStudlySingularName(){
    return $this->studlySingularName;
  }
  //Getters end

  //Setters start
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
  //Setters end
}
