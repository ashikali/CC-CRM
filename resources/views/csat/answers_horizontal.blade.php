@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.CSAT Horizontal Report') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
	<div class="row pl-3">
		    <div class="form-inline">
	 		<div class="form-group pl-3 pb-3">
                	     <label for="start_date">{{ trans('global.start_date') }}</label><br/>
                 	     <input type="text" class="input-sm form-control date" name="start_date" id="start_date" value="" />
			</div>
	 		<div class="form-group pl-3 pb-3">
                	     <label for="end_date">{{ trans('global.end_date') }}</label>
                 	     <input type="text" class="input-sm form-control date" name="end_date" id="end_date" value=""/>
		  	</div>
	 		<div class="form-group pl-3 pb-3">
			 <button type="button" id="dateSearch" class="btn btn-sm btn-primary">Search</button>
			</div>
		   </div></br>
	</div>
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-CSATAnswer">
	 <thead>
            <tr>
                <th width="10"></th>
                <th>Unique ID</th>
		<th>Caller ID</th>
                <th>Kindly share your experience with the agent?</th>
                <th>How was the Overall Experience?</th>
                <th>Kindly Share your comments</th>
                <th>Created At</th>
            </tr>
         </thead>
         <tbody>
         </tbody> 
        </table>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
   var table = $('.datatable-CSATAnswer').DataTable({
	    processing: true,
	    serverSide: true,
	    pageLength: 50,
	    ajax: {
                url:"{{ route('admin.csat.answers_horizontal') }}",
                data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                }
            },
	    columns: [
		{data: 'placeholder', name: 'placeholder'},
	        {data: 'Unique ID', name: 'Unique ID'},
	        {data: 'Caller ID', name: 'Caller ID'},
	        {data: 'Question1', name: 'Question1'},
	        {data: 'Question2', name: 'Question2'},
	        {data: 'Comment', name: 'Comment'},
	        {data: 'Created At', name: 'Created At'},
	    ]
	});
        $('#dateSearch').on('click', function() {
                table.draw();
        });
 
});
</script>
@endsection
