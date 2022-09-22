@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.abandonedCall.title_singular') }} {{ trans('global.list') }}
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
		
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AbandonedCall">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.abandonedCall.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.abandonedCall.fields.callerid') }}
                    </th>
                    <th>
                        {{ trans('cruds.abandonedCall.fields.datetime_entry_queue') }}
                    </th>
                    <th>
                        {{ trans('cruds.abandonedCall.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.abandonedCall.fields.duration_wait') }}
                    </th>
                    <th>
                        {{ trans('cruds.abandonedCall.fields.c_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.abandonedCall.fields.c_action') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                    <td>
                        <!-- duration -->
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\AbandonedCall::C_STATUS_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\AbandonedCall::C_ACTION_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
    	url: "{{ route('admin.abandoned-calls.index') }}",
        data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
        }
    },
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'id', name: 'id' },
      { data: 'callerid', name: 'callerid' },
      { data: 'datetime_entry_queue', name: 'datetime_entry_queue' },
      { data: 'status', name: 'status' },
      { data: 'duration_wait', name: 'duration_wait' },
      { data: 'c_status', name: 'c_status' },
      { data: 'c_action', name: 'c_action' },
      { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-AbandonedCall').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
$('#dateSearch').on('click', function() {
                table.draw();
            });
});
</script>
@endsection
