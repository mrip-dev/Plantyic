<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'theme' => 'required|string',
            'primaryColor' => 'required|string',
            'fontFamily' => 'required|string',
            'displayFont' => 'nullable|string',
            'borderRadius' => 'nullable|string',
            'sidebarCompact' => 'nullable|boolean',
            'animationsEnabled' => 'nullable|boolean',
            'fontSize' => 'nullable|string',
            'language' => 'nullable|string',
            'dateFormat' => 'nullable|string',
            'timeFormat' => 'nullable|string',
            'weekStartsOn' => 'nullable|string',
            'emailNotifications' => 'nullable|boolean',
            'pushNotifications' => 'nullable|boolean',
            'taskReminders' => 'nullable|boolean',
            'weeklyDigest' => 'nullable|boolean',
            'teamUpdates' => 'nullable|boolean',
            'soundEnabled' => 'nullable|boolean',
            'sidebarOnRight' => 'nullable|boolean',
            'showBottomBar' => 'nullable|boolean',
        ];
    }
}
