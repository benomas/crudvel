<?php namespace  Crudvel\Requests;

class CatFileRequest extends \Crudvel\Customs\Requests\CrudRequest{

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function defaultRules()
  {
    $this->rules=[
      "active"      => "boolean",
      "description" => "required|min:10",
      "max_size"    => "required|numeric",
      "min_size"    => "required|numeric",
      "multiple"    => "boolean",
      "resource"    => "required",
      "name"        => "required|unique:".$this->mainTable.",name",
      "required"    => "boolean",
      "slug"        => "required|unique:".$this->mainTable.",slug",
      "types"       => "required",
    ];
  }

  public function putUpdate(){
    $this->rules["name"] .=",".$this->currentActionId;
    $this->rules["slug"] .=",".$this->currentActionId;
  }
}
