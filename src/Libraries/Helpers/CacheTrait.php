<?php
namespace Crudvel\Libraries\Helpers;

trait CacheTrait{
  public function cvSafeCacheGet($property,$callBack,$rememberTime,$sleepTime){
    $semaphone = "{$property}-semaphore";

    if (!\Illuminate\Support\Facades\Cache::has($property)) {
      $continueFlag = false;
      $lockCout     = 0;
      do{
        if(!\Illuminate\Support\Facades\Cache::has($semaphone)){
          \Illuminate\Support\Facades\Cache::put($semaphone, true);

          if(!\Illuminate\Support\Facades\Cache::has($property)){
            $value = \Illuminate\Support\Facades\Cache::remember($property, $rememberTime, function () use($callBack){
              return $callBack();
            });
            \Illuminate\Support\Facades\Cache::forget($semaphone);
            $continueFlag = true;
          }else{
            \Illuminate\Support\Facades\Cache::forget($semaphone);
            $continueFlag = true;
          }
        }else{
          $nano = time_nanosleep(0, $sleepTime);
          $lockCout++;
        }

        if($lockCout > 1000){
          cvConsoler("\n Dead lock detected".cvRedTC(" $lockCout intentos for "));
        }
      }while(!$continueFlag);
    }

    try{
      $value = \Illuminate\Support\Facades\Cache::get($property);
    }catch(\Exception $e ){
      cvConsoler("\n fallo al intentar obtener la propiedad {$property} ");
      throw new \Crudvel\Exceptions\NoCacheProperty();
    }

    return $value;
  }
}
