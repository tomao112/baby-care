<?php

namespace App\Http\Requests\GrowthRecord;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'record_date' => 'required|date',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'memo' => 'nullable|string'
        ];
    }
}
