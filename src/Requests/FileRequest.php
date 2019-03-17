<?php namespace  Crudvel\Requests;

use Crudvel\Customs\Requests\CrudRequest;
use App\Models\CatFile;

class FileRequest extends \Crudvel\Customs\Requests\CrudRequest{
  /**
   * Add parameters to be validated
   *
   * @return array
   */
  public function all($keys=null)
  {
    $resourceName = $this->route('resource');
    $inputs = !empty($resourceName)?array_replace_recursive(
        parent::all(),
        ['resource'=>$resourceName]
    ):parent::all();

    switch($resourceName){
      case 'module-densities':
      case 'module-mixes':
      case 'module-services':
      case 'module-distributions':
      case 'module-precisions':
      case 'module-abattoirs':
        $resource = "App\Models\\".studly_case(str_singular($resourceName));
        if($resourceUuid = $this->route('resource_uuid'))
          $this->resourceModel = $resource::uuid($resourceUuid)->first();
        $this->catFile = CatFile::resource($resourceName)->first();
        break;
      default: $resource=null;
    }
    return !empty($this->resourceModel) && !empty($this->catFile)?array_replace_recursive(
        $inputs,
        [
          'resource_id' => $this->resourceModel->id,
          'cat_file_id' => $this->catFile->id,
        ]
    ):$inputs;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function defaultRules()
  {
    $this->rules=[
      'resource'    => 'required',
      'cat_file_id' => 'required|integer|key_exist:cat_files,id,active,1',
    ];
    if(!empty($this->fields['resource']))
      $this->rules['resource_id'] = 'required|integer|key_exist:'.str_slug($this->fields['resource'],'_').',id';

    $this->fileName = '';
    $this->catFile = $this->catFile??CatFile::where('cat_files.resource',$this->fields['resource'])->first();
    if(!empty($this->fields['resource']) && $this->catFile){
      $this->fileName .= $this->fields['resource'];
      $this->fixedAttributes                 =  [];
      $this->fixedAttributes[$this->fileName] = $this->catFile->name;
      $this->rules[$this->fileName] = 'required';
    }

    if($this->catFile){
      $this->rules[$this->fileName] .= '|min:'.$this->catFile->min_size.'|max:'.$this->catFile->max_size.'|mimes:'.$this->catFile->types;
      if(!$this->catFile->multiple && $this->fields['resource_id']){
        if($this->modelInstanciator()->catFileId($this->fields["cat_file_id"])->resourceId($this->fields["resource_id"])->count())
          $this->rules[$this->fileName] = 'file_already_exist';
      }
    }
  }

  public function postUpdate(){
    $this->rules[$this->fileName] = 'required';
  }

  public function postStoreUpdate(){
    $this->rules[$this->fileName] = 'required';
  }
}
