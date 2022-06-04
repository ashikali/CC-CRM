<?php

namespace App\Http\Controllers\CSAT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class ReportController extends Controller {


	public function answers(){

        abort_if(Gate::denies('csat_report'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		return view("csat.answers");

	}



}
