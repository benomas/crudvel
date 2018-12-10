<?php namespace Crudvel\Observers;

class GlobalModelObserver
{
    public $modelName;

    public function __construct(){
    }

    /**
     * Listen to the ActivityUser created event.
     *
     * @param  ActivityUser  $activityUser
     * @return void
     */
    public function created($modelInstance)
    {
        customLog($modelInstance);
    }

    /**
     * Listen to the ActivityUser created event.
     *
     * @param  ActivityUser  $activityUser
     * @return void
     */
    public function updated($modelInstance)
    {
        customLog($modelInstance);
    }

    /**
     * Listen to the ActivityUser deleting event.
     *
     * @param  ActivityUser  $activityUser
     * @return void
     */
    public function deleted($modelInstance)
    {
        customLog($modelInstance);
    }
}