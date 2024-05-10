<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\User;
use App\Models\OptionOrder;

class OptionController extends Controller
{
    /**
     * Log for alrets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request, $day = 'TODAY')
    {
        
        if($day == 'ALL')
            $logs = OptionOrder::orderBy('id','DESC')->get();
        elseif($day == 'MONTH')
            $logs = OptionOrder::whereMonth('created_at', date('m'))->orderBy('id','DESC')->get();
        else 
            $logs = OptionOrder::whereDate('created_at', date('Y-m-d'))->orderBy('id','DESC')->get();
        return view('optionOrder.index', [
            'logs' => $logs
        ]);
       
    } 
     
    public function autoPEPlacement(Request $request)
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
            CURLOPT_URL => 'https://api-t1.fyers.in/data/quotes?symbols=NSE:NIFTYBANK-INDEX',
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
        $year = date('y', strtotime('Next Wednesday'));
        $month = $months[date('M', strtotime('Next Wednesday'))];
        $date = date('d', strtotime('Next Wednesday'));
        if($date == date('d', strtotime('Last wednesday of this month')))
            $code = $year.strtoupper(date('M', strtotime('Next Thursday')));
        else
            $code = $year.$month.$date;
            
        for($i = -2000; $i <= 2000; $i+= 100){
            $optionChain[] = "NSE:BANKNIFTY".$code.$openPriceRound + $i."PE";
        }
        


        $string = "";
        foreach($optionChain as $row)
        {
            echo $string.= $row.',';
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
            echo $row->v->lp."----". $row->v->symbol."<br>";
            if($row->v->lp >370 && $row->v->lp < 420){
                $tradePE = $row; break;
            }
                
        }
        if($tradePE) $this->stockBuy($request, $tradePE);
       
    }
    
    public function autoCEPlacement(Request $request)
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
            CURLOPT_URL => 'https://api-t1.fyers.in/data/quotes?symbols=NSE:NIFTYBANK-INDEX',
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
        $year = date('y', strtotime('Next Wednesday'));
        $month = $months[date('M', strtotime('Next Wednesday'))];
        $date = date('d', strtotime('Next Wednesday'));
         $date = date('d', strtotime('Next Wednesday'));
        if($date == date('d', strtotime('Last wednesday of this month')))
            $code = $year.strtoupper(date('M', strtotime('Next Thursday')));
        else
            $code = $year.$month.$date;
            
        for($i = -2000; $i <= 2000; $i+= 100){
            $optionChain[] = "NSE:BANKNIFTY".$code.$openPriceRound + $i."CE";
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
            echo $row->v->lp."----". $row->v->symbol."<br>";
            if($row->v->lp >370 && $row->v->lp < 420){
                $tradePE = $row; break;
            }
                
          
        }
        
        if($tradePE) $this->stockBuy($request, $tradePE);
       
    }
    
    public function stockBuy(Request $request, $stock)
    {
        $user = User::find(2);
        $bid = floor($stock->v->lp + (($stock->v->lp * 12) / 100));
        
        $curl = curl_init();
        $qty = 15;
        $symbol = $stock->v->symbol;
        $takeProfit = number_format((($bid * 18)/100),2);
        $stopLoss = number_format((($bid * 18)/100),2);
        
        $takeProfit = floor($takeProfit/.25) * .25;
        $stopLoss = floor($stopLoss/.25) * .25;
        $bid = floor($bid/ 5) * 5;
        
        $order = new OptionOrder;
        $order->symbol = $symbol;
        $order->price = $bid;
        $order->user_id = $user->id;
        $order->ltp = $stock->v->lp;
        $order->stop_loss = $stopLoss;
        $order->take_profit = $takeProfit;
        $order->save();
        
        //Telegram Bot
        echo $payload = file_get_contents('https://api.telegram.org/bot6148169225:AAEJrD_o-jyH_zcP49R1Aug7A8iYCFfV3is/sendMessage?chat_id=-945699134&text=Buy:'.$symbol.' at '.$bid.' Symbol :'.substr($symbol,18,7));
        echo $payload = file_get_contents('https://api.telegram.org/bot6148169225:AAEJrD_o-jyH_zcP49R1Aug7A8iYCFfV3is/sendMessage?chat_id=-945699134&text=LTP: '.$stock->v->lp.' Target: '.$bid + $takeProfit.' Stoploss: '.$bid - $stopLoss);
        $data = array(
            "symbol" => $symbol,
            "qty" => $qty,
            "type" => 4,
            "side" => 1,
            "productType" => 'BO',
            "limitPrice" => $bid,
            "stopPrice" => $bid - 1,
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
        print_r($data);
        echo $response = curl_exec($curl);
        $resData = json_decode($response);
        
        // return redirect('/fyers-positions');
        
    }
    
    public function stockBuy2(Request $request, $stock)
    {
        $user = User::find(2);
        $bid = floor($stock->v->lp + (($stock->v->lp * 12) / 100));
        
        $curl = curl_init();
        $qty = 15;
        $symbol = $stock->v->symbol;
        $takeProfit = number_format((($bid * 18)/100),2);
        $stopLoss = number_format((($bid * 18)/100),2);
        
        $takeProfit = floor($takeProfit/.25) * .25;
        $stopLoss = floor($stopLoss/.25) * .25;
        $bid = floor($bid/ 5) * 5;
        
        $order = new OptionOrder;
        $order->symbol = $symbol;
        $order->price = $bid;
        $order->user_id = $user->id;
        $order->ltp = $stock->v->lp;
        $order->stop_loss = $stopLoss;
        $order->take_profit = $takeProfit;
        $order->save();
        
        //Telegram Bot
        echo $payload = file_get_contents('https://api.telegram.org/bot6148169225:AAEJrD_o-jyH_zcP49R1Aug7A8iYCFfV3is/sendMessage?chat_id=-945699134&text=Buy:'.$symbol.' at '.$bid.' Symbol :'.substr($symbol,18,7));
        echo $payload = file_get_contents('https://api.telegram.org/bot6148169225:AAEJrD_o-jyH_zcP49R1Aug7A8iYCFfV3is/sendMessage?chat_id=-945699134&text=LTP: '.$stock->v->lp.' Target: '.$bid + $takeProfit.' Stoploss: '.$bid - $stopLoss);
        $data = array(
            "symbol" => $symbol,
            "qty" => $qty,
            "type" => 4,
            "side" => 1,
            "productType" => 'BO',
            "limitPrice" => $bid,
            "stopPrice" => $bid - 1,
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
        print_r($data);
        echo $response = curl_exec($curl);
        $resData = json_decode($response);
        
        // return redirect('/fyers-positions');
        
    }
    
   
    
}
