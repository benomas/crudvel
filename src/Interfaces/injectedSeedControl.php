<?php namespace Crudvel\Interfaces;

interface injectedSeedControl{
  public function afterCreation(\Illuminate\Database\Eloquent\Collection $createds,\Illuminate\Database\Seeder $sourceSeed):\Illuminate\Database\Seeder;
  public function setControlTag();
  public function getControlTag();
}
