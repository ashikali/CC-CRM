<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PaloDB;
use App\LiveMonitorInfo;
use App\Models\Queue;
use Debugbar;
use DB;



class LiveDashController extends Controller {

    public function livedash(Request $request){

        $rep_info = Array(); $queue_opt = $request->input('queue_opt') ?? "" ;
        $exten_spy =  $_GET['exten_spy'] ?? "";
        $queues = Queue::select(DB::raw("CONCAT(queue,' ',description) as name"),'id')
                               ->where('estatus','A')->pluck('name','id');
        $queues[""] = "All";
	$dsn = env('DB_DSN');
        $reports =  new LiveMonitorInfo($dsn);

        $reports->queue_opt = &$queue_opt;
        $rep_info = array();
        $reports->totales = &$rep_info ;
        $reports->exten_spy = &$exten_spy;
        $reports->bringInfo();

        debugbar::log($reports->totales);

        $rep_info["heading"] = $queues[$queue_opt];
        $rep_info["missed_call"] = 5;

        $css_waiting = "info_val";
        if($reports->totales["waiting_calls"] > 0 and $reports->totales["waiting_calls"] < 3)
                    $css_waiting = "info_waiting_yellow";
        else if($reports->totales["waiting_calls"] > 2 )
                    $css_waiting = "info_waiting_red";

        $rep_info["css_waiting"] = $css_waiting;
        if($request->ajax()){
               return json_encode($rep_info);

        }
       return view('dash.livedash',compact("rep_info","queues","queue_opt","exten_spy"));

    }
//
}
