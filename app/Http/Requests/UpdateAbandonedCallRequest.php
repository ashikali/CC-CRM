<?php

namespace App\Http\Requests;

use App\Models\AbandonedCall;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAbandonedCallRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('abandoned_call_edit');
    }

    public function rules()
    {
        return [];
    }
}
