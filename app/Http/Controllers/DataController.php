<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;

class DataController extends Controller
{
    /**
     * Log for alrets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
     
     
    public function listStocks(Request $request)
    {
        $stocks = Stock::all();
        return view('data.listStocks', [
            'data' => $stocks
        ]);
        
    }
    public function watchlist(Request $request)
    {
        $accessToken =  Auth::user()->access_token;
        $appId =  Auth::user()->app_id;

        $curl = curl_init();
        $stocks = Stock::where('status',1)->get();
        $strings = [];
        $strings[0]='';
        $i=0;
        $j=0;
        $stocksAr = [];
        $stocksAr[0] = [];
        foreach($stocks as $row)
        {
            $strings[$j].='NSE:'.$row->symbol.'-EQ,';
            $stocksAr[$j][]=$row->symbol;
            $i++;
            if($i > 49){
                $strings[$j] = rtrim($strings[$j],',');
                $j++;
                $strings[$j]='';
                $stocksAr[$j]=[];
                $i=0;
            }
        }
        $strings[$j] = rtrim($strings[$j],',');
        
        
        $fullData = [];
        
        // foreach($stocksAr[34] as $row)
        // {
        //     $string='NSE:'.$row.'-EQ';
        //     $i++;
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => 'https://api.fyers.in/data-rest/v2/quotes/?symbols='.$string,
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'GET',
        //         CURLOPT_HTTPHEADER => array(
        //           'Authorization: '.Auth::user()->api_id.':'.Auth::user()->access_token
        //         ),
        //       ));
        //     $response = curl_exec($curl);
    
        //     curl_close($curl);
        //     echo $string;
        //     echo $response."<br>";
        // }
        
        //     die();
        $i = 0;
        foreach($strings as $string)
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-t1.fyers.in/data/quotes?symbols='.$string,
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
            if(isset($data->d))
                $fullData = array_merge($fullData, $data->d);
            else echo $i;
            $i++;
        }
        return view('data.watchlist', [
            'data' => $fullData
        ]);
       
    }
    
    public function stockBuy(Request $request, $stock, $bid)
    {
        $user = Auth::user();
        
        $curl = curl_init();
        $qty = floor((1500 * 5)/ $bid);
        $takeProfit = number_format((($bid * .60)/100),2);
        $stopLoss = number_format((($bid * .45)/100),2);
        
        $takeProfit = floor($takeProfit/.25) * .25;
        $stopLoss = floor($stopLoss/.25) * .25;
        
        
        $data = array(
            "symbol" => $stock,
            "qty" => $qty,
            "type" => 2,
            "side" => 1,
            "productType" => 'BO',
            "limitPrice" => 0,
            "stopPrice" => 0,
            "validity" => "DAY",
            "disclosedQty" => 0,
            "offlineOrder" => "False",
            "stopLoss" => $stopLoss < .50 ? .50 : $stopLoss,
            "takeProfit" => $takeProfit ? $takeProfit : .50
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
    
    public function stockSell(Request $request, $stock, $ask)
    {
        $user = Auth::user();
        
        $curl = curl_init();
        $qty = floor((1500 * 5)/ $ask);
        $takeProfit = number_format((($ask * .40)/100),2);
        $stopLoss = number_format((($ask * .25)/100),2);
        
        $takeProfit = floor($takeProfit/.25) * .25;
        $stopLoss = floor($stopLoss/.25) * .25;
        
        $data = array(
            "symbol" => $stock,
            "qty" => $qty,
            "type" => 2,
            "side" => -1,
            "productType" => 'BO',
            "limitPrice" => 0,
            "stopPrice" => 0,
            "validity" => "DAY",
            "disclosedQty" => 0,
            "offlineOrder" => "False",
            "stopLoss" => $stopLoss < .50 ? .50 : $stopLoss,
            "takeProfit" => $takeProfit ? $takeProfit : .50
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
        // return redirect('/fyers-positions');
        
    }
    public function updateStatus(Request $request, $id)
    {
        $stock = Stock::find($id);
        $stock->status = $stock->status ? 0 : 1;
        $stock->save();
        echo $stock->status;
        return;
    }
    
    public function getTopMovingStocks()
    {
        $accessToken =  Auth::user()->access_token;
        $appId =  Auth::user()->app_id;

        $curl = curl_init();
        $stocks = Stock::where('status',1)->get();
        $strings = [];
        $strings[0]='';
        $i=0;
        $j=0;
        $stocksAr = [];
        $stocksAr[0] = [];
        foreach($stocks as $row)
        {
            $strings[$j].='NSE:'.$row->symbol.'-EQ,';
            $stocksAr[$j][]=$row->symbol;
            $i++;
            if($i > 49){
                $strings[$j] = rtrim($strings[$j],',');
                $j++;
                $strings[$j]='';
                $stocksAr[$j]=[];
                $i=0;
            }
        }
        $strings[$j] = rtrim($strings[$j],',');
        
        
        $fullData = [];
        foreach($strings as $string)
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-t1.fyers.in/data/quotes?symbols='.$string,
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
            $fullData = array_merge($fullData, $data->d);
        }
        $topMovers = [];
        foreach($fullData as $row)
        {
            if($row->v->chp > 1.5 && $row->v->lp > 50 && $row->v->high_price > $row->v->open_price && $row->v->spread < '0.51' && $row->v->volume > 30000)
            {
                $topMovers[]=$row;
            }
            
        }
        
        return view('data.watchlist', [
            'data' => $topMovers
        ]);
        
        
    }
    
}
