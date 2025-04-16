<?php

namespace App\Http\Api\Children\Record\Daily\Request;

use Illuminate\Foundation\Http\FormRequest;

class AddDailyRecordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'child_id' => 'required|exists:children,id',
            'daily_record_stamps_date' => 'required|date',
            'record_date' => 'required|date',
            'comment' => 'nullable|string|max:20',
            'memo' => 'nullable|string|max:500',
        ];
    }
}
