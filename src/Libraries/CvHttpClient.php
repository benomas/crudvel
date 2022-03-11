<?php namespace Crudvel\Libraries;

use Tests\TestCase;
use \Database\Factories\UserFactory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Http;

class CvHttpClient
{
  protected $bearerToken = null;
  protected $cvApiUrl    = '';

  public function __construct(){
    $this->setCvApiUrl(config('cv.cv_api_url'));
  }
// [Specific Logic]
  /** @test */
  public function callOauthLogin() {
    return $this->setBearerToken($this->bearerToken())->getBearerToken();
  }

  /** @test */
  public function genericStore($apiPath='',$body=[],$headers=[]){
    $headers = array_merge([
        'Accept' => 'application/json, text/plain, */*'
      ],
      $headers
    );

    if($this->getBearerToken())
      $headers['Authorization'] = "Bearer {$this->getBearerToken()}";

    $response = Http::withHeaders($headers)
      ->post("{$this->getCvApiUrl()}/{$apiPath}", $body);

    if($response->status() !== 200)
      throw new \Crudvel\Exceptions\CvHttpClientExceptions\UnableToCreateToken();

    return $response->json();
  }

  /** @test */
  public function genericShow($apiPath='',$body=[],$headers=[]){
    $headers = array_merge([
        'Accept' => 'application/json, text/plain, */*'
      ],
      $headers
    );

    if($this->getBearerToken())
      $headers['Authorization'] = "Bearer {$this->getBearerToken()}";

    $response = Http::withHeaders($headers)
      ->get("{$this->getCvApiUrl()}/{$apiPath}");

    if($response->status() !== 200)
      throw new \Crudvel\Exceptions\CvHttpClientExceptions\UnableToProcessRequest();

    return $response->json();
  }

  public function genericIndex($apiPath='',$body=[],$headers=[]){
    $headers = array_merge([
        'Accept' => 'application/json, text/plain, */*'
      ],
      $headers
    );

    if($this->getBearerToken())
      $headers['Authorization'] = "Bearer {$this->getBearerToken()}";

    $response = Http::withHeaders($headers)
      ->get("{$this->getCvApiUrl()}/{$apiPath}");

    if($response->status() !== 200)
      throw new \Crudvel\Exceptions\CvHttpClientExceptions\UnableToProcessRequest();

    return $response->json();
  }

  /** @test */
  public function genericUpdate($apiPath='',$body=[],$headers=[]){
    $headers = array_merge([
        'Accept' => 'application/json, text/plain, */*'
      ],
      $headers
    );

    if($this->getBearerToken())
      $headers['Authorization'] = "Bearer {$this->getBearerToken()}";

    $response = Http::withHeaders($headers)
      ->put("{$this->getCvApiUrl()}/{$apiPath}", $body);

    if($response->status() !== 200){
      pdd($response->body());
      throw new \Crudvel\Exceptions\CvHttpClientExceptions\UnableToProcessRequest();
    }

    return $response->json();
  }

  /** @test */
  public function genericDelete($apiPath='',$body=[],$headers=[]){
    $headers = array_merge([
        'Accept' => 'application/json, text/plain, */*'
      ],
      $headers
    );

    if($this->getBearerToken())
      $headers['Authorization'] = "Bearer {$this->getBearerToken()}";

    $response = Http::withHeaders($headers)
      ->delete("{$this->getCvApiUrl()}/{$apiPath}");

    if($response->status() !== 200)
      throw new \Crudvel\Exceptions\CvHttpClientExceptions\UnableToProcessRequest();

    return $response->json();
  }

  protected function oauthLogin() {
    $oauthClientId     = config('cv.api_client');
    $oauthClientSecret = config('cv.api_secret');
    $username          = config("packages.benomas.crudvel.crudvel.default_user.crudvel_default_user_email");
    $password          = config("packages.benomas.crudvel.crudvel.default_user.crudvel_default_user_passsword");

    $body = [
      'username'      => $username,
      'password'      => $password,
      'client_id'     => $oauthClientId,
      'client_secret' => $oauthClientSecret,
      'grant_type'    => 'password',
      'scope'         => '*'
    ];

    $response = Http::withHeaders(['Accept' => 'application/json'])
      ->post("{$this->getCvApiUrl()}/oauth/token", $body);

    if($response->status() !== 200)
      throw new \Crudvel\Exceptions\CvHttpClientExceptions\UnableToCreateToken();

    return $response->json();
  }

  protected function bearerToken($oauthRequestResponse = null) {
    if(!$oauthRequestResponse)
      $oauthRequestResponse = $this->oauthLogin();

    if($oauthRequestResponse)
      return $oauthRequestResponse['access_token'] ?? null;

    return null;
  }

  private function oauthLoginToken() {
    return $this->bearerToken($this->oauthLogin());
  }

// [End Specific Logic]

// [Getters]
  protected function getBearerToken(){
    return $this->bearerToken??null;
  }

  public function getCvApiUrl(){
      return $this->cvApiUrl??null;
  }
// [End Getters]
// [Setters]
  protected function setBearerToken($bearerToken=null){
    $this->bearerToken = $bearerToken??null;

    return $this;
  }

  public function setCvApiUrl($cvApiUrl=null){
    $this->cvApiUrl = $cvApiUrl??null;

    return $this;
  }
// [End Setters]
}
