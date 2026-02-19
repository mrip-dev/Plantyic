<?php

namespace App\Livewire;

use App\Enums\MileageRange;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\User;
use App\Models\VehicleEnquiry;
use App\Notifications\AccountCreatedConfirmation;
use App\Notifications\VehicleEnquiryNotification;
use App\Notifications\VehicleEnquiryReceivedConfirmation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SellCarHomeComponent extends Component
{
    public int $step = 1;
    public bool $formSubmitted = false;

    public array $formData = [
        'brand_id' => null,
        'make_id' => null,
        'year' => null,
        'mileage' => '',
        'specification' => '',
        'notes' => '',
        'name' => '',
        'phone' => '',
        'email' => '',
    ];

    public string $brandSearch = '';
    public string $modelSearch = '';

    public $brands = [];
    public $models = [];
    public $years = [];
    public $featuredBrands = [];

    // Friendly attribute names for cleaner error messages
    protected $validationAttributes = [
        'formData.brand_id' => 'make',
        'formData.make_id' => 'model',
        'formData.year' => 'year',
        'formData.mileage' => 'mileage',
        'formData.specification' => 'specification',
        'formData.notes' => 'additional notes',
        'formData.name' => 'full name',
        'formData.phone' => 'contact number',
        'formData.email' => 'email',
    ];

    public function messages()
    {
        return [
            'formData.phone.required' => 'The phone number is required.',
            'formData.phone.regex' => 'Please enter a valid UAE mobile number, e.g., +9715XXXXXXXX or 05XXXXXXXX.',
        ];
    }

    // Master rules (used on final submit)
    protected function rules()
    {
        $mileageKeys = array_keys(MileageRange::options());
        $currentYear = now()->year;
        $minYear = 1980;

        return [
            'formData.brand_id' => ['required', 'exists:brands,id'],
            'formData.make_id' => ['required', 'exists:vehicle_models,id'],
            'formData.year' => ['required', 'integer', "between:$minYear,$currentYear"],
            'formData.mileage' => ['required', Rule::in($mileageKeys)],
            'formData.specification' => ['nullable', 'string', 'max:255'],
            'formData.notes' => ['nullable', 'string', 'max:1000'],

            'formData.name' => ['required', 'string', 'max:255'],
            'formData.phone' => [
                'required',

            ],
            'formData.email' => ['required', 'email', 'max:255'],
        ];
    }

    // Rules needed for specific steps
    private function rulesForStep(int $step): array
    {
        $mileageKeys = array_keys(MileageRange::options());
        $currentYear = now()->year;
        $minYear = 1980;

        switch ($step) {
            case 1: // Featured brand selection
            case 2: // Brand search & select
                return [
                    'formData.brand_id' => ['required', 'exists:brands,id'],
                ];
            case 3: // Model select
                return [
                    'formData.make_id' => ['required', 'exists:vehicle_models,id'],
                ];
            case 4: // Year select
                return [
                    'formData.year' => ['required', 'integer', "between:$minYear,$currentYear"],
                ];
            case 5: // Vehicle details
                return [
                    'formData.mileage' => ['required', Rule::in($mileageKeys)],
                    'formData.specification' => ['nullable', 'string', 'max:255'],
                    'formData.notes' => ['nullable', 'string', 'max:1000'],
                ];
            case 6: // Personal details
                return [
                    'formData.name' => ['required', 'string', 'max:255'],
                    'formData.phone' => [
                        'required',

                    ],
                    'formData.email' => ['required', 'email', 'max:255'],
                ];
            default:
                return [];
        }
    }

    private function validateStep(int $step): void
    {
        $this->validate($this->rulesForStep($step), $this->messages(), $this->validationAttributes);
    }

    public function updated($propertyName)
    {
        // Live-validate only the fields of the current step
        if (Str::startsWith($propertyName, 'formData.')) {
            $rules = $this->rulesForStep($this->step);
            if (array_key_exists($propertyName, $rules)) {
                $this->validateOnly($propertyName, $rules, $this->messages(), $this->validationAttributes);
            }
        }
    }

    public function mount()
    {
        $this->featuredBrands = Brand::where('is_active', true)->take(12)->get();
        $this->years = getYears();
        $this->formData['mileage'] = array_key_first(MileageRange::options());
    }

    // Navigation helpers (validate on Next)
    public function next(): void
    {
        $this->validateStep($this->step);
        if ($this->step < 6) {
            $this->step++;
        }
    }

    public function back(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    // Keep goToStep but enforce validation if jumping forward
    public function goToStep($stepNumber)
    {
        $target = (int) $stepNumber;

        if ($target <= $this->step) {
            $this->step = $target;
            return;
        }

        while ($this->step < $target) {
            $this->validateStep($this->step);
            $this->step++;
        }
    }

    // --- Selectors ---

    public function selectBrand($brandId)
    {
        $this->formData['brand_id'] = $brandId;
        $this->models = VehicleModel::where('brand_id', $brandId)->get();
        $this->step = 3;
    }
    public function gotoAllMakes()
    {
        $this->step = 2;
    }
    public function updatedmodelSearch($value)
    {

        $this->models = VehicleModel::where('brand_id', $this->formData['brand_id'])->where('name', 'like', '%' . $value . '%')->get();
    }
    public function selectModel($modelId)
    {
        $this->formData['make_id'] = $modelId;
        $this->step = 4;
    }

    public function selectYear($year)
    {
        $this->formData['year'] = $year;
        $this->step = 5;
    }

    public function submit()
    {
        // Validate all fields on final submit
        $this->validate();

        try {
            $this->formData['type'] = 'sale';

            $user = null;
            // Create the main enquiry record
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                $email = $this->formData['email'] ?? null;

                if ($email) {
                    $user = User::where('email', $email)->first();

                    if (!$user) {
                        $tempPassword = Str::random(10);
                        $user = User::create([
                            'name' => $this->formData['name'] ?: 'Customer',
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
                $this->formData['user_id'] = $user->id;
            }

            $enquiry = VehicleEnquiry::create($this->formData);

            $recipients = User::role(['admin', 'super-admin'])->get();
            Notification::send($recipients, new VehicleEnquiryNotification($enquiry));

            if ($user) {
                Notification::send($user, new VehicleEnquiryReceivedConfirmation($enquiry));
            }

            $this->formSubmitted = true;
        } catch (\Exception $e) {
            // Log error if needed: \Log::error($e);
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        $this->brands = Brand::whereHas('models')
            ->where('name', 'like', '%' . $this->brandSearch . '%')
            ->get();
        return view('livewire.sell-car-home-component');
    }
}
