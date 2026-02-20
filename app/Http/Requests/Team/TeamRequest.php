<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'members' => 'nullable|array',
            'members.*.id' => 'string',
            'members.*.name' => 'string',
            'members.*.email' => 'string',
            'members.*.avatar' => 'string',
            'members.*.role' => 'string',
            'members.*.department' => 'string',
            'members.*.status' => 'string',
            'members.*.tasksAssigned' => 'integer',
            'members.*.tasksCompleted' => 'integer',
            'color' => 'nullable|string',
        ];
    }
}
