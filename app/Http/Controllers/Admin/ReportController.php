<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CSATAnswer;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Debugbar;

class ReportController extends Controller {


	public function answers(Request $request){

         abort_if(Gate::denies('csat_report'), Response::HTTP_FORBIDDEN, '403 Forbidden');
	 if ($request->ajax()) {

         	$start_date = date('Y-m-d');
         	$end_date = date('Y-m-d');
		
         	if(isset($request->start_date))
         	            $start_date = $request->start_date;
         	if(isset($request->end_date))
         	            $end_date = $request->end_date;

		$query = CSATAnswer::with(['entry.call_entry','question'])->select(sprintf('%s.*', (new CSATAnswer())->table))
         		 ->whereBetween('answers.created_at', array($start_date." 00:00:00", $end_date." 23:59:59" ));
			
         	$table = Datatables::of($query);
		$table->addColumn('placeholder','');
 

         	return $table->make(true);

         }

        return view('csat.answers');
 
      }

}
