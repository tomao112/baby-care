<?php

namespace App\Http\Requests\Children;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string',
        ];
    }
}
