<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\VehicleEnquiry;
use App\Models\Vehicle;
use App\Notifications\AccountCreatedConfirmation;
use App\Notifications\VehicleEnquiryReceivedConfirmation;
use App\Notifications\EnquirySubmitNotification;
use App\Notifications\VehicleEnquiryNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Rule;
use Illuminate\Support\Str;

class BuyCarComponent extends Component
{
    public $selected_vehicle;
    public $vehicleId;
    public $is_not_login = true;
    public $name;
    #[Rule(['required', 'min:13', 'max:13'])]
    public $phone = '';

    public $email;
    public $address;
    public $user_id;

    public $is_auction = 0;
    public function messages()
    {
        return [
            'phone.required' => 'Number is required.',
            'phone.regex' => 'Please enter a valid Dubai mobile number starting with +9715.',
        ];
    }
    protected $rules = [
        'name' => 'required',
        'phone' => 'required',
        'email' => 'nullable',
        'address' => 'nullable',
    ];
    public bool $formSubmitted = false;
    public function mount($selected_vehicle, $is_auction = 0)
    {
        $this->selected_vehicle = $selected_vehicle;
        $this->is_auction = $is_auction;
        if (Auth::check()) {
            $this->is_not_login = false;
        }
    }
    public function saveBuyEnquiry()
    {

        $this->validate();
        $user = null;
        // Create the main enquiry record
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            $email = $this->email ?? null;

            if ($email) {
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $tempPassword = Str::random(10);
                    $user = User::create([
                        'name' => $this->name ?: 'Customer',
                        'email' => $email,
                        'role' => 'customer',
                        'password' => Hash::make($tempPassword),
                    ]);
                    Notification::send($user, new AccountCreatedConfirmation($user, $tempPassword));
                    $user->syncRoles('customer');
                }
            }
        }

        if ($user) {
            $this->user_id = $user->id;
        }

        $enquiry = VehicleEnquiry::create([
            'name'       => $this->name,
            'phone'      => $this->phone,
            'email'      => $this->email,
            'address'    => $this->address,
            'user_id'    => $this->user_id,
            'type'    => 'purchase',
            'vehicle_id' => $this->selected_vehicle->id,

        ]);
        $recipients = User::role(['admin', 'super-admin'])->get();
        Notification::send($recipients, new VehicleEnquiryNotification($enquiry));

        if ($user) {
            Notification::send($user, new VehicleEnquiryReceivedConfirmation($enquiry));
        }

        session()->flash('success', 'Thank you! Your message has been received and saved.');
        $this->formSubmitted = true;
        $this->dispatch('closeBuyNowModal');
        session()->flash('message', 'Your inquiry has been submitted successfully!');


        $this->reset(['name', 'phone', 'email', 'address']);
    }

    public function render()
    {
        return view('livewire.buy-car-component');
    }
}
