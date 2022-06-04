<html>
<head>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <form method="POST" action="{{ route("csat.store",$uniqueid) }}" enctype="multipart/form-data">
	@csrf
	@if(session('success'))
		<div class="alert alert-success">{{session('success')}}</div>
	@endif
	@include('survey::standard', ['survey' => $survey])
    </form>
</div>
</body>
</html>
