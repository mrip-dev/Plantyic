<?php

namespace App\Http\Requests\Goals;

use Illuminate\Foundation\Http\FormRequest;

class GoalRequest extends FormRequest
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
            'progress' => 'required|integer|min:0|max:100',
            'target' => 'nullable|string',
            'category' => 'nullable|string',
            'dueDate' => 'nullable|date',
            'milestones' => 'nullable|array',
            'milestones.*.id' => 'string',
            'milestones.*.label' => 'string',
            'milestones.*.done' => 'boolean',
        ];
    }
}
