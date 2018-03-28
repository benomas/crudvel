<?php
namespace Crudvel\Requests;

use Crudvel\Traits\CrudTrait;
use Crudvel\Exceptions\AuthorizationException;
use Crudvel\Models\Permission;
use Crudvel\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Lang;

class CrudRequest extends FormRequest
{
    protected $crudObjectName;
    protected $rules;
    public $currentAction;
    public $currentActionId;
    public $currentUser;
    public $baseName;
    public $mainTable;
    public $fields;
    protected $unauthorizedException;
    protected $customBaseName;
    protected $langName;
    protected $rowName;

    //import/export
    public $exportImportProperties = [];
    public $importerRowIdentifier = "Identificador";
    public $currentValidator;

    //imports/export
    public $importResults       = [];
    public $importerCursor        = 1;
    use CrudTrait;

    public function resourcesExplode(){
        if(empty($this->mainTable))
            $this->mainTable = str_slug(snake_case(str_plural($this->getCrudObjectName())),"_");
        if(empty($this->langName))
            $this->langName = str_slug(snake_case(str_plural($this->getCrudObjectName())));
        if(empty($this->rowName))
            $this->rowName = camel_case($this->getCrudObjectName());
        if(empty($this->baseName))
            $this->baseName = $this->langName;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!$this->currentAction)
            return true;
        return resourceAccess($this->userModel,$this->baseName."_".$this->currentAction);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->resourcesExplode();
        $this->setCurrentUser();

        $this->currentAction   = $this->route()?explode('@', $this->route()->getActionName())[1]:null;
        $this->currentActionId = $this->route($this->mainArgumentName());
        $this->rules           = [];
        if(!$this->currentAction)
            return $this->rules;
        if(in_array($this->method(),["POST","PUT"])){
            $this->loadFields();
            $this->defaultRules();
        }
        $rulesGenerator = strtolower($this->method()).(ucfirst($this->currentAction));
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
        return $this->wantsJson()?$this->apiUnautorized():$this->webUnauthorized();
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
                "lastAction"   =>$this->currentAction,
                "lastActionId" =>$this->currentActionId,
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
        return !empty($fields = __("crudvel/".$this->langName.".fields"))?$fields:[];
    }

    //Todo
    public function validateImportingRow($row){
        $this->defaultRules();
        $identifier = $this->slugedImporterRowIdentifier();
        $this->currentAction = "store";
        if($row->{$identifier}){
            $this->currentAction   = "update";
            $this->currentActionId = $row->{$identifier};
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
        $fixedLabel = str_slug($label,"_");
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
        $this->importResults[$this->importerCursor]["transactionType"] = trans("crudvel.actions.".$this->currentAction.".call_message");
    }

    public function changeTransactionType($transactionType){
        $this->importResults[$this->importerCursor]["transactionType"] = $transactionType;
    }

    public function changeErrors($errors){
        $this->importResults[$this->importerCursor]["errors"] = $errors;
    }

    public function slugedImporterRowIdentifier(){
        return str_slug($this->importerRowIdentifier,"_");
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
}