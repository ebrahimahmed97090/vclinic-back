<?php

namespace App\Http\Requests;

use App\Models\Config;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConfigRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('config_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:configs,title,' . request()->route('config')->id,
            ],
            'details' => [
                'string',
                'nullable',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
