@extends('layouts.admin')

@section('section')
<style>
.amchart{
  width: 410px;
  height: 410px;
  padding-top:20px;
}
#labeldraw li  {
 display : inline-block;
 padding:8px;
 width:80px;
 margin:5px;
 border : 2px solid black;
 color:white;
 font-size:21px;
}

.green {
  background: #0da5a0;
}

.purple {
  background: #9966ff;
}

.red {
  background: #FF0000;
}
.lightred {
 background:#ff6384;
}

</style>
<script src="{{ asset("js/Chart.bundle.js") }}"></script>
<script src="{{ asset("js/utils.js") }}"></script>
<script src="{{ asset("js/Chart.PieceLabel.js") }}"></script>
<script src="{{ asset("js/am/amcharts.js")}}"></script>
<script src="{{ asset("js/am/gauge.js")}}"></script>
<script src="{{ asset("js/am/export.min.js")}}"></script>
<script src="{{ asset("js/am/light.js")}}"></script>
<b>
<table >
<tr align="center" style="border-bottom: 2px solid #075654;">
	<td>
		<div id="amchart1" class="amchart"></div>
	</td>
	<td>{{$queues_info["501"]["name"]}}<canvas id="c501"  width="290" height="290"></canvas></td>
	<td>{{$queues_info["508"]["name"]}}<canvas id="c508"  width="290" height="290"></canvas></td>
	<td>{{$queues_info["505"]["name"]}}<canvas id="c505"  width="290" height="290"></canvas></td>
	<td>{{$queues_info["513"]["name"]}}<canvas id="c513"  width="290" height="290"></canvas></td>
</tr>
<tr align="center">
	<td>
		<div id="amchart2" class="amchart"></div>
	</td>
	<td>{{$queues_info["506"]["name"]}}<canvas id="c506"  width="290" height="290"></canvas></td>
	<td>{{$queues_info["512"]["name"]}}<canvas id="c512"  width="290" height="290"></canvas></td>
	<td>{{$queues_info["510"]["name"]}}<canvas id="c510"  width="290" height="290"></canvas></td>
	<td>{{$queues_info["502"]["name"]}}<canvas id="c502"  width="290" height="290"></canvas></td>
</tr>
</table>
<ul id="labeldraw">
  <li class="green">{{trans('messages.Completed')}}</li>
  <li class="purple">{{trans('messages.OnLine')}}</li>
  <li class="lightred">{{trans('messages.Waiting')}}</li>
  <li class="red">{{trans('messages.Abandoned')}}</li>
</ul>
</b>
<script>


