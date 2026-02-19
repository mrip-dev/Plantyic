<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\InspectionEnquiry;
use App\Models\User;
use App\Models\VehicleModel;
use App\Notifications\AccountCreatedConfirmation;
use App\Notifications\VehicleEnquiryNotification;
use App\Notifications\VehicleInspectionRecievedConfirmation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Illuminate\Support\Str;

class BookInspectionComponent extends Component
{
    public $name;
    public $phone;
    public $email;
    public $type;
    public $date;
    public $time;
    public $location;
    public $year;
    public $make;
    public $model;
    public $years=[];

    public bool $formSubmitted = false;

    protected function rules()
    {

        return [
            'name' => 'required',
            'phone'    => 'required',
            'email' => 'required',
            'type' => 'required',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'year' => 'required',
            'make' => 'required',
            'model' => 'required',



        ];
    }
    public $brands = [], $models = [];
    public function mount()
    {
        $this->years = getYears();
        $this->brands = Brand::orderBy('name')->whereHas('models')->get(['id', 'name'])->toArray();
    }
    public function updatedMake($value)
    {


        $this->models = empty($value)
            ? []
            : VehicleModel::where('brand_id', $value)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();

        $this->reset('model');
        $this->dispatch('models-updated', options: $this->models);
    }
    public function messages()
    {
        return [
            'phone.required' => 'Number is required.',
            'phone.regex' => 'Please enter a valid Dubai mobile number starting with +9715.',
        ];
    }


    public function saveInspection()
    {
        $validatedData = $this->validate();

        $user = null;
        // Create the main enquiry record
        if (auth()->check()) {
            $user = auth()->user();
        } else {

            if ($validatedData['email']) {
                $user = User::where('email', $validatedData['email'])->first();

                if (!$user) {
                    $tempPassword = Str::random(10);
                    $user = User::create([
                        'name' => $validatedData['name'] ?: 'Customer',
                        'email' => $validatedData['email'],
                        'role' => 'customer',
                        'password' => Hash::make($tempPassword),
                    ]);
                    Notification::send($user, new AccountCreatedConfirmation($user, $tempPassword));
                    $user->syncRoles('customer');
                }
            }
        }

        if ($user) {
            $validatedData['user_id'] = $user->id;
        }
        $enquiry=InspectionEnquiry::create($validatedData);
        $enquiry->type='inspection';
        $recipients = User::role(['admin', 'super-admin'])->get();
        Notification::send($recipients, new VehicleEnquiryNotification($enquiry));
        if ($user) {
            Notification::send($user, new VehicleInspectionRecievedConfirmation($enquiry));
        }
        $this->dispatch('success-notification', message: 'Record Saved Successfully');
        $this->formSubmitted = true;
    }

    public function resetForm()
    {
        $this->reset(['name', 'phone', 'email', 'type', 'date', 'time', 'location', 'year', 'make', 'model']);
        $this->formSubmitted = false; // Set the switch back to false

    }
    public function render()
    {
        return view('livewire.book-inspection-component');
    }
}
