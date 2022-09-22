<link rel="stylesheet" type="text/css" href="css/live_monitor.css?version=1.3"/>
<meta http-equiv="refresh" content="1800">

<table width='100%'>
<tr>
<td width="25%">
<div class="main_heading">معلومات الموظفين</div>
</td>
<td width="50%" align="center">
<span class="main_heading">{{$heading}}</span>
</td>
<td width="25%" align="left">
<div class="select_tags">
<form action="/livebreak">
	 {!! Form::select('queue_id', $queues_combo,$queue_id, ['placeholder' => trans('messages.Choose Queue'), 'class' => 'form-control','id' => 'queue_id' ]); !!}
	
        <input type="submit" class="button" value="Go" name="submit">
</form>
</div>
</td>
</tr>
</table>

<br>
<br>
<div> 
     <table width=100% border=1>
				
	  <tr>
                <td align=center><img src=img/agent_ready.png width=50px height=50px></td>
                <th class=info_head>ركم الموظف</th>
                <th class=info_head>اسم الموظف</th>
                <th class=info_head>المستلمة</th>
                <th class=info_head>لم ىرد عليها</th> 
                <th class=info_head>مدة المكالمة</th>
                <th class=info_head>معدل مدة المكالمهة</th>
                <th class=info_head>عدد وضع الراحة</th>
                <th class=info_head>أجمالي مددالراحة</th>
                <th class=info_head>Hits</th>
		<th class=info_head>العدد سيئ</th>
    	</tr>

	@foreach( $records as $i => $record)
	 <tr>
                <td class=info_val><img src=img/agent_ready.png width=50px height=50px></td>
                <td class=info_val>{{$record["number"]}}</td>
                <td class=info_val>{{$record["name"]}}</td>
                <td class=info_val>{{$record["no_of_calls"]}}</td>
                <td class=info_val>{{$record["no_abandoned_calls"]}}</td>
                <td class=info_val>{{sec_to_time($record["total_duration"])}}</td>
                <td class=info_val>{{sec_to_time($record["avg_duration"])}}</td>
                <td class=info_val>{{$record["no_of_breaks"]}}</td>
                <td class=info_val>{{sec_to_time($record["total_break"])}}</td>
                <td class=info_val>{{$record["ct_hits"]}}</td>
                <td class=info_val>{{$record["cnt"]}}</td>
        </tr>
	@endforeach
		
       </table>
   
</div>
