<?php namespace  Crudvel\Requests;

class CatFileRequest extends \Customs\Crudvel\Requests\CrudRequest{

  // [Authorization]
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    $this->prepareRequest();

    if(!$this->getUserModelCollectionInstance())
      return true;

    if(!$this->getCurrentAction())
      return true;

    if($this->owner() && in_array($this->getCurrentAction(),['index','show','resourcer']))
      return true;

    return $this->actionAccess();
  }
  // [End Authorization]

  // [Rules]
    public static function externalPostStoreRules($fields=null,$request=null){
      return static::externalize([
        "active"      => "boolean",
        "description" => "",
        "max_size"    => "required|numeric",
        "min_size"    => "required|numeric",
        "multiple"    => "boolean",
        "resource"    => "required",
        "name"        => "required",
        "required"    => "boolean",
        "slug"        => "required|unique:cat_files,slug",
        "types"       => "required",
      ]);
    }

    public static function externalPutUpdateRules($fields=null,$request=null){
      return static::externalize(static::externalPostStoreRules()->fixRules())->setExtraRules([
        'name' => 'required',
        'slug' => 'required|unique:cat_files,slug,@catFileKey@',
      ])->setMethod(__FUNCTION__);
    }

    public function putUpdate(){
      $this->rules = static::externalPutUpdateRules($this->getFields(),$this)->setParam('catFileKey',$this->getCurrentActionKey())->fixRules();
    }
  // [End Rules]
}
