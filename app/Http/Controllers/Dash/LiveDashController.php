<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LiveMonitorInfo;
use App\Models\Queue;
use Debugbar;



class LiveDashController extends Controller {

    public function livedash(Request $request){

        $rep_info = Array(); $queue_opt = $request->input('queue_opt') ?? "" ;
        $exten_spy =  $_GET['exten_spy'] ?? "";
        $queues = Queue::where('estatus','A')->pluck('queue','id');
        $queues[""] = "All";
        $rep_info["heading"] ="Testing";
        Debugbar::info($queues);
        return view('dash.example',compact('queue_opt','queues','rep_info','exten_spy'));


        /*



        // Conexión a la base de datos CallCenter
        $reports =  new LiveMonitorInfo($pDB,$myDB);

        $reports->queue_opt = &$queue_opt;
        $rep_info = array();
        $reports->totales = &$rep_info ;
        $reports->exten_spy = &$exten_spy;
        $reports->bringInfo();

        $queues_tag = $reports->get_queues_tag();

        $css_waiting = "info_val";
        if($reports->totales["waiting_calls"] > 0 and $reports->totales["waiting_calls"] < 3)
                    $css_waiting = "info_waiting_yellow";
        else if($reports->totales["waiting_calls"] > 2 )
                    $css_waiting = "info_waiting_red";

        $rep_info["css_waiting"] = $css_waiting;
        if($request->ajax()){
               return json_encode($rep_info);

        }
       return view('dash.livedash',compact("rep_info","queues_tag","queue_opt","exten_spy")); */

}
//
}
