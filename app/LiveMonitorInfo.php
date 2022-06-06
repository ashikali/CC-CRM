<?php
namespace App;
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Elastix version 0.5                                                  |
  | http://www.elastix.org                                               |
  +----------------------------------------------------------------------+
  | Copyright (c) 2006 Palosanto Solutions S. A.                         |
  +----------------------------------------------------------------------+
  | Cdla. Nueva Kennedy Calle E 222 y 9na. Este                          |
  | Telfs. 2283-268, 2294-440, 2284-356                                  |
  | Guayaquil - Ecuador                                                  |
  | http://www.palosanto.com                                             |
  +----------------------------------------------------------------------+
  | The contents of this file are subject to the General Public License  |
  | (GPL) Version 2 (the "License"); you may not use this file except in |
  | compliance with the License. You may obtain a copy of the License at |
  | http://www.opensource.org/licenses/gpl-license.php                   |
  |                                                                      |
  | Software distributed under the License is distributed on an "AS IS"  |
  | basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See  |
  | the License for the specific language governing rights and           |
  | limitations under the License.                                       |
  +----------------------------------------------------------------------+
  | The Original Code is: Elastix Open Source.                           |
  | The Initial Developer of the Original Code is PaloSanto Solutions    |
  +----------------------------------------------------------------------+
  $Id: new_campaign.php $ */

#include_once("libs/paloSantoDB.class.php");

/* Clase que implementa campaña (saliente por ahora) de CallCenter (CC) */
class LiveMonitorInfo
{
    var $_DB; // instancia de la clase paloDB
    var $errMsg;
    var $queue_opt,$exten_spy;
    var $totales,$agent_on_breaks,$where_con;
    function  __construct(&$pDB){
        // Se recibe como parámetro una referencia a una conexión paloDB
        if (is_object($pDB))
            $this->_DB = $pDB;
        else {
            $dsn = (string)$pDB;
            $this->_DB = new paloDB($dsn);

            if (!$this->_DB->connStatus) {
                $this->errMsg = $this->_DB->errMsg;
                // debo llenar alguna variable de error
            } else {
                // debo llenar alguna variable de error
            }
        }
       $this->agent_on_breaks = false ;
       $this->queue_opt = "";
    }
   function bring_count_info($query) {

    $result=$this->_DB->getFirstRowQuery($query);
     if($result==FALSE){
            $this->errMsg = $this->_DB->errMsg;
            return null;
        }
     return (int)$result[0];
   }

   function bring_agent_info() {

	$this->totales[ "agent_on_brk" ]    = 0;
        $this->totales[ "agent_on_call" ]   = 0 ;
        $this->totales[ "agent_on_ready" ]  = 0 ;
        $this->totales[ "agent_logged_in" ] = 0 ;

       if( empty($this->queue_opt)) {

                $query = "SELECT sum(on_break) on_brk,sum(on_call) on_call,
                                 sum(on_ready) on_ready,sum(on_logged_in) logged_in
                          FROM agent_status";

                $details = $this->_DB->getFirstRowQuery($query,true);


       }else{
                $queue = $queues[$this->queue_opt];
                $query = "SELECT on_break on_brk,on_call on_call,
                                 on_ready on_ready,on_logged_in logged_in
                          FROM agent_status
                          WHERE queue_id='{$queue}'";
                $details = $this->_DB->getFirstRowQuery($query,true);
       }

        if(!empty($details)){

                foreach($details as $key => $value )
                        $this->totales[ "agent_".$key ] = $value;

        }

   }
   function bring_queue_info($date=null) {

   //Queue Info
	 if(is_null($date))
 	 	$date= date("Y-m-d");
     $date = '2022-05-29';

   	 $this->where_con = (empty($this->queue_opt))? '': " AND id_queue_call_entry={$this->queue_opt}";
   	 $query = "
		SELECT  call_entry.status, count(call_entry.id) hits FROM call_entry
		WHERE convert(datetime_entry_queue,date)= '{$date}'
		   	{$this->where_con}
		GROUP BY call_entry.status ORDER BY call_entry.status";
   	 $result_queue = $this->_DB->fetchTable($query);
   	 if($result_queue===FALSE){
   	         $this->errMsg = $this->_DB->errMsg;
   	         return null;
   	 }

   	 $this->totales["entered"]=0;
   	 $this->totales["abandoned"]=0;
   	 $this->totales["waiting_calls"]=0;
   	 $this->totales["answered"]=0;
     $this->totales["failover"]=0;


	 foreach($result_queue as $key=>$data) {
   	             $this->totales["entered"] += $data[1]; //hits
   	             switch (strtoupper($data[0])) { //status
   	                 case "ABANDONADA";
   	                    $this->totales["abandoned"] = $this->totales["abandoned"] + $data[1]; //hits
   	                 break;
   	                 case "EN-COLA";
   	                   $this->totales["waiting_calls"] = $this->totales["waiting_calls"] + $data[1];
   	                 break;
   	                 case "FAILOVER";
   	                   $this->totales["failover"] = $this->totales["failover"] + $data[1];
   	                 break;
   	                 case "TERMINADA";
   	                 case "ACTIVA";
   	                 case "HOLD";
   	                     $this->totales["answered"] = $this->totales["answered"]+$data[1];
   	             }
   	 } // fin del foreach

   	 $query =   "SELECT
                	AVG(duration) as ACD,
			        MAX(duration_wait) as MAX,
			        AVG(duration_wait) as AVG
   	             FROM
   	     		    call_entry
   	             WHERE
   	            	 convert(datetime_entry_queue,date)= '{$date}'
			      {$this->where_con}";

   	 $result=$this->_DB->getFirstRowQuery($query);
   	 if($result==FALSE){
   	         $this->errMsg = $this->_DB->errMsg;
   	         return null;
   	 }

	 if(empty($result[0]))
	    $result[0] = $result[1] = $result[2] = $result[3] = "0" ;

	 if($result[0] < 0 )
			$result[0] = 0;
   	 $this->totales["avg_call_time"] = $this->sec2HHMMSS($result[0]);
   	 $this->totales["max_wait_time"] = $this->sec2HHMMSS($result[1]);
   	 $this->totales["avg_wait_time"] = $this->sec2HHMMSS($result[2]);

  }

