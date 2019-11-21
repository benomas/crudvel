<?php namespace Crudvel\Requests;

use Crudvel\Exceptions\AuthorizationException;
use Crudvel\Interfaces\CvCrudInterface;
use Crudvel\Models\Permission;
use Crudvel\Models\Role;
use Crudvel\Traits\CrudTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Lang;

class CrudRequest extends FormRequest implements CvCrudInterface{
  protected $slugSingularName;
  protected $cvResource;

  protected $rules;
  public $currentAction;
  public $currentActionId;
  public $fields;
  protected $unauthorizedException;
  protected $fixedAttributes = null;
  protected $currentDepth='';
  protected $currentDinamicResource='';

  //import/export
  public $exportImportProperties = [];
  public $importerRowIdentifier  = "Identificador";
  public $currentValidator;

  //imports/export
  public $importResults          = [];
  public $importerCursor         = 1;
  use CrudTrait;
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    $this->prepareRequest();
    if(!$this->getCurrentAction())
      return true;
    if($this->owner() && in_array($this->getCurrentAction(),['index','show']))
      return true;

    return actionAccess($this->userModel,$this->getSlugPluralName().".".Str::slug(snake_case($this->getCurrentAction())));
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $this->rules           = [];
    if(!$this->getCurrentAction())
      return $this->rules;
    if(in_array($this->method(),["POST","PUT"])){
      $this->defaultRules();
    }

    $rulesGenerator = strtolower($this->method()).(ucfirst($this->getCurrentAction()));
    if(method_exists($this,$rulesGenerator))
      $this->{$rulesGenerator}();
    return $this->rules;
  }

  public function validatorInstance(){
    return $this->getValidatorInstance();
  }

  public function revalidator($data,$method=null){
    if($method)
      $this->{$method}();

    $rules     = $this->rules;
    $validator = $this->getValidatorInstance();
    $validator->setData($data);
    $validator->setRules($rules);
    $validator->validate();
  }

  public function unautorizedRedirect(){
    if($this->wantsJson()){
      if($this->getUserModelCollectionInstance() && $this->getUserModelCollectionInstance()->active)
        return $this->apiUnautorized();
      return $this->apiUnautenticated();
    }
    return $this->webUnauthorized();
  }

  public function failedAuthorization(){
    $unauthorizedException = new AuthorizationException('This action is unauthorized.');
    $unauthorizedException->redirect   = $this->unautorizedRedirect();
    $unauthorizedException->dontFlash  = $this->dontFlash;
    throw $unauthorizedException;
  }

  /**
   * Handle a failed validation attempt.
   *
   * @param  \Illuminate\Validation\Validator  $validator
   * @return mixed
   */
  protected function failedValidation(Validator $validator)
  {
      if(!$this->wantsJson()){
        $this->merge([
          "lastAction"   =>$this->getCurrentAction(),
          "lastActionId" =>$this->getCurrentActionKey(),
        ]);
        Session::flash("error", trans("crudvel.web.validation_errors"));
      }

      if(property_exists($this,"formatErrors")){
        throw new HttpResponseException($this->response(
            $this->formatErrors($validator)
        ));
      }else{
        throw (new ValidationException($validator))
          ->errorBag($this->errorBag)
          ->redirectTo($this->getRedirectUrl());
      }
  }

  public function attributes()
  {
    return array_merge(
      __("crudvel/".$this->getSlugPluralName().".fields")??[],
      $this->fixedAttributes??[]);
  }

  public function simpleAttributeTranslator($field){
    $this->fixedAttributes[$this->currentDepth.$field]  = __("crudvel/".$this->currentDinamicResource.".fields.$field");
  }

  public function simpleAttributesTranslator($fields){
    foreach($fields as $field)
      $this->simpleAttributeTranslator($field);
  }

  public function fixDepth($rules){
    $fixedRules = [];
    foreach($rules as $rulesIndex => $rulesValue){
      $fixedRules[$this->currentDepth.$rulesIndex]=$rulesValue;
      $this->simpleAttributeTranslator($rulesIndex);
    }
    return $fixedRules;
  }

  //Todo
  public function validateImportingRow($row){
    $this->defaultRules();
    $identifier = $this->slugedImporterRowIdentifier();
    $this->setCurrentAction('store');
    if($row->{$identifier}){
      $this->setCurrentAction('update');
      $this->setCurrentActionKey($row->{$identifier});
      if(method_exists($this,"putUpdate"))
        $this->putUpdate();
    }
    else{
      if(method_exists($this,"postStore"))
        $this->postStore();
    }

    $this->currentValidator= \Illuminate\Support\Facades\Validator::make(
      $this->fields,
      $this->rules,
      [],
      $this->attributes()
    );
    return !$this->currentValidator->fails();
  }

  public function exportPropertyFixer($property,$row){
    return !empty($row->{$property})?$row->{$property}:null;
  }

  public function importPropertyFixer($label,$row){
    $fixedLabel = Str::slug($label,"_");
    return (!empty($row->{$fixedLabel}))?$row->{$fixedLabel}:null;
  }

  public function inicializeImporter($properties){
    $this->langsToImport($properties);
    $this->importerCursor=1;
    $this->importResults=[];
  }

  public function firstImporterCall($row){
    $this->importerCursor++;
    $this->changeImporter();
  }

  public function changeImporter($status="success",$errors=null){
    if(empty($this->importResults[$this->importerCursor]))
      $this->importResults[$this->importerCursor]=[];
    $this->importResults[$this->importerCursor]["status"]          = $status;
    $this->importResults[$this->importerCursor]["errors"]          = $errors;
    $this->importResults[$this->importerCursor]["transactionType"] = trans("crudvel.actions.".snake_case($this->getCurrentAction()).".call_message");
  }

  public function changeTransactionType($transactionType){
    $this->importResults[$this->importerCursor]["transactionType"] = $transactionType;
  }

  public function changeErrors($errors){
    $this->importResults[$this->importerCursor]["errors"] = $errors;
  }

  public function slugedImporterRowIdentifier(){
    return Str::slug($this->importerRowIdentifier,"_");
  }

  public function langsToImport($properties){
    $key = trans("crudvel/".$this->langName.".fields.id");
    $this->exportImportProperties[$key] = "id";
    foreach ($properties as $property) {
      $key = trans("crudvel/".$this->langName.".fields.".$property);
      $this->exportImportProperties[$key] = $property;
    }
  }

  public function putActivate(){
    $this->rules=[];
  }

  public function putDeactivate(){
    $this->rules=[];
  }

  public function prepareRequest(){
    $this->injectCvResource();
    $this->cvResource->captureRequestHack($this)->assignUser();
    $this->setCurrentAction($this->route()?explode('@', $this->route()->getActionName())[1]:null);
    $this->setCurrentActionKey($this->route($this->getSnakeSingularName()));
    $this->loadFields();
  }

  public function setCurrentDepth($currentDepth=''){
    $this->currentDepth=$currentDepth;
    return $this;
  }
  public function setCurrentDinamicResource($currentDinamicResource=''){
    $this->currentDinamicResource=$currentDinamicResource;
    return $this;
  }

  public function getCurrentDepth(){
    return $this->currentDepth;
  }
  public function getCurrentDinamicResource(){
    return $this->currentDinamicResource;
  }

  public function getSlugSingularName(){
    return $this->slugSingularName??Str::snake(str_replace('Request','',class_basename($this)),'-');
  }
}
