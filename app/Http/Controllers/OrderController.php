<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Log;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Log for alrets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $codeLog = Order::select('code')->selectRaw("SUM(status) as count")->selectRaw("SUM(profit) as profit")->groupBy('code')->get();
        $symbolLog = Order::select('symbol')->selectRaw("SUM(status) as count")->selectRaw("SUM(profit) as profit")->groupBy('symbol')->get();
        $logs = Order::where('user_id', Auth::user()->id)->orderBy('id','DESC')->get();
        
        $weekArray = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $weekData = [];
        foreach($weekArray as $week) {
            $weekData[$week] = Order::select('code')->selectRaw("SUM(status) as count")->selectRaw("SUM(profit) as profit")->where('day', $week)->groupBy('code')->get();
        }
        
        return view('order.index', [
            'logs' => $logs,
            'codeLog' => $codeLog,
            'symbolLog' => $symbolLog,
            'weekData' => $weekData
        ]);
       
    }
    public function orderPlacement(Request $request)
    {
        
        Log::info($request);
        $user = User::find(2);
        $curl = curl_init();
        if($request['status'] == 4 && $request['message'] == 'New Ack' && $request['clientId'] == 'XM32873')
            {
                Log::info($request['symbol']);
                 $data = array(
                "symbol" => $request['symbol'],
                "qty" => 1,
                "type" => $request['type'],
                "side" => $request['side'],
                "productType" => $request['productType'],
                "limitPrice" => number_format($request['limitPrice'], 2),
                "stopPrice" => number_format($request['stopPrice'], 2),
                "validity" => "DAY",
                "disclosedQty" => 0,
                "offlineOrder" => "False"
            );
             curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fyers.in/api/v2/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$user->api_id.':'.$user->access_token,
                'Content-Type: application/json'
            ),
            ));
    
            $response = curl_exec($curl);
    
            curl_close($curl);
        }
    }
}
