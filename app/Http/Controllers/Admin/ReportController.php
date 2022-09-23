<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CSATAnswer;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Illuminate\Support\Collection;

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
      public function answers_horizontal(Request $request){

         abort_if(Gate::denies('csat_report'), Response::HTTP_FORBIDDEN, '403 Forbidden');

	 if ($request->ajax()) {

		 $start_date = date('Y-m-d');
        	 $end_date = date('Y-m-d');

        	 if(isset($request->start_date))
        	             $start_date = $request->start_date;
        	 if(isset($request->end_date))
        	             $end_date = $request->end_date;

        	 $records = CSATAnswer::with(['entry.call_entry','question'])->select(sprintf('%s.*', (new CSATAnswer())->table))
        	          ->whereBetween('answers.created_at', array($start_date." 00:00:00", $end_date." 23:59:59" ))->orderBy('entry_id','DESC')->get();

		 $table = Array();
		 foreach($records as $in => $record){
			 $uniqueid = $record->entry->call_entry->uniqueid;
			 if(!isset($table[$uniqueid]))
			 	$table[$uniqueid] = Array($uniqueid,$record->entry->call_entry->callerid,$record->entry->created_at);
			 $table[$uniqueid]["q{$record->question_id}"] = $record->value;
		 }

		$data = new Collection;
       		foreach( $table as $uniqueid => $record ){
       		    $data->push([
       		        'placeholder'  => '',
       		        'Unique ID'  => $record[0],
       		        'Caller ID'  => $record[1],
       		        'Question1'  => $record["q1"] ?? '',
       		        'Question2'  => $record["q2"] ?? '',
       		        'Comment'    => $record["q3"] ?? '',
			'Created At' => $record[2]->format('Y-m-d h:i:s')
       		    ]);
       		}
     		return Datatables::of($data)->make(true);	

         }
         return view('csat.answers_horizontal');
 
      }
      

}
