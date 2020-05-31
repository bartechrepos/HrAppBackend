<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as Oclient;

class UserCtrl extends Controller
{
    //
    public $successStatus = 200;

    public function login(Request $request)
    {

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

            $oClient = Oclient::where('password_client', 1)->first();

            return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client(['timeout' => 3.0]);
        $response = null ;
        try {
            // the built-in PHP webserver which is single-threaded.
            // so you need to run another thread to serve
            // https://stackoverflow.com/questions/50400150/server-freezing-when-using-passport-password-grants-and-or-guzzle/54223483#54223483

            $response = $http->request('POST', 'http://localhost:8001/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ],
                'connect_timeout' => 5
            ]);

        } catch (RequestException $th) {
            //throw $th;
            die($th);
            abort(404, $th);
        }

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }
}
