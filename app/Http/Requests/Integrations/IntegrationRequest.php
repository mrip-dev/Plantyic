<?php

namespace App\Http\Requests\Integrations;

use Illuminate\Foundation\Http\FormRequest;

class IntegrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'connected' => 'boolean',
            'scopes' => 'nullable|array',
            'scopes.*' => 'string',
            'lastSync' => 'nullable|date',
        ];
    }
}
