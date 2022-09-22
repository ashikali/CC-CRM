@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.CSAT Report') }} {{ trans('global.list') }}
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
                <th>Question</th>
                <th>Answer</th>
                <th>Created On</th>
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
	ajax: { 
		url:"{{ route('admin.csat.answers') }}",
		data: function (d) {
            	d.start_date = $('#start_date').val();
            	d.end_date = $('#end_date').val();
        	}
	},
        columns: [
	    {data: 'placeholder', name: 'placeholder'},
            {data: 'entry.uniqueid', name: 'entry.uniqueid'},
            {data: 'entry.call_entry.callerid', name: 'entry.call_entry.callerid'},
            {data: 'question.content', name: 'question.content'},
            {data: 'value', name: 'value'},
            {data: 'created_at', name: 'created_at'},
	],
	orderCellsTop: true,
   	order: [ [1,'asc'],[3,'asc'] ],
    	pageLength: 50,
    });
   $('#dateSearch').on('click', function() {
                table.draw();
            });
    
  });
</script>
@endsection
