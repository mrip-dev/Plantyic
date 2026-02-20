<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'tasks' => 'nullable|integer',
            'completed' => 'nullable|integer',
            'members' => 'nullable|array',
            'members.*' => 'string',
            'status' => 'required|string',
            'dueDate' => 'nullable|date',
            'createdAt' => 'nullable|date',
        ];
    }
}
