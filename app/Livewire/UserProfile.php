<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserProfile extends Component
{
    // The User model instance.
    public User $user;

    // Form properties.
    public $name;
    public $email;
    public $phone;
    public $bio;

    public $current_password;
    public $new_password;
    public $confirm_password;

    // State management for the UI.
    public bool $isEditing = false;
    public bool $showSuccessIndicator = false;

    // Validation rules.
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',

            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'nullable|max:13',
            'bio' => 'nullable|string|max:500',
        ];
    }


    public function mount()
    {
        $this->user = Auth::user();
        $this->resetForm();
    }


    public function edit()
    {
        $this->phone = $this->user->phone;
        $this->isEditing = true;
    }


    public function cancel()
    {
        $this->isEditing = false;
        $this->resetForm();
    }


    public function save()
    {
        $validatedData = $this->validate();
        $this->user->update($validatedData);
        $this->isEditing = false;
        $this->showSuccessIndicator = true;
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        $user = User::find(Auth::id());

        // 2. Check if current password matches
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Your current password is incorrect.');
            return;
        }

        // 3. Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // 4. Reset fields
        $this->reset(['current_password', 'new_password', 'confirm_password']);

        // 5. Show success message
        session()->flash('success', 'Your password has been changed successfully!');
    }

    /**
     * Helper method to reset form properties from the model.
     */
    private function resetForm()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->bio = $this->user->bio;
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
