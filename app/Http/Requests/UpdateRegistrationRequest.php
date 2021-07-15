<?php

namespace App\Http\Requests;

use App\Models\Registration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('registration_edit');
    }

    public function rules()
    {
        return [
            'patient_id' => [
                'required',
                'integer',
            ],
            'doctor_id' => [
                'required',
                'integer',
            ],
            'zoom_link' => [
                'string',
                'required',
            ],
            'zoom_data' => [
                'string',
                'nullable',
            ],
            'date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
