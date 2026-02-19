<?php

namespace App\Livewire;

use App\Models\VehicleEnquiry;
use App\Models\SaleEnquiryImage;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Brand;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Notifications\AccountCreatedConfirmation;
use App\Notifications\VehicleEnquiryNotification;
use App\Notifications\VehicleEnquiryReceivedConfirmation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Rule;
use Illuminate\Support\Str;

class SellCarComponent extends Component
{
    use WithFileUploads;

    // Step 1: Car Details
    public $brand_id, $make_id, $year, $mileage, $specification, $faq, $notes, $user_id;

    // Step 2: Personal Info
    public $name, $email;

    public $phone = '';
    public function messages()
    {
        return [

            'phone.required' => 'Number is required.',
            'phone.min' => 'Dubai mobile number must be exactly 13 characters.',
            'phone.max' => 'Dubai mobile number must be exactly 13 characters.',
            'phone.regex' => 'Please enter a valid Dubai mobile number starting with +9715.',
        ];
    }
    protected $rules = [
        'name' => 'required',
        'phone' => 'required|min:13',

    ];

    // Step 3: Images
    public $images = [];
    public $years = [];

    // Helpers
    public $brands = [], $models = [];
    public bool $formSubmitted = false;
    public int $currentStep = 1;

    public function mount()
    {
        $this->brands = Brand::orderBy('name')->whereHas('models')->get();
        $this->years = getYears();
        $this->models = collect();
    }

    // This runs when the brand_id is updated
    public function updatedBrandId($value)
    {

        $this->models = empty($value)
            ? []
            : VehicleModel::where('brand_id', $value)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();

        $this->reset('make_id');
        $this->dispatch('models-updated', options: $this->models);



        $this->reset('make_id');
    }

    // Validation rules for each step
    public function getRules()
    {
        if ($this->currentStep === 1) {

            return [
                'brand_id'      => 'required|exists:brands,id',
                'make_id'       => 'required|exists:vehicle_models,id',
                'year'       => 'required',
                'mileage'       => 'required',
                'specification' => 'required',
                'faq'           => 'nullable',
                'notes'         => 'nullable',
            ];
        } elseif ($this->currentStep === 2) {
            return [
                'name'   => 'required',
                'phone' => 'required|min:13',
                'email' => 'required',
            ];
        }
        return []; // No validation needed for step 3 navigation
    }

    // Go to the next step
    public function nextStep()
    {
        $this->validate($this->getRules());
        $this->currentStep++;
    }

    // Go to the previous step
    public function previousStep()
    {
        $this->currentStep--;
    }

    // Final submission
    public function save()
    {

        $validated = $this->validate([

            'name'          => 'required',
            'phone'        => 'required',
            'email'        => 'required',
            'brand_id'      => 'required|exists:brands,id',
            'make_id'       => 'required|exists:vehicle_models,id',
            'year'       => 'required',
            'mileage'       => 'required',
            'specification' => 'required',
            'faq'           => 'nullable',
            'notes'         => 'nullable',
            // 'images'        => 'required|array|min:1|max:6',
        ]);
        $user = null;
        // Create the main enquiry record
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            // Otherwise, create/find by email
            $email = $this->email ?? null;

            if ($email) {
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $tempPassword = Str::random(10);
                    $user = User::create([
                        'name'     => $this->name ?: 'Customer',
                        'email'    => $email,
                        'role'     => 'customer',
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
            'name'          => $this->name,
            'email'          => $this->email,
            'phone'        => $this->phone,
            'brand_id'      => $this->brand_id,
            'make_id'       => $this->make_id,
            'year'       => $this->year,
            'mileage'       => $this->mileage,
            'specification' => $this->specification,
            // 'faq'           => $this->faq,
            'notes'         => $this->notes,
            'type'         => 'sale',
            'user_id'         => $this->user_id,
        ]);
        $recipients = User::role(['admin', 'super-admin'])->get();
        Notification::send($recipients, new VehicleEnquiryNotification($enquiry));

        if ($user) {
            Notification::send($user, new VehicleEnquiryReceivedConfirmation($enquiry));
        }

        // Store images and prepare data for the images table
        $imagePaths = [];
        foreach ($this->images as $index => $image) {
            // The column name will be image1, image2, etc.
            $imageColumn = 'image' . ($index + 1);
            $imagePaths[$imageColumn] = $image->store('sale-enquiries', 'public');
        }

        // Create the associated image record
        SaleEnquiryImage::create(array_merge(
            ['sale_enquiry_id' => $enquiry->id],
            $imagePaths
        ));

        // Show success message and reset
        $this->formSubmitted = true;
        $this->dispatch('reset-filepond');
    }

    public function render()
    {
        return view('livewire.sell-car-component');
    }
}
