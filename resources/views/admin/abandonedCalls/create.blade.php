@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.abandonedCall.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.abandoned-calls.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>{{ trans('cruds.abandonedCall.fields.c_status') }}</label>
                <select class="form-control {{ $errors->has('c_status') ? 'is-invalid' : '' }}" name="c_status" id="c_status">
                    <option value disabled {{ old('c_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\AbandonedCall::C_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('c_status', 'Open') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('c_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('c_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.abandonedCall.fields.c_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.abandonedCall.fields.c_action') }}</label>
                <select class="form-control {{ $errors->has('c_action') ? 'is-invalid' : '' }}" name="c_action" id="c_action">
                    <option value disabled {{ old('c_action', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\AbandonedCall::C_ACTION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('c_action', 'Not Called') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('c_action'))
                    <div class="invalid-feedback">
                        {{ $errors->first('c_action') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.abandonedCall.fields.c_action_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="c_reason">{{ trans('cruds.abandonedCall.fields.c_reason') }}</label>
                <textarea class="form-control {{ $errors->has('c_reason') ? 'is-invalid' : '' }}" name="c_reason" id="c_reason">{{ old('c_reason') }}</textarea>
                @if($errors->has('c_reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('c_reason') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.abandonedCall.fields.c_reason_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection