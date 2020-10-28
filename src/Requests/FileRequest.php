<?php namespace  Crudvel\Requests;

use Customs\Crudvel\Requests\CrudRequest;
use App\Models\CatFile;

class FileRequest extends \Customs\Crudvel\Requests\CrudRequest{

  //TODO homologate external rules methods
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function defaultRules()
  {
    $fields = $this->getFields();

    $this->rules=[
      'resource'    => 'required',
      'cat_file_id' => 'required|key_exist:cat_files,id,active,1',
    ];

    if(!empty($fields['resource']))
      $this->rules['resource_id'] = 'required|key_exist:'.\Str::slug($fields['resource'],'_').',id';

    $this->fileName = '';
    $this->catFile = CatFile::id($fields['cat_file_id']??null)->CvSearch()->first();

    if(!empty($fields['resource']) && $this->catFile){
      $this->fileName                         .= $fields['resource'];
      $this->fixedAttributes                  = [];
      $this->fixedAttributes[$this->fileName] = ' '.$this->catFile->name;
      $this->rules          [$this->fileName] = 'required';
    }

    if($this->catFile){
      $this->rules[$this->fileName] .= '|min:'.$this->catFile->min_size.'|max:'.$this->catFile->max_size.'|mimes:'.$this->catFile->types;
      if(!$this->catFile->multiple && $fields['resource_id']){
        if($this->modelInstanciator()->catFileId($fields["cat_file_id"])->resourceId($fields["resource_id"])->count())
          $this->rules[$this->fileName] = 'file_already_exist';
      }
/*
      if(!$this->actionAccess($this->catFile->resource.".update"))
        $this->rules['cat_file_id'].='|file_resource';*/
    }
  }

  public function postUpdate(){
    $this->rules[$this->fileName] = 'required';
  }

  public function postStoreUpdate(){
    $this->rules[$this->fileName] = 'required';
  }

  public function deleteDestroy(){
    if(!$this->actionAccess($this->getModelCollectionInstance()->catFile.".update"))
      $this->rules['cat_file_id'].='file_resource';
  }
}
