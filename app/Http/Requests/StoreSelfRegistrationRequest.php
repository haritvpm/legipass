<?php

namespace App\Http\Requests;

use App\Models\SelfRegistration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSelfRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('self_registration_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'gender' => [
                'required',
            ],
            'age' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'mobile' => [
                'string',
                'min:10',
                'required',
            ],
            'id_detail' => [
                'string',
                'nullable',
            ],
            'state' => [
                'string',
                'nullable',
            ],
            'pincode' => [
                'string',
                'required',
            ],
            'purpose' => [
                'required',
            ],
            'date_of_visit' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],

            'district' => [
                'string',
                'nullable',
            ],
            'post_office' => [
                'string',
                'nullable',
            ],
        ];
    }
}
