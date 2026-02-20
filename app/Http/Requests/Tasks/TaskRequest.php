<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time' => 'nullable|string',
            'duration' => 'nullable|string',
            'priority' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'status' => 'required|string',
            'date' => 'required|date',
            'assignee' => 'nullable|string',
            'project' => 'nullable|string',
        ];
    }
}
