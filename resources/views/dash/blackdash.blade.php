<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mci Call Center Live</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="MCI Call Center">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css?version=1" />
    <link href="css/black/main.css?version=5" rel="stylesheet">

    <script src="js/highchart/highcharts.js"></script>
    <script src="js/highchart/highcharts-3d.src.js"></script>
    <script src="js/highchart/highcharts-3d.js"></script>

    <script type="text/javascript" src="js/jquery.min.js"></script>    
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/lineandbars.js?version=2.1"></script>
    <script type="text/javascript" src="js/raphael-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/justgage.js"></script>
    <script type="text/javascript" src="js/Chart.bundle.js"></script>
    <script type="text/javascript" src="js/configs.js"></script>
    <script type="text/javascript" src="js/Chart.PieceLabel.js"></script>
<!-- Am Chart -->

<style>
.gauge {
   width: 220px;
   height: 220px;
}
.gaugetot {
   width: 225px;
   height: 225px;
}
#labeldraw li  {

 display: inline-block;
 padding:5px;
 margin:2px;
 width:90px;
 border: 2px solid black;
 color:white;
 font-size:18px;
 text-align:center;

}
.green {
  background: #0da5a0;
}
.lgreen {
  background: #00f2c3;
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
</head>
  <body dir="rtl">
  
  <br/>
    <div class="container-fluid">

	  <!-- FIRST ROW OF BLOCKS -->     
      <div class="row">

      <!-- USER PROFILE BLOCK -->
        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["501"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c501"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g501" class="gauge"></div>
			</div>	
		</div>
        </div>
        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["508"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c508"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g508" class="gauge"></div>
			</div>	
		</div>
        </div>
        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["500"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c500"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g500" class="gauge"></div>
			</div>	
		</div>
        </div>
        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["513"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c513"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g513" class="gauge"></div>
			</div>	
		</div>
        </div>


      </div><!-- /row -->
      
	  <!-- SECOND ROW OF BLOCKS -->     
      <div class="row">

        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["506"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c506"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g506" class="gauge"></div>
			</div>	
		</div>
        </div>
        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["515"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c515"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g515" class="gauge"></div>
			</div>	
		</div>
        </div>
        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["510"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c510"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g510" class="gauge"></div>
			</div>	
		</div>
        </div>
        <div class="col-sm-3 col-lg-3" align="center">
      		<div class="dash-unit"> 
		<h3>{{$queues_info["516"]["name"]}}</h3>
		<hr> 
			<div class="col-sm-6 col-lg-6">	
				<canvas id="c516"  width="220" height="220"></canvas>
			</div>
			<br/>
			<div class="col-sm-6 col-lg-6">	
		      		  <div id="g516" class="gauge"></div>
			</div>	
		</div>
        </div>
		
      </div><!-- /row -->
	  <!-- THIRD ROW OF BLOCKS -->     
      <div class="row">
	<!-- LATEST NEWS BLOCK -->     
      	<div class="col-sm-6 col-lg-6">
      		<div class="dash-unit" style="height:390px">
	      	<h3>{{trans("messages.Waiting and Live Calls")}}</h3>
      		<hr>
		      <div id="importantchart"></div>
		      <br></br>
		        <h3 id="total2" style="color:rgb(0, 255, 43)">{{$total_info["activa"]}} / {{$total_info["en-cola"]}}</h3>
		</div>
      	</div>

      	<div class="col-sm-3 col-lg-3">
	  <!-- TOTAL SUBSCRIBERS BLOCK -->     
      		<div class="dash-unit" style="height:390px;overflow:hidden" align="center">
	      		<h3>{{trans("messages.AllNutTitle")}}</h3>
			<hr>
			 <div id="nutall"></div>
      		</div>
      		
      	</div>

      	<div class="col-sm-3 col-lg-3">

	 <!-- BARS CHART - lineandbars.js file -->     
      	 <div class="dash-unit">
		<h3>{{trans("messages.Answered and Abondoned Calls")}}</h3>
		<hr>
		<div class="col-sm-6 col-lg-6 padding-0">
	        	<div id="totans" class="gaugetot"></div>
		</div>
		<div class="col-sm-6 col-lg-6 padding-0">
	        	<div id="totabd" class="gaugetot"></div>
		</div>
      	 </div>

	  <!-- TO DO LIST -->     
      	<div class="half-unit" style="height:70px;text-align:center"> 
		<h3><span class="label green">&emsp;{{trans('messages.Completed')}}&emsp;</span>
		<span class="label lgreen">{{trans('messages.OnLine')}}&nbsp;</span>	
		<span class="label lightred">{{trans('messages.Waiting')}}&nbsp;</span>	
		<span class="label red">&emsp;{{trans('messages.Abandoned')}}&emsp;</span></h3>
      	</div>


      	</div>



      </div><!-- /row -->
      
</div> <!-- /container -->


<script>
$(document).ready(function () {



  window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)',
	dgreen:'rgb(0, 255, 43)', 
	dred:'#FF0000', 
	ogreen: '#b2c831',
	lgreen: '#00f2c3',
	
}; 	



 var options =  {
          responsive: false,
          maintainAspectRatio: false,
          borderColor: '#f13d19', 	
          legend: {
              display:false,
             position: 'left',
             labels:{
              fontStyle: 'bold',
      	      fontSize: 5,
      	      fontColor: "#ffffff",
             }
           },
          title: {
              display: false,
              text: 'Testing', 
              fontSize:18,
              fontStyle: 'bold',
              fontColor:'#ffff',

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
                  fontSize: 20,
                  fontStyle: 'bold',
                  fontColor: '#ffff',
      	    arc:false,
                 },
         elements: {
               center: {
               text: 'Desktop',
              }
         },

   };


	
  window.charts = {};
  @foreach( $queues_info as $queue => $info ) 

	@if( ( $info["activa"] + $info["en-cola"] + $info["abandonada"]) > 0 )

		  window.charts["c{{$queue}}"] =  new Chart($("#c{{$queue}}"), {
                             type: "doughnut",
                             options: options,
                             data: {
                                     labels: ["{{trans('messages.OnLine')}}",
                                              "{{trans('messages.Waiting')}}","{{trans('messages.Abandoned')}}"],
                                   datasets: [
                                     {
                                       data: [{{$info["activa"]}},{{$info["en-cola"]}},{{$info["abandonada"]}}],
                                       backgroundColor: [
                                         window.chartColors.lgreen,
                                         window.chartColors.red,
                                         "#FF0000",
                                       ]
                                     },
                                   ]
                                 }
                                });
       		   window.charts["c{{$queue}}"].options.title.text = '{{$info["name"]}}';

	@elseif( $info["terminada"] > 0 ) //in case only terminada

		  window.charts["c{{$queue}}"] =  new Chart($("#c{{$queue}}"), {
                             type: "doughnut",
                             options: options,
                             data: {
                                     labels: ["{{trans('messages.Completed')}}"],
                                   datasets: [
                                     {
                                       data: [{{$info["terminada"]}}],
                                       backgroundColor: [ window.chartColors.green ]
                                     },
                                   ]
                                 }
                    });
                   window.charts["c{{$queue}}"].options.title.text = '{{$info["name"]}}';

	@endif

     window.charts["g{{$queue}}"] = new JustGage({
       id: "g{{$queue}}",
       value: {{$info["terminada"]}},
       title: "{{trans('messages.Completed')}}", 
       titlePosition: "below",
       titleFontColor: "white",
       valueFontColor: window.chartColors.dgreen,
       min: 0,
       max: 1000,
       pointer: true,
       pointerOptions: {
             toplength: 5,
             bottomlength: 8,
             bottomwidth: 5,
             color: window.chartColors.red,
       },
       gaugeColor: window.chartColors.yellow,
       levelColors: [window.chartColors.dgreen],
       levelColorsGradient:true, 	
       gaugeWidthScale: 0.6,
       counter: true,
       hideMinMax: true,

     });   



   @endforeach
var randomScalingFactor = function() {
	return Math.round(Math.random() * 100);
};

   window.charts["gtotans"] = new JustGage({

            id: "totans",
      	    valueFontColor: window.chartColors.green,
            value: {{$total_info["terminada"]}},
            min: 0,
            max: 5000,
            donut: true,
            gaugeWidthScale: 0.6,
            counter: true,
	    pointer: true,
	    pointerOptions: {
	      toplength: 1,
	      bottomlength: 10,
	      bottomwidth: 8,
              color: window.chartColors.green, 
        	},	      
       	    gaugeColor: window.chartColors.yellow,
       	    levelColors: [window.chartColors.dgreen],
            hideInnerShadow: true

        });
   window.charts["gtotabd"] = new JustGage({

            id: "totabd",
            value: {{$total_info["abandonada"]}},
            valueFontColor: window.chartColors.red,
            min: 0,
            max: 1000,
            min: 0,
            max: 100,
            donut: true,
       	    levelColors: [window.chartColors.dred],
            gaugeWidthScale: 0.6,
            counter: true,
	    pointer: true,
	    pointerOptions: {
	      toplength: 1,
	      bottomlength: 10,
	      bottomwidth: 8,
              color: window.chartColors.red, 
        	},	      
       	    gaugeColor: window.chartColors.yellow,
            hideInnerShadow: true


        });


window.charts['nutall'] = Highcharts.chart('nutall', {
    colors: [window.chartColors.green,window.chartColors.lgreen,window.chartColors.red  ], 
    legend: {
	 itemStyle: {
	             color: '#fff',
		     fontWeight: 'bold',
		     fontSize: '19px'
		           }		
	 },
    chart: {
        type: 'pie',
	backgroundColor:'transparent',
        options3d: {
            enabled: true,
            alpha: 55,
            beta: 0
        },
	height: '355px',
    },
    title: {
        text:''
    },
    tooltip: {
        pointFormat: '<b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            cursor: 'pointer',
            depth: 35,
	    innerSize: 100,	
            dataLabels: {
                enabled: true,
		format: '{point.percentage:.1f}',
		color: 'red',
		style: {
		      fontSize:'15px',
		      fontWeight:'normal',
                   }	
	    },
	    showInLegend: true 
        }
    },
    series: [{
        type: 'pie',
        data: [
            ['{{trans("messages.Excellent")}}',{{$total_info["Excellent"]}}],
            ['{{trans("messages.Good")}}',{{$total_info["Good"]}}],
            ['{{trans("messages.Bad")}}',{{$total_info["Bad"]}}]
        ]
    }]
});


   function UpdateData( queue,info ){

	 var chart = window.charts["c"+queue];var gauge = window.charts["g"+queue];

	if( ( info["activa"] + info["en-cola"] + info["abandonada"] ) > 0 ){

         var newDataset =   {
                                  data: [info["activa"],info["en-cola"],info["abandonada"]],
                                  backgroundColor: [
                                     window.chartColors.lgreen,
                                     window.chartColors.red,
                                     "#FF0000",
                                   ]
                                 };
                                   
	}else if( info["terminada"] > 0 ){  //in case only terminada
            var  newDataset =     {
                              data: [info["terminada"]],
                              backgroundColor: [ window.chartColors.green ]
                            };
	}

       chart.config.data.datasets.splice(0);
       chart.data.datasets.push(newDataset);
       chart.update();

       gauge.refresh(info["terminada"]);

 }

 function UpdateChart() {


                   $.ajax({
                                  url: '{{ url ('blackdash') }}',
                                  method: 'GET',
                                  dataType: 'json',
                                  success: function (d) {
				  var ti =  d["total_info"];
				  window.charts["gtotans"].refresh(ti["terminada"]);
				  window.charts["gtotabd"].refresh(ti["abandonada"]);
				  window.charts["nutall"].series[0].setData([ parseInt(ti["Excellent"]),parseInt(ti["Good"]),parseInt(ti["Bad"]) ]);
				  $("#total2").html(ti["activa"]+" / " + ti["en-cola"]);

				  delete d["total_info"];
                                  for( var queue in d ){
                                        UpdateData( queue,d[queue] );
						
                                   }


                                }
                });

 }

setInterval(UpdateChart,30000);
//  setInterval(UpdateChart,60000);
	  
 });

</script>
</body></html>
