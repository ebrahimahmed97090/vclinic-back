<?php

namespace App\Http\Requests;

use App\Models\DoctorAppointment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDoctorAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doctor_appointment_edit');
    }

    public function rules()
    {
        return [
            'day' => [
                'string',
                'required',
            ],
            'time_from' => [
                'required',
                'date_format:' . config('panel.time_format'),
            ],
            'time_to' => [
                'required',
                'date_format:' . config('panel.time_format'),
            ],
            'status' => [
                'required',
            ],
            'doctor_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
