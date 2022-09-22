<?php

namespace App\Http\Controllers\CSAT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;
use App\Models\AbandonedCall;
use Symfony\Component\HttpFoundation\Response;
use Debugbar;


class EntriesController extends Controller {

    public function entry($surveyid,$uniqueid){

        $count = AbandonedCall::where('uniqueid',$uniqueid)->count(); //Check if the survey has call uniqueid
        abort_if($count < 1, Response::HTTP_FORBIDDEN, '403 Forbidden');
        $count = Entry::where('uniqueid',$uniqueid)->count(); //Survey already finished for that call
        abort_if($count > 0, Response::HTTP_FORBIDDEN, '403 Forbidden');
        $survey = $this->getSurvey($surveyid);
        abort_if(empty($survey) > 0, Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('csat.submit',[ 'survey' => $survey,'uniqueid' => $uniqueid ]);

    }
    public function store(Request $request,$surveyid,$uniqueid){

          $count = Entry::where('uniqueid',$uniqueid)->count(); //Survey already finished for that call
          abort_if($count > 0, Response::HTTP_FORBIDDEN, '403 Forbidden');

          $survey = $this->getSurvey($surveyid);
          $answers = $this->validate($request, $survey->rules);
          $entry = new Entry;
          $entry->uniqueid = $uniqueid;
          $entry->for($survey)->fromArray($answers)->push();
          return view('csat.after_submit')->with('success','Thank you for your feedback');

    }
    protected function getSurvey($id){

            return Survey::where('id',$id)->first();

    }


}
