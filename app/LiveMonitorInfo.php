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
                $query = "SELECT on_break on_brk,on_call on_call,
                                 on_ready on_ready,on_logged_in logged_in
                          FROM agent_status
                          WHERE queue_id='{$this->queue_opt}'";
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

   	 $result=$this->_DB->getFirstRowQuery($query,true);
   	 if($result==FALSE){
   	         $this->errMsg = $this->_DB->errMsg;
   	         return null;
   	 }
	 if(empty($result["ACD"]))
	    $result["ACD"] = $result["MAX"] = $result["AVG"] = 0;

	 if($result["ACD"] < 0 )
			$result["ACD"] = 0;
   	 $this->totales["avg_call_time"] = $this->sec2HHMMSS(round($result["ACD"]));
   	 $this->totales["max_wait_time"] = $this->sec2HHMMSS(round($result["MAX"]));
   	 $this->totales["avg_wait_time"] = $this->sec2HHMMSS(round($result["AVG"]));

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
 			distinct(agent.number) number,agent.name,callerid,
            UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(cce.datetime_init) as duration,
            cce.datetime_init,CONCAT('SIP/',agent.number) as extension
        FROM
 			current_call_entry as cce,agent
 		WHERE
 			agent.id=cce.id_agent
 			$this->where_con
 		ORDER BY cce.datetime_init desc";
     $result=$this->_DB->fetchTable($query,true);

     if(is_null($result)){
             $this->errMsg = $this->_DB->errMsg;
             return null;
     }

    if(count($result) > 0 ) {

    //$result[] = array("7001","mtaher","234234",32);

    $current_call_tag = "
 <br><div class=agent_info>Call Information</div>
 <table width=100% border=1>
 <tr><td width=2% align=center><img src=img/telephone_ans.gif width=50px height=50px></td>
 <th class=info_head>Agent Name</th>
 <th class=info_head>Agent Number</th>
 <th class=info_head>Extension</th>
 <th class=info_head>Caller ID</th>
 <th class=info_head>Duration</th>";

    $current_call_row = "<tr>
    <td class=info_val><a href='http://10.20.50.223/chan_spy.php?exten_spy=%s&agent_number=%s' target='_blank'><img src=img/agent_on_call.png width=50px height=50px></a></td>
    <td class=info_val>%s</td>
    <td class=info_val>%s</td>
    <td class=info_val>%s</td>
    <td class=info_val>%s</td>
    <td>
 <div class='meter-wrap'>
         <div style='background-color: #00ff00'>
     <div class='meter-value' style='background-color: #ff0000; width: %s;'>%s</div>
          </div>
         </div>
 </td></tr>";
    $content = "" ;
    foreach($result as $in => $row) {

	    $duration = $row['duration'];
	    $row[3] = $duration/1800 * 100;
	    $row[4] = $this->sec2HHMMSS($duration);
 	    if($row[3] > 100 )
 	         $row[3] = 100; //just for align if it should not go beyond
 	$content = $content.sprintf($current_call_row,$this->exten_spy,$row['number'],$row['name'],$row['number'],$row['extension'],
         $row['callerid'],ceil($row[3])."%",$row[4]);
   }

    $this->totales["agent_current_calls"] = $current_call_tag.$content."</table>";
   }
    else
    $this->totales["agent_current_calls"] = "";
 }

  function bring_break_info() {

    # $this->where_con = (empty($this->queue_opt))? '': "AND alq.id_queue = '$this->queue_opt'";
     $query = "
     SELECT  agent.name,agent.number,
            UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(datetime_init) as duration,CONCAT('SIP/',agent.number) as extension
     FROM    audit,agent
     WHERE   audit.id_agent=agent.id AND audit.id_break IS NOT NULL AND audit.datetime_end IS NULL AND audit.datetime_init IS NOT NULL                ORDER BY agent.name;
     ";
      $result=$this->_DB->fetchTable($query,true);
      if(is_null($result)){
              $this->errMsg = $this->_DB->errMsg;
              return null;
      }
    if(count($result) > 0 ) {

        $current_break_tag = "
  <br><div class=agent_info>Agents On Break</div>
   <table width=100% border=1>
   <tr><td width=3% align=center><img src=img/agent_break_head.jpg width=50px height=50px></td>
       <th class=info_head>Agent Name</th><th class=info_head>Agent Number</th>
       <th class=info_head>Extension</th><th class=info_head>Duration</th></tr>";

        $current_break_row = "<tr><td class=info_val><img src=img/agent_on_break.jpg width=50px height=50px></td>
                              <td class=info_val>%s</td><td class=info_val>%s</td><td class=info_val>%s</td><td>
     <div class='meter-wrap'>
             <div style='background-color: #00ff00'>
         <div class='meter-value' style='background-color: #ff0000; width: %s;'>%s</div>
              </div>
             </div>
     </td></tr>";
        $content = "" ;
        foreach($result as $in => $row) {
	        $duration = $row['duration'];
            $row[2] = $duration/1800 * 100;
            $row[3] = $this->sec2HHMMSS($duration);
  	    if($row[2] > 100 )
  	         $row[2] = 100; //just for align if it should not go beyond
            $content = $content.sprintf($current_break_row,$row['name'],$row['number'],$row['extension'],$row[2]."%",$row[3]);
        }
        $this->totales["agent_current_breaks"] = $current_break_tag.$content."</table>";

     }
     else
        $this->totales["agent_current_breaks"] = "";
  }

  function bring_waiting_info() {

	$date = date("Y-m-d");
   	$this->where_con = (empty($this->queue_opt))? '': "AND id_queue_call_entry = '$this->queue_opt'";

	$query = "SELECT callerid,UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(datetime_entry_queue) as duration
		      FROM call_entry
              WHERE  status='en-cola'
                     and convert(datetime_entry_queue,date) = '$date' {$this->where_con} order by id";


	$result = $this->_DB->fetchTable($query,true);

    if(count($result) > 0 ) {

        $ring_tag = "
  <br><div class=agent_info>Waiting Calls</div>
     <table width=100% border=1>
     <tr>
	<th width=3% align=center><img src=img/calls_waiting.png width=50px height=50px></th>
	<th class=info_head>Caller id</th>
	<th class=info_head>Waiting Duration</th>
	</tr>";

        $ring_row = "<tr>
                         <td class=meter-wrap><img src=img/queue_waiting.png width=50px height=50px></td>
                         <td class=info_val>%s</td>
                         <td>
                             <div class='meter-wrap'>
                                     <div style='background-color: #00ff00'>
                                 <div class='meter-value' style='background-color: #ff0000; width: %s;'>%s</div>
                                      </div>
                             </div>
                         </td>
                    </tr>";

        $content = "" ;
        foreach($result as $in => $row) {

	    $image = "queue_waiting.png";
	    $callerid = $row['callerid'];
        $duration = (int)$row['duration'];
	    if($duration < 0 )
			$duration = 0;
        $percentage = ceil($duration/1800 * 100);
        $duration_time = $this->sec2HHMMSS($duration);
        if($percentage > 100 )
           $percentage = 100; //just for align if it should not go beyond
        $content = $content.sprintf($ring_row,$callerid,$percentage."%",$duration_time);

        }
        $this->totales["agents_ring_info"] = $ring_tag.$content."</table>";

     }
     else
        $this->totales["agents_ring_info"] = "";


  }

 function bringInfo() {


     $this->bring_agent_info();
     $this->bring_queue_info();
     $this->bring_waiting_info();
     $this->bring_current_call_info();
     $this->bring_break_info();

 }
}

?>
