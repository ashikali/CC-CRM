<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAbandonedCallRequest;
use App\Http\Requests\UpdateAbandonedCallRequest;
use App\Models\AbandonedCall;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AbandonedCallsController extends Controller
{
    public function index(Request $request)
    {

        abort_if(Gate::denies('abandoned_call_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
	    
	    $start_date = date('Y-m-d');
	    $end_date = date('Y-m-d');

	    if(isset($request->start_date))
			$start_date = $request->start_date;
	    if(isset($request->end_date))
			$end_date = $request->end_date;

            $query = AbandonedCall::query()->select(sprintf('%s.*', (new AbandonedCall())->table));
            $query->where('status','abandonada');
            $query->whereBetween('datetime_entry_queue', array($start_date." 00:00:00", $end_date." 23:59:59" ));
 
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {

                $viewGate = 'abandoned_call_show';
                $editGate = 'abandoned_call_edit';
                $deleteGate = 'abandoned_call_delete';
                $crudRoutePart = 'abandoned-calls';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));

            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('callerid', function ($row) {
                return $row->callerid ? $row->callerid : '';
            });
            $table->editColumn('datetime_entry_queue', function ($row) {
                return $row->datetime_entry_queue ? $row->datetime_entry_queue : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->c_status ? 'Abandoned' : '';
            });
            $table->editColumn('duration_wait', function ($row) {
                return $row->duration_wait ? gmdate('H:i:s',$row->duration_wait) : '';
            });
            $table->editColumn('c_status', function ($row) {
                return $row->c_status ? AbandonedCall::C_STATUS_SELECT[$row->c_status] : '';
            });
            $table->editColumn('c_action', function ($row) {
                return $row->c_action ? AbandonedCall::C_ACTION_SELECT[$row->c_action] : '';
            });
            $table->editColumn('c_reason', function ($row) {
                return $row->c_reason ? $row->c_reason : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.abandonedCalls.index');
    }

    public function edit(AbandonedCall $abandonedCall)
    {
        abort_if(Gate::denies('abandoned_call_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.abandonedCalls.edit', compact('abandonedCall'));
    }

    public function update(UpdateAbandonedCallRequest $request, AbandonedCall $abandonedCall)
    {
        $abandonedCall->update($request->all());

        return redirect()->route('admin.abandoned-calls.index');
    }

    public function show(AbandonedCall $abandonedCall)
    {
        abort_if(Gate::denies('abandoned_call_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.abandonedCalls.show', compact('abandonedCall'));
    }

    public function destroy(AbandonedCall $abandonedCall)
    {
        abort_if(Gate::denies('abandoned_call_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $abandonedCall->delete();

        return back();
    }

    public function massDestroy(MassDestroyAbandonedCallRequest $request)
    {
        AbandonedCall::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }


}
