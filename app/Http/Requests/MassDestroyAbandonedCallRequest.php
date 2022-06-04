<?php

namespace App\Http\Requests;

use App\Models\AbandonedCall;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAbandonedCallRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('abandoned_call_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:abandoned_calls,id',
        ];
    }
}
