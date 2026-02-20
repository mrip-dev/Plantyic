<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'read' => 'boolean',
            'createdAt' => 'nullable|date',
            'link' => 'nullable|string',
            'actor' => 'nullable|array',
            'actor.name' => 'nullable|string',
            'actor.avatar' => 'nullable|string',
        ];
    }
}
