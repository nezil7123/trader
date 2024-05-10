<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class FyresController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function loginSuccess(Request $request)
    {
        $code = $request->input('auth_code');
        $appIdHash =  Auth::user()->appid_hash;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fyers.in/api/v2/validate-authcode',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"grant_type":"authorization_code","appIdHash":"'.$appIdHash.'","code":"'.$code.'"}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response);
        Auth::user()->access_token = $data->access_token;
        Auth::user()->refresh_token = $data->refresh_token;
        Auth::user()->save();
        return redirect('/dashboard');
    }

    public function summary(Request $request)
    {
        $accessToken =  Auth::user()->access_token;
        $appId =  Auth::user()->app_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fyers.in/api/v2/funds',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: '.Auth::user()->api_id.':'.Auth::user()->access_token
            ),
          ));
        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response);

        return view('fyers.summary', ['data' => $data]);
    }

    public function positions(Request $request)
    {
        $accessToken =  Auth::user()->access_token;
        $appId =  Auth::user()->app_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fyers.in/api/v2/positions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: '.Auth::user()->api_id.':'.Auth::user()->access_token
            ),
          ));
        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response);

        return view('fyers.positions', ['data' => $data]);
    }
    
    public function dashboard(Request $request)
    {
        // positions
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fyers.in/api/v2/positions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: '.Auth::user()->api_id.':'.Auth::user()->access_token
            ),
          ));
        $response = curl_exec($curl);

        curl_close($curl);
        $positions = json_decode($response);
        
         $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fyers.in/api/v2/orders',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.Auth::user()->api_id.':'.Auth::user()->access_token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $data = json_decode($response);
        if($data->s == 'error')
            $orders = "logout";
        else
            $orders = $data->orderBook;
        
        return view('dashboard', ['orders' => $orders, 'positions' => $positions]);
        
    }


}
