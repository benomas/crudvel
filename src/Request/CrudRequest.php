<?php
namespace Crudvel\Requests;

use Crudvel\Traits\CrudTrait;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
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
        return $this->expectsJson()?$this->apiUnautorized():$this->webUnauthorized();
    }

    public function failedAuthorization(){
        $unauthorizedException = new \Illuminate\Auth\Access\AuthorizationException('This action is unauthorized.');
        $unauthorizedException->redirect   = $this->unautorizedRedirect();
        $unauthorizedException->dontFlash  = $this->dontFlash;  
        throw $unauthorizedException;
    }
}