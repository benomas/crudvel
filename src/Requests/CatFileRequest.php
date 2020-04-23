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

    if(!$this->getCurrentAction())
      return true;

    if($this->owner() && in_array($this->getCurrentAction(),['index','show','resourcer']))
      return true;

    return $this->actionAccess();
  }
  // [End Authorization]

  // [Rules]
    public static function externalPostStoreRules(){
      return (get_called_class())::externalize([
        "active"      => "boolean",
        "description" => "required|min:10",
        "max_size"    => "required|numeric",
        "min_size"    => "required|numeric",
        "multiple"    => "boolean",
        "resource"    => "required",
        "name"        => "required|unique:cat_files,name",
        "required"    => "boolean",
        "slug"        => "required|unique:cat_files,slug",
        "types"       => "required",
      ]);
    }

    public static function externalPutUpdateRules(){
      return (get_called_class())::externalize((get_called_class())::externalPostStoreRules()->fixRules())->setExtraRules([
        'name' => 'required|unique:cat_files,name,@catFileKey@',
        'slug' => 'required|unique:cat_files,slug,@catFileKey@',
      ])->setMethod(__FUNCTION__);
    }

    public function putUpdate(){
      $this->rules = (get_called_class())::externalPutUpdateRules()->setParam('catFileKey',$this->getCurrentActionKey())->fixRules();
    }
  // [End Rules]
}
