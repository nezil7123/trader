<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Automation;
use App\Models\Order;
use App\Models\User;

class LogController extends Controller
{
    /**
     * Log for alrets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $stock = 'ALL')
    {
        $types = Log::select('title')->distinct('title')->get();
        if($stock == 'ALL')
            $logs = Log::orderBy('id','DESC')->get();
        else 
            $logs = Log::where('title',urldecode($stock))->orderBy('id','DESC')->get();
        return view('logs.index', [
            'logs' => $logs,
            'types' => $types
        ]);
       
    }
    public function add(Request $request)
    {
        $data1 = json_decode(file_get_contents('php://input'), true);
        $log = new Log;
        $log->user_id = 1;
        $log->title = $data1['title'];
        $log->message = $data1['message'];
        $log->type = $data1['type'];
        $log->price = $data1['price'];
        $log->status = "Pending";
        $log->save();

        $automation = Automation::where('code', $data1['code'])->first();
        
        if(!$automation) return;
        if(!$automation->status) return;
        $user = User::find($automation->user_id);
        $side = $data1['type'] == 'buy' ? 1 : -1;
        
        $order = Order::where([['dt',date('Y-m-d')],['code',$data1['code']]])->first();
        if($order) return;

        $curl = curl_init();

        $data = array(
            "symbol" => $automation->symbol,
            "qty" => $automation->qty,
            "type" => $automation->type,
            "side" => $side,
            "productType" => $automation->product_type,
            "limitPrice" => 0,
            "stopPrice" => 0,
            "validity" => "DAY",
            "disclosedQty" => 0,
            "offlineOrder" => "False",
            "stopLoss" => $automation->stop_loss,
            "takeProfit" => $automation->take_profit
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
        $resData = json_decode($response);

        curl_close($curl);
        
        $order = new Order;
        @$order->order_id = $data->id;
        $order->symbol = $automation->symbol;
        $order->side = $side;
        $order->user_id = $automation->user_id;
        $order->code = $data1['code'];
        $order->dt = date('Y-m-d');
        $order->day = date('D');
        $order->status = 1;
        $order->save();
        
    }
    
    public function test(Request $request)
    {
       
        $automation = Automation::find(1);
        $user = User::find($automation->user_id);
        $side = 1;

        $curl = curl_init();

        $data = array(
            "symbol" => $automation->symbol,
            "qty" => $automation->qty,
            "type" => $automation->type,
            "side" => $side,
            "productType" => $automation->product_type,
            "limitPrice" => 0,
            "stopPrice" => 0,
            "validity" => "DAY",
            "disclosedQty" => 0,
            "offlineOrder" => "False",
            "stopLoss" => $automation->stop_loss,
            "takeProfit" => $automation->take_profit
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
        echo json_encode($data);
        $data = json_decode($response);

        curl_close($curl);
        echo $response;

        
    }
    
    public function logReports(Request $request)
    {
        $types = Log::select('title')->distinct('title')->get();
        $logs = [];
        foreach($types as $row){
            $logs[$row->title] = Log::where('title',$row->title)->orderBy('id','DESC')->get();
        }
        return view('logs.logReports', [
            'logs' => $logs,
            'types' => $types
        ]);
       
    }
}
