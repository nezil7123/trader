<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Automation;

class AutomatedController extends Controller
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
        $logs = Automation::where('user_id', Auth::user()->id)->get();
        return view('automated.index', [
            'logs' => $logs
        ]);
       
    }
    public function add(Request $request)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $log = new Log;
        $log->user_id = 1;
        $log->title = $data['title'];
        $log->message = $data['message'];
        $log->type = $data['type'];
        $log->price = $data['price'];
        $log->status = "Pending";
        $log->save();
    }
}
