<link rel="stylesheet" type="text/css" href="css/live_monitor.css?version=1.1"/>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/raphael-2.1.4.min.js"></script>
<script type="text/javascript" src="js/justgage.js"></script>

<style>
.gauge {
   width: 290px;
   height: 220px;
}
</style>


<table width='100%'>
<tr>
<td width="25%">
<div class="agent_info">Agent Information</div>
</td>
<td width="50%" align="center">
<span class="main_heading">{{$rep_info["heading"]}}</span>
</td>
<td width="25%" align="right">
<div class="select_tags">
<form action="/livedash">
        <input type="text" value="{{$exten_spy}}" name="exten_spy" size="3px">
        <select class="select_class" id="cbo_estado" name="queue_opt" >
            @foreach($queues as  $id => $queue)
                <option value="{{$id}}" {{ $queue_opt === $id ? 'selected' : '' }}>{{ $queue }}</option>
            @endforeach
        </select>
        <input type="submit" class="button" value="Go" name="submit">
</form>
</div>
</td>
</table>
