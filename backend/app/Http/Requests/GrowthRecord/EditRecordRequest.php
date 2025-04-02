<?php

namespace App\Http\Requests\GrowthRecord;

use Illuminate\Foundation\Http\FormRequest;

class EditRecordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'child_id' => 'required|exists:children,id',
            'record_date' => 'required|date',  // 名前がrecord_dateであることを確認
            'height' => 'nullable|numeric|min:0|max:200',  // 必須から任意に変更
            'weight' => 'nullable|numeric|min:0|max:100',  // 必須から任意に変更
            'memo' => 'nullable|string|max:500',
        ];
    }
}
