<?php
namespace Crudvel\Requests;

use Crudvel\Traits\CrudTrait;
use Crudvel\Exceptions\AuthorizationException;
use Crudvel\Models\Permission;
use Crudvel\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Exception\HttpResponseException;
use Lang;

class CrudRequest extends FormRequest
{
    protected $rules;
    public $currentAction;
    public $currentActionId;
    public $currentUser;
    public $baseName;
    public $fields;
    protected $unauthorizedException;
    protected $customBaseName;
    protected $langName;
    use CrudTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return resourceAccess($this->currentUser,$this->baseName."_".$this->currentAction);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setCurrentUser();
        $this->baseName = $this->customBaseName?
            $this->customBaseName:
            basename($this->path());
        if(empty($this->langName))
            $this->langName=$this->baseName;

        $this->currentAction   = explode('@', $this->route()->getActionName())[1];
        $this->currentActionId = $this->route($this->mainArgumentName());
        $this->rules           = [];
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
    protected function failedValidation(\Illuminate\Validation\Validator $validator)
    {
        if(!$this->wantsJson())
            Session::flash("error", trans("crudvel.web.validation_errors"));

        throw new HttpResponseException($this->response(
            $this->formatErrors($validator)
        ));
    }

    public function attributes()
    {
        return !empty($fields = Lang::get("crudvel/".$this->langName.".fields"))?$fields:[];
    }

}