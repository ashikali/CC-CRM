<html>
<head>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
  function validate(){
	  if($('#q1-not-satisfied').is(':checked') ||
		$('#q2-not-satisfied').is(':checked')	
	    ){ 
		var inp = $("#q3").val(); 
		if(jQuery.trim(inp).length < 1){
			alert('please, fill the comment if you choose not satisfied');
			return false;		
		}
		
		return true;
	     }	

  };
  $("form").submit(function() {
	return validate();
  }); 
   
});
</script>
</head>
<body>
<div class="container">
    <form method="POST" action="{{ route("csat.store",[ $survey->id,$uniqueid ]) }}" enctype="multipart/form-data">
	@csrf
	@if(session('success'))
		<div class="alert alert-success">{{session('success')}}</div>
	@endif
	@include('survey::standard', ['survey' => $survey])
    </form>
</div>
</body>
</html>

