<?php

namespace App\Http\Requests;

use App\Models\Contact;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateContactRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('contact_edit');
    }

    public function rules()
    {
        return [

            'telefono' => [
                'numeric',
                'nullable',
                'unique:contact,telefono,' . request()->route('contact')->id,
            ]

        ];
    }
}
