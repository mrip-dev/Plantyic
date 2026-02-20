<?php

namespace App\Http\Requests\Notes;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'pinned' => 'boolean',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            'folder' => 'nullable|string',
        ];
    }
}