 function sec2HHMMSS($sec)       {

                $HH = '00'; $MM = '00'; $SS = '00';

                if($sec >= 3600){
                        $HH = (int)($sec/3600);
                        $sec = $sec%3600;
                        if( $HH < 10 ) $HH = "0$HH";
                }

                if( $sec >= 60 ){
                        $MM = (int)($sec/60);
                        $sec = $sec%60;
                        if( $MM < 10 ) $MM = "0$MM";
                }

                $SS = $sec;
                if( $SS < 10 ) $SS = "0$SS";

                return "{$HH}:{$MM}:{$SS}";
        }

 function bring_current_call_info() {

     $this->where_con = (empty($this->queue_opt))? '': " AND id_queue_call_entry = '$this->queue_opt'";
     $query = "
 		SELECT
 			distinct(agent.number),agent.name,callerid,
            UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(cce.datetime_init) as duration,
            cce.datetime_init,'SIP/1000'
        FROM
 			current_call_entry as cce,agent
 		WHERE
 			agent.id=cce.id_agent
 			$this->where_con
 		ORDER BY cce.datetime_init desc";
     $result=$this->_DB->fetchTable($query);

     if(is_null($result)){
             $this->errMsg = $this->_DB->errMsg;
             return null;
     }

    if(count($result) > 0 ) {

    //$result[] = array("7001","mtaher","234234",32);

    $current_call_tag = "
 <br><div class=agent_info>الموظفين في وضع الاتصال</div>
 <table width=100% border=1>
 <tr><td width=2% align=center><img src=img/telephone_ans.gif width=50px height=50px></td><th class=info_head>رقم الموظف</th><th class=info_head>رقم التحويله</th><th class=info_head>اسم الموظف</th><th class=info_head>رقم المتصل</th><th class=info_head>مدة المكالمة</th></tr>";

    $current_call_row = "<tr><td class=info_val><a href='http://10.50.33.43/chan_spy.php?exten_spy=%s&agent_id=%s' target='_blank'><img src=img/agent_on_call.png width=50px height=50px></a></td><td class=info_val>%s</td><td class=info_val>%s</td><td class=info_val>%s</td><td class=info_val>%s</td><td>
 <div class='meter-wrap'>
         <div style='background-color: #00ff00'>
     <div class='meter-value' style='background-color: #ff0000; width: %s;'>%s</div>
          </div>
         </div>
 </td></tr>";
    $content = "" ;
    foreach($result as $in => $row) {

	    $duration = $row[3];
	    $row[3] = $duration/1800 * 100;
	    $row[4] = $this->sec2HHMMSS($duration);
 	    if($row[3] > 100 )
 	         $row[3] = 100; //just for align if it should not go beyond
 	$content = $content.sprintf($current_call_row,$this->exten_spy,$row[0],$row[0],$row[5],$row[1],$row[2],ceil($row[3])."%",$row[4]);
   }

    $this->totales["agent_current_calls"] = $current_call_tag.$content."</table>";
   }
    else
    $this->totales["agent_current_calls"] = "";
 }

