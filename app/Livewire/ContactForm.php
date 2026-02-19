<?php

namespace App\Livewire;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\User;
use App\Notifications\EnquiryReceivedConfirmation;
use App\Notifications\EnquirySubmitNotification;
use Illuminate\Support\Facades\Notification;

class ContactForm extends Component
{

    #[Rule('required|min:2', as: 'first name')]
    public $firstName = '';

    #[Rule('required|min:2', as: 'last name')]
    public $lastName = '';

    #[Rule('required|email')]
    public $email = '';

    #[Rule(['required'])]
    public $phone = '';

    #[Rule('required|min:10')]
    public $message = '';

    #[Rule('accepted', as: 'terms of service')]
    public $terms = true;

    public bool $formSubmitted = false;

    public function messages()
    {
        return [
            'phone.required' => 'Number is required.',
            'phone.regex' => 'Please enter a valid Dubai mobile number starting with +9715.',
        ];
    }


    public function submit()
    {

        $validatedData = $this->validate();

        try {
            // 2. Create the database record
            $enquiry = ContactSubmission::create([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'phone' => $this->phone,
                'message' => $this->message,
                'terms_agreed' => $this->terms,
            ]);
            // Notify all admins and super-admins
            $recipients = User::role(['admin', 'super-admin'])->get();
            Notification::send($recipients, new EnquirySubmitNotification($enquiry));
            Notification::route('mail', $enquiry->email)
                ->notify(new EnquiryReceivedConfirmation($enquiry));
            session()->flash('success', 'Thank you! Your message has been received and saved.');
            $this->formSubmitted = true;

            $this->terms = true;
        } catch (\Exception $e) {
            // \Log::error('Contact form submission error: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
        }
    }
    public function resetForm()
    {
        $this->reset(); // Resets all public properties
        $this->formSubmitted = false; // Set the switch back to false
        $this->terms = true; // Re-check the terms checkbox if needed
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
