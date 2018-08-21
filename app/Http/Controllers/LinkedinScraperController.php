<?php

namespace App\Http\Controllers;

//use League\OAuth2\Client\Provider\GenericProvider;
use App\AccessToken;
use GuzzleHttp\Client;
use Happyr\LinkedIn\LinkedIn;
use Happyr\LinkedIn\Storage\IlluminateSessionStorage;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

session_start();
class LinkedinScraperController extends Controller
{
    protected $appKey;
    protected $appSecret;
    protected $state;

    public function __construct()
    {
        $this->appKey = config('linkedin.api_key');
        $this->appSecret = config('linkedin.api_secret');
        session()->regenerate();
    }

    public function post(Request $request)
    {
        $state = uniqid();
        Session::put('state', $state);

        $redirectUrl = urlencode('http://127.0.0.1:8000/api/oauth/login');
        $url = "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=".$this->appKey."&redirect_uri=".$redirectUrl."&state=".$state."&scope=r_basicprofile";
        Session::save();
        return $url;
    }

   public function get()
    {
        $client = new Client();
        $code = $_GET['code'];
        $state = $_GET['state'];
        $redirectUrl = 'http://127.0.0.1:8000/api/oauth/login';
        $stateSession = Session::all();
        dd($stateSession );
        if($state == $stateSession){
            $response = $client->request('POST', 'https://www.linkedin.com/oauth/v2/accessToken', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $redirectUrl,
                    'client_id' => $this->appKey,
                    'client_secret' =>$this->appSecret
                ]
            ]);

            $response = json_decode($response->getBody(),true);

            AccessToken::create([
                'access_token'  => $response->access_token,
                'expires_in'    => $response->expires,
                'state'         => $state,
            ]);
            return "Done";
        } else {
            return "Jo me jo";
        }





    }
}