  function bring_break_info() {

     $this->where_con = (empty($this->queue_opt))? '': "AND alq.id_queue = '$this->queue_opt'";
     $query = "SELECT
  		agent.number,agent.name,DATEDIFF(SECOND,alq.modifiedon,GETDATE()) as duration,alq.in_chan,alq.in_chan
               FROM
  		agent_logged_queue alq JOIN agent ON alq.id_agent=agent.id
	       WHERE
		alq.status = 'inbreak'
		{$this->where_con}
              ORDER BY alq.modifiedon";
      $result=$this->_DB->fetchTable($query);
      if(is_null($result)){
              $this->errMsg = $this->_DB->errMsg;
              return null;
      }
    if(count($result) > 0 ) {

        $current_break_tag = "
  <br><div class=agent_info>الموظفين في وضع الراحة</div>
   <table width=100% border=1> <tr><td width=3% align=center><img src=img/agent_break_head.jpg width=50px height=50px></td><th class=info_head>رقم الموظف</th><th class=info_head>رقم التحويلة</th><th class=info_head> اسم الموظف</th><th class=info_head>مدة وقت الراحة</th></tr>";

        $current_break_row = "<tr><td class=info_val><img src=img/agent_on_break.jpg width=50px height=50px></td><td class=info_val>%s</td><td class=info_val>%s</td><td class=info_val>%s</td><td>
     <div class='meter-wrap'>
             <div style='background-color: #00ff00'>
         <div class='meter-value' style='background-color: #ff0000; width: %s;'>%s</div>
              </div>
             </div>
     </td></tr>";
        $content = "" ;
        foreach($result as $in => $row) {
	    $duration = $row[2];
            $row[2] = $duration/1800 * 100;
            $row[3] = $this->sec2HHMMSS($duration);
  	    if($row[2] > 100 )
  	         $row[2] = 100; //just for align if it should not go beyond
            $content = $content.sprintf($current_break_row,$row[0],$row[4],$row[1],$row[2]."%",$row[3]);
        }
        $this->totales["agent_current_breaks"] = $current_break_tag.$content."</table>";

     }
     else
        $this->totales["agent_current_breaks"] = "";
  }

  function bring_ring_info() {


	$date = date("Y-m-d");
     	$this->where_con = (empty($this->queue_opt))? '': "AND id_queue_call_entry = '$this->queue_opt'";
	$query = "SELECT callerid,DATEDIFF(SECOND,datetime_entry_queue,GETDATE()),ext_ringing,ext_ringagent,ext_noanswer,ext_noansweragent
		  FROM call_entry WHERE  status='en-cola'  and convert(date,datetime_entry_queue) = '$date' {$this->where_con} order by id";


	$result = $this->_DB->fetchTable($query);



	  if(count($result) > 0 ) {

        $ring_tag = "
  <br><div class=agent_info>مكالمات الانتظار</div>
     <table width=100% border=1>
     <tr>
	<th width=3% align=center><img src=img/calls_waiting.png width=50px height=50px></th>
	<th class=info_head>رقم المتصل</th>
	<th class=info_head>الموظف المستلم للمكالمة </th><th class=info_head>الموظف لايرد على المكالمة </th><th class=ring_duration>مدة الانتظار</th>
	</tr>";

        $ring_row = "<tr><td class=meter-wrap><img src=img/%s width=50px height=50px></td><td class=info_val>%s</td>
<td >%s</td><td >%s</td>
<td>
     <div class='meter-wrap'>
             <div style='background-color: #00ff00'>
         <div class='meter-value' style='background-color: #ff0000; width: %s;'>%s</div>
              </div>
             </div>
     </td>
</td>
</tr>";
        $content = "" ;
        foreach($result as $in => $row) {


	    $image = "queue_waiting.png";
	    $callerid = $row[0];
            $duration = (int)$row[1];
	    if($duration < 0 )
			$duration = 0;
            $percentage = ceil($duration/1800 * 100);
            $duration_time = $this->sec2HHMMSS($duration);
	    $ringing_ext = $row[2];
	    $ringing_agent = $row[3];
	    $missed_ext = $row[4]; //4
	    $missed_agent = $row[5]; //5
	    if(!empty($ringing_ext)){
		$image = "telephone_ring.gif";
		$ringing_agent = "<font class=ring_extension>{$ringing_agent}</font>&nbsp;&nbsp;&nbsp;<font class=sub_extension>Ext.{$ringing_ext}</font>";
	     }

	    if(!empty($missed_ext)) {

		$missed_agent = "<font class=missed_extension>{$missed_agent}</font>&nbsp;&nbsp;&nbsp;<font class=sub_extension>Ext.{$missed_ext}</font>";

	    }

            if($percentage > 100 )
                 $percentage = 100; //just for align if it should not go beyond
            $content = $content.sprintf($ring_row,$image,$callerid,$ringing_agent,$missed_agent,$percentage."%",$duration_time);
        }
        $this->totales["agents_ring_info"] = $ring_tag.$content."</table>";

     }
     else
        $this->totales["agents_ring_info"] = "";


  }

 function bringInfo() {


     $this->bring_agent_info();
     $this->bring_queue_info();
     $this->bring_current_call_info();
     #$this->bring_break_info();

 }
}

?>
