<?php namespace Crudvel\Database\Factories;

use App\Models\{Owner, User};
use PHPUnit\Framework\Constraint\IsInstanceOf;

class BaseFactory extends \Illuminate\Database\Eloquent\Factories\Factory
{
  use \Crudvel\Traits\CrudTrait;
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      "name"        => $this->faker->unique()->state,
      "description" => $this->faker->sentence(6,true),
      "active"      => $this->faker->boolean(85),
    ];
  }

  public function injectUser($user=null){
    if(!$user)
      return $this;

    if($user instanceof \Illuminate\Database\Eloquent\Builder)
      $user = $user->first();

    if($user instanceof \Illuminate\Database\Eloquent\Collection)
      $user = $user->first();

    if($user instanceof \Illuminate\Database\Eloquent\Model)
      $user = $user->id;

    return $this->state(function (array $attributes) use($user) {
      return [
        'user_id' => $user,
      ];
    });
  }

  public function userBuilder(){
    return \App\Models\User::noFilters();
  }

  public function randomOrCreate($modelBuilder = null){
    if(!class_exists($modelBuilder))
      return null;

    if(!$model = $modelBuilder->inRandomOrder()->first()){
      $model = $modelBuilder->getModel();
      $model = $model::factory()->create();
    }

    return $model;
  }
}
