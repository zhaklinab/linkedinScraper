<?php

namespace App\Http\Controllers;

//use League\OAuth2\Client\Provider\GenericProvider;
use App\AccessToken;
use GuzzleHttp\Client;
use Happyr\LinkedIn\Exception\LinkedInException;
use Happyr\LinkedIn\LinkedIn;
use Happyr\LinkedIn\Storage\IlluminateSessionStorage;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LinkedinScraperController extends Controller
{
    protected $appKey;
    protected $appSecret;
    protected $state;

    public function __construct()
    {
        $this->appKey = config('linkedin.api_key');
        $this->appSecret = config('linkedin.api_secret');
    }

    public function index(Request $request)
    {
        $state = uniqid();
        $request->session()->put('state', $state);

        $redirectUrl = urlencode('http://127.0.0.1:8000/oauth/login');
        $url = "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=".$this->appKey."&redirect_uri=".$redirectUrl."&state=".$state."&scope=r_basicprofile";
        $request->session()->save();

        return view('welcome', compact('url'));
    }

    public function post(Request $request)
    {
        $state = uniqid();
        $request->session()->put('state', $state);

        $redirectUrl = urlencode('http://127.0.0.1:8000/oauth/login');
        $url = "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=".$this->appKey."&redirect_uri=".$redirectUrl."&state=".$state."&scope=r_basicprofile";
        $request->session()->save();
        return $url;
    }

   public function get(Request $request)
    {
        $client = new Client();
        $code = $_GET['code'];
        $state = $_GET['state'];
        $redirectUrl = 'http://127.0.0.1:8000/oauth/login';
        $stateSession = $request->session()->get('state');
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
                'access_token'  => $response['access_token'],
                'expires_in'    => $response['expires_in'],
                'state'         => $state,
            ]);
            $client = new Client();
            $headers = [
                'Authorization' => 'Bearer ' . $response['access_token']
            ];
            $client->request('GET', 'https://api.linkedin.com/v2/people/(id:059b11137)?projection=(id,firstName,lastName,industryId~)', [
                'headers' => $headers,
            ]);
            return $client->getBody();
        } else {
            return "Jo me jo";
        }

    }
    public function getME(){
        $state = session('state');
//        dd($state);
        $accessToken = AccessToken::where('state',$state)->select('access_token')->first();
        try {
            $client = new Client();
            $headers = [
                'Authorization' => 'Bearer ' . $accessToken
            ];
            $client->request('GET', 'https://api.linkedin.com/v2/me', [
                'headers' => $headers,
            ]);
            return $client->getBody();
        } catch (LinkedInException $e){
            return $e->getMessage();
        }

    }
}