$(function(){

    //get the doughnut chart canvas

    Chart.pluginService.register({

      beforeDraw: function(chart) {

      var width = chart.chart.width,
        height = chart.chart.height,
        ctx = chart.chart.ctx;

    ctx.restore();
    var fontSize = 1.5 ;
    ctx.font = fontSize + "em sans-serif";
    ctx.textBaseline = "middle";


    var total=0;
    for(var i in chart.config.data.datasets[0].data){ 

	    total += chart.config.data.datasets[0].data[i]; 

    }    

      var text = total; 
      textX = Math.round((width + ctx.measureText(text).width) / 2),
      textY = height / 2 - 10;

    ctx.fillText(text, textX, textY);
    ctx.save();
       }
   });     

	
        var options =  {
	            responsive: false,
		    maintainAspectRatio: false,
		    legend: {
			display:false,
		       position: 'bottom',
		       labels:{
		        fontStyle: 'bold',
		       }
		           },
		    title: {
		   	display: false, 
			text: 'test',
			fontSize:18,
		        fontStyle: 'bold',
		        fontColor:'#075654', 

		     },
		    label: {
			fontStyle: 'bold'
		    },
                    animation: {
                	animateScale: true,
                	animateRotate: true
            		},
		    pieceLabel: {
			    render: 'value',
			    fontSize: 22,
		            fontStyle: 'bold',
	    		    fontColor: '#000',
		           },
		   elements: {
		         center: {
		          text: 'Desktop',
    			}
		     }	 

            };

	  window.charts = {};
	  @foreach( $queues_info as $queue => $info )
	   	
	   window.charts[{{$queue}}] =  new Chart($("#c{{$queue}}"), {
		             type: "doughnut",
			     options: options,
		             data: {
				     labels: ["{{trans('messages.Completed')}}","{{trans('messages.OnLine')}}",
    					      "{{trans('messages.Waiting')}}","{{trans('messages.Abandoned')}}"],
	     			   datasets: [
	     			     {
	     			       data: [{{$info["terminada"]}},{{$info["activa"]}},{{$info["en-cola"]}},{{$info["abandonada"]}}],
	     			       backgroundColor: [
	     			         '#0da5a0',
	     			         window.chartColors.purple,
	     			         window.chartColors.red,
		   			 "#FF0000",
	     			       ]
	     			     },
	     			   ]
	     			 }
			        });
	  window.charts[{{$queue}}].options.title.text = '{{$info["name"]}}'; 
	 @endforeach

	  
    function UpdateData( chart,info ){

	    var newDataset1 = {  backgroundColor: ["#0da5a0",
						  window.chartColors.purple,
						  window.chartColors.red ,
		   				  "#FF0000",
					  ],
			       data: [  info['terminada'],info['activa'],
			       		info['en-cola'],info['abandonada'] ]

	    };
	    var newDataset2 = {  backgroundColor: ["#0da5a0",
						  window.chartColors.purple,
						  window.chartColors.red,
		   				  "#FF0000",
					 	 ],
			       data: [  0,info['activa'],
			       		info['en-cola'],info['abandonada'] ]

	    };
	  chart.config.data.datasets.splice(0);
	  chart.data.datasets.push(newDataset1);
	  if( ( info['activa'] + info['en-cola'] + info['abandonada'] ) > 0 )
	  	chart.data.datasets.push(newDataset2);
	  chart.update(); 	   
	   	

    }

    function UpdateChart() {


	           $.ajax({
			          url: '{{ url ('dash') }}',
			          method: 'GET',
				  dataType: 'json',
			          success: function (d) {
				  var abandoned=0,waiting=0;
				  for( var queue in d ){
					UpdateData( window.charts[queue],d[queue] );
					abandoned += d[queue]['abandonada'];
					waiting += d[queue]['en-cola'];
				   }
				   //abandoned=Math.round(Math.random() * 50);
				   amchart1.axes[0].setTopText(abandoned);
				   if( abandoned > 50 ) //because of the range
				   	abandoned = 50;
				   amchart1.arrows[0].setValue(abandoned);
				   amchart1.axes[0].bands[1].setEndValue(abandoned);

				   amchart2.axes[0].setTopText(waiting);
				   if( waiting > 50 ) //because of the range
				   	waiting = 50;
				   amchart2.arrows[0].setValue(waiting);
				   amchart2.axes[0].bands[1].setEndValue(waiting);
		  	

				}	
        	});

    } 


     function createamChart(id,title) {

      return AmCharts.makeChart(id,  {
				         "theme": "light",
					  "type": "gauge",
   				     	  "fontFamily":'tahoma',
					  "titles": [
				                       {
					
					                    "text": title, 
							    "size":20,
							    "color":'#075654', 
							     
						       }
                                		],
				   	"axes": [{
				     "topTextFontSize": 30,
				      "topTextYOffset": 100,
				     "topTextColor": 'red',
   				    "axisColor": "#31d6ea",
   				    "axisThickness": 3,
   				    "endValue": 50,
   				    "gridInside": true,
   				    "inside": true,
   				    "radius": "55%",
   				    "valueInterval": 5,
   				//  "tickColor": "#075654",
   				    "startAngle": -120,
   				    "endAngle": 120,
   				    "unit": "",
   				    "bandOutlineAlpha": 0,
   				    "bands": [{
   				              "color": "#0080ff",
   				        	        "endValue": 50,
   				        	          "innerRadius": "105%",
   				        	        "radius": "170%",
   				                    "gradientRatio": [0.5, 0, -0.5],
   				          "startValue": 0
   				    }, {
   				        "color": "#3cd3a3",
   				          "endValue": 0,
   				      "innerRadius": "105%",
   				      "radius": "170%",
   				      "gradientRatio": [0.5, 0, -0.5],
   				      "startValue": 0
   				    }]
				  }],
				  "arrows": [{
					    "alpha": 1,
					     "innerRadius": "25%",
				            "nailRadius": 0,
				          "radius": "170%"
						       
						    }]
				  });
   }

   var amchart1 = createamChart("amchart1","{{trans("messages.Abandoned Calls")}}");

   var amchart2 = createamChart("amchart2","{{trans("messages.Waiting Calls")}}");

   UpdateChart();
  //setInterval(ajaxCall, 300000);  5 minuts
  setInterval(UpdateChart,60000);  

});

</script>
@stop
