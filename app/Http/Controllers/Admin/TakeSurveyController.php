<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTakeSurveyRequest;
use App\Http\Requests\StoreTakeSurveyRequest;
use App\Http\Requests\UpdateTakeSurveyRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TakeSurveyController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('take_survey_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.takeSurveys.index');
    }

    public function create()
    {
        abort_if(Gate::denies('take_survey_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.takeSurveys.create');
    }

    public function store(StoreTakeSurveyRequest $request)
    {
        $takeSurvey = TakeSurvey::create($request->all());

        return redirect()->route('admin.take-surveys.index');
    }

    public function edit(TakeSurvey $takeSurvey)
    {
        abort_if(Gate::denies('take_survey_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.takeSurveys.edit', compact('takeSurvey'));
    }

    public function update(UpdateTakeSurveyRequest $request, TakeSurvey $takeSurvey)
    {
        $takeSurvey->update($request->all());

        return redirect()->route('admin.take-surveys.index');
    }

    public function show(TakeSurvey $takeSurvey)
    {
        abort_if(Gate::denies('take_survey_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.takeSurveys.show', compact('takeSurvey'));
    }

    public function destroy(TakeSurvey $takeSurvey)
    {
        abort_if(Gate::denies('take_survey_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $takeSurvey->delete();

        return back();
    }

    public function massDestroy(MassDestroyTakeSurveyRequest $request)
    {
        TakeSurvey::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
