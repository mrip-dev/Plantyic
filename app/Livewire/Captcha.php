<?php

namespace App\Livewire;

use Livewire\Component;

class Captcha extends Component
{
    public $captcha;
    public $input;
    public $status;
    public $isMatched = false;

    public function mount()
    {
        $this->generateCaptcha();
    }

    public function generateCaptcha()
    {
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $this->captcha = collect($characters)->random(6)->implode(' ');
        $this->status = null;
        $this->input = '';
        $this->isMatched = false;
    }

    public function checkCaptcha()
    {

        if (trim($this->input) === str_replace(' ', '', $this->captcha)) {
            $this->isMatched = true;
            $this->status = 'success';
            $this->dispatch('captcha-passed');
            $this->dispatch('captchaSolved');
        } else {
            $this->status = 'failed';
            $this->dispatch('captcha-failed');
        }


    }

    public function render()
    {
        return view('livewire.captcha');
    }
}
