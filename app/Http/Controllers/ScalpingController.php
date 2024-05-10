<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\User;
use App\Models\OptionOrder;

class ScalpingController extends Controller
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
        
        return view('optionOrder.scalping');
       
    } 
     
    public function scalpingPlacement(Request $request, $type, $rate)
    {
        $months = array(
            'Jan' => 1,
            'Feb' => 2,
            'Mar' => 3,
            'Apr' => 4,
            'May' => 5,
            'Jun' => 6,
            'Jul' => 7,
            'Aug' => 8,
            'Sep' => 9,
            'Oct' => 'O',
            'Nov' => 'N',
            'Dec' => 'D'
            );
        $user = User::find(2);
        $accessToken =  $user->access_token;
        $appId = $user->app_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fyers.in/data-rest/v2/quotes/?symbols=NSE:NIFTYBANK-INDEX',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: '.$user->api_id.':'.$user->access_token
            ),
          ));
        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response);
        $openPrice = $data->d[0]->v->open_price;
        $openPriceRound = floor($openPrice - ($openPrice % 100));
        $optionChain = [];
        $year = date('y', strtotime('Next Thursday'));
        $month = $months[date('M', strtotime('Next Thursday'))];
        $date = date('d', strtotime('Next Thursday'));
        if($date == date('d', strtotime('Last thursday of this month')))
            $code = $year.strtoupper(date('M', strtotime('Next Thursday')));
        else
            $code = $year.$month.$date;
            
        for($i = -2000; $i <= 2000; $i+= 100){
            $optionChain[] = "NSE:BANKNIFTY".$code.$openPriceRound + $i.$type;
        }
        
        $string = "";
        foreach($optionChain as $row)
        {
            $string.= $row.',';
        }
        $string = rtrim($string,',');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fyers.in/data-rest/v2/quotes/?symbols='.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: '.$user->api_id.':'.$user->access_token
            ),
          ));
        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response);
        
        
        foreach($data->d as $row)
        {
            $row->v->lp."----". $row->v->symbol."<br>";
            if($row->v->lp >320 && $row->v->lp < 460){
                echo $row->v->lp."----". $row->v->symbol."<br>";
                $tradePE = $row; break;
                echo $row->v->lp."----". $row->v->symbol."<br>";
            }
                
        }
        if($tradePE) $this->stockBuy($request, $tradePE, $rate);
       
    }
    
    
    public function stockBuy(Request $request, $stock, $rate)
    {
        $user = Auth::user();
        $bid = floor($stock->v->lp - 3);
        
        $curl = curl_init();
        $qty = 45;
        $symbol = $stock->v->symbol;
        switch($rate)
        {
            case 1: {
                $takeProfit = 5;
                $stopLoss = 8;
                break;
            }
            case 2: {
                $takeProfit = 8;
                $stopLoss = 8;
                break;
            }
            case 3: {
                $takeProfit = 10;
                $stopLoss = 10;
                break;
            }
            case 4: {
                $takeProfit = 12;
                $stopLoss = 12;
                break;
            }
            default: {
                $takeProfit = 12;
                $stopLoss = 8;
                break;
            }
        }
        
        echo "Tp: ";
        echo $takeProfit = floor($takeProfit/.25) * .25;
        echo "<br> Stop Loss :";
        echo $stopLoss = floor($stopLoss/.25) * .25;
        echo "<br>";
        
        $data = array(
            "symbol" => $symbol,
            "qty" => $qty,
            "type" => 1,
            "side" => 1,
            "productType" => 'BO',
            "limitPrice" => $bid,
            "stopPrice" => 0,
            "validity" => "DAY",
            "disclosedQty" => 0,
            "offlineOrder" => "False",
            "stopLoss" => floor($stopLoss) ,
            "takeProfit" => floor($takeProfit)
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
        echo $response = curl_exec($curl);
        $resData = json_decode($response);
        
        // return redirect('/fyers-positions');
        
    }
    
   
    
}
