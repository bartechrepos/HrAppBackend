<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Twilio\Rest\Client as TwilloClient;

class PanaProxyCtrl extends Controller
{
    //
    public function login(Request $request)
    {
        $http = new Client(['timeout' => 3.0]);
        $response = null ;
        try {
            $response = $http->request('POST', 'http://13.90.214.197:881/api/ManageAccount/Login', [
                'form_params' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ],
                'connect_timeout' => 5
            ]);

        } catch (RequestException $th) {
            //throw $th;
            die($th);
            abort(404, $th);
        }

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, 200);
    }

    public function SendSMS(Request  $request)
    {
        $account_sid = 'ACa111e434f70ec0010d5019a67c9c1908';
        $auth_token = $request->auth;
        $twilio_number = "+18442324681";
        $client = new TwilloClient($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $request->to,
            array(
                'from' => $twilio_number,
                'body' => $request->body
            )
        );
    }
}
