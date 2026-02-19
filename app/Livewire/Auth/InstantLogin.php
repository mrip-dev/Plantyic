<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InstantLogin extends Component
{
    public $email;
    public $password;
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
           
        )) {
            session()->regenerate();
            $this->dispatch('close-login-modal');
           $this->js('setTimeout(() => window.location.reload())');
        }

        session()->flash('error', 'Invalid email or password.');
    }



    public function render()
    {
        return view('livewire.auth.instant-login');
    }
}
