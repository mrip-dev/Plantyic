<?php

namespace App\Livewire\Admin;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\FuelType;
use App\Models\Transmission;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Models\Feature;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class VehicleFormComponent extends Component
{
    use WithPagination;
    use WithFileUploads;

    // --- Component State ---
    public $showForm = false;
    public $isEditing = false;
    public $currentStep = 1;
    public $search = '';
    public $vehicle_id = null;
    public $years = [];

    // --- Form Data Holder ---
    public array $vehicleData = [];

    // --- Data for Dropdowns & Selections ---
    public $brands = [], $models = [], $bodyTypes = [], $fuelTypes = [], $transmissions = [];

    protected $listeners = ['showCreateForm', 'showEditForm', 'addNewSelectOption' => 'handleAddNewSelectOption'];
    public $allFeatures = [];
    public $selectedFeatures = [];
    public $exteriorFeatures = [];
    public $tags = [];
    public $interiorFeatures = [];

    public $images = [];
    public $existingImages = [];




    // All rules point to the keys in the vehicleData array.
    protected function rules()
    {
        return [
            'vehicleData.title' => 'required',
            'vehicleData.brand_id' => 'required',
            'vehicleData.vehicle_model_id' => 'required',
            'vehicleData.year' => 'required',
            'vehicleData.price' => 'required',
            'vehicleData.mileage' => 'required',
            'vehicleData.transmission_id' => 'required',
            'vehicleData.fuel_type_id' => 'required',
            'vehicleData.body_type_id' => 'required',
            'vehicleData.condition' => 'required',
            'vehicleData.status' => 'required',
            'vehicleData.description' => 'nullable',
            'vehicleData.variant' => 'nullable',
            'vehicleData.engine_cc' => 'nullable',
            'vehicleData.horsepower' => 'nullable',
            'vehicleData.torque' => 'nullable',
            'vehicleData.seats' => 'nullable',
            'vehicleData.doors' => 'nullable',
            'vehicleData.color' => 'nullable',
            'vehicleData.interior_color' => 'nullable',
            'vehicleData.drive_type' => 'nullable',
            'vehicleData.vin' => 'nullable',
            'vehicleData.registration_no' => 'nullable',
            'vehicleData.negotiable' => 'boolean',
            'vehicleData.is_featured' => 'boolean',
            'vehicleData.is_auction' => 'boolean',
        ];
    }

    // This method runs once, when the component is first mounted.
    public function mount()
    {
        // Initialize the form array with default values from a blank model instance
        $this->vehicleData = (new Vehicle())->getAttributes();
        $this->vehicleData['negotiable'] = false;
        $this->vehicleData['is_featured'] = false;
        $this->vehicleData['is_auction'] = false;
        $this->vehicleData['condition'] = 'used';
        $this->vehicleData['status'] = 'draft';


        // Pre-load data for dropdowns and selections


        $this->brands = Brand::orderBy('name')->whereHas('models')->get();
        $this->bodyTypes = BodyType::all();
        $this->fuelTypes = FuelType::all();
        $this->transmissions = Transmission::all();
        $this->allFeatures = Feature::where('type', 'simple')->get();
        $this->exteriorFeatures = Feature::where('type', 'exterior')->get();
        $this->interiorFeatures = Feature::where('type', 'interior')->get();
        $this->tags = Feature::where('type', 'tag')->get();
        $this->years = getYears();
    }

    // The hook now works reliably with the array.
    public function updatedVehicleDataBrandId($value)
    {
        $this->vehicleData['vehicle_model_id'] = null; // Reset vehicle_model_id in our data array
        if ($value) {
            $this->models = VehicleModel::where('brand_id', $value)->get();
        } else {
            $this->models = [];
        }
    }

    // --- WIZARD CONTROLS ---
    public function nextStep()
    {
        $this->validateStep($this->currentStep);

        if ($this->currentStep < 3) {
            $this->currentStep++;
        } elseif ($this->currentStep == 3 || $this->currentStep == 4) {
            $this->saveVehicle();
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) $this->currentStep--;
    }

    public function showCreateForm()
    {
        $this->isEditing = false;
        $this->reset();
        $this->mount();
        $this->currentStep = 1;
        $this->showForm = true;
    }

    public function showEditForm($vehicleId)
    {

        $this->isEditing = true;
        $vehicle = Vehicle::findOrFail($vehicleId);
        $this->vehicle_id = $vehicleId;
        $this->vehicleData = $vehicle->toArray();


        if ($this->vehicleData['brand_id']) {
            $this->models = VehicleModel::where('brand_id', $this->vehicleData['brand_id'])->get();
        }
        $this->currentStep = 1;
        $this->showForm = true;
        $this->selectedFeatures = $vehicle->features->pluck('id')->toArray();
        $this->existingImages = $vehicle->images?->map(function ($image) {
            return [
                'name' => basename($image->path),
                'url' => asset('storage/' . $image->path)
            ];
        })->all();
    }


    public function saveVehicle()
    {

        $this->validate();
        if (!$this->isEditing) {
            $this->vehicleData['slug'] = Str::slug($this->vehicleData['title']);
        }
        $vehicle = Vehicle::updateOrCreate(['id' => $this->vehicle_id], $this->vehicleData);
        $vehicle->features()->sync($this->selectedFeatures);
        $this->vehicle_id = $vehicle->id;
        if (!empty($this->images)) {
            foreach ($this->images as $imageFile) {
                $path = $imageFile->store('vehicle_images', 'public');
                $vehicle->images()->create(['path' => $path]);
            }
        }
        if ($this->currentStep == 3) {
            $this->currentStep++;
        } else {
            $this->dispatch('success-notification', message: $this->isEditing ? 'Vehicle updated successfully.' : 'Vehicle created successfully.');
            $this->dispatch('vehicleSaved');
            $this->cancel();
        }
    }

    public function cancel()
    {

        $this->dispatch('cancelForm');
        $this->showForm = false;
        $this->reset();
        $this->mount();
    }

    public function delete($id)
    {
        Vehicle::findOrFail($id)->delete();

        $this->dispatch('success-notification', message: 'Vehicle deleted successfully.');
    }

    public function setSingleSelection(string $property, $value)
    {
        $currentValue = $this->vehicleData[$property] ?? null;
        $this->vehicleData[$property] = ($currentValue == $value) ? null : $value;

    }

    // Helper method to validate rules for a specific step.
    private function validateStep(int $step)
    {
        $rulesForStep = [];
        if ($step === 1) {
            $rulesForStep = [
                'vehicleData.title' => 'required|string',
                'vehicleData.brand_id' => 'required',
                'vehicleData.vehicle_model_id' => 'required',
                'vehicleData.year' => 'required',
                'vehicleData.price' => 'required',
                'vehicleData.mileage' => 'required',
                'vehicleData.engine_type' => 'nullable',
            ];
        } elseif ($step === 2) {
            $rulesForStep = [

                'vehicleData.transmission_id' => 'required',
                'vehicleData.fuel_type_id' => 'required',
                'vehicleData.body_type_id' => 'required',
            ];
        } elseif ($step === 3) {
            $rulesForStep = [
                'vehicleData.condition' => 'nullable',
                'vehicleData.status' => 'nullable',
            ];
        } elseif ($step === 4) {
            $rulesForStep = [
                'vehicleData.condition' => 'nullable',
            ];
        }

        $this->validate($rulesForStep);
    }
    public function handleAddNewSelectOption($text, $model, $list)
    {

        $newItemText = trim($text);
        if ($list === 'brands' && !empty($newItemText)) {
            $newItem = Brand::create([
                'name' => $newItemText,
            ]);
        } else if ($list === 'models' && !empty($newItemText)) {
            $brandId = data_get($this, 'vehicleData.brand_id');
            if (!$brandId) {
                session()->flash('error', 'Please select a brand first.');
                return;
            }
            $newItem = VehicleModel::create([
                'name' => $newItemText,
                'brand_id' => $brandId,
            ]);
        } else {
            $newItem = collect();
        }
        if ($newItem !== null && $newItem->id) {
            data_set($this, $model, $newItem->id);
            $array = data_get($this, $list, []);
            $array[] = ['id' => $newItem->id, 'text' => $newItem->name];
            data_set($this, $list, $array);
        }
        $this->dispatch('re-init-select-2-component');
    }


    public function render()
    {
        $vehicles = Vehicle::where('title', 'like', '%' . $this->search . '%')
            ->orWhere('vin', 'like', '%' . $this->search . '%')
            ->with('brand', 'vehicleModel')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.vehicle-form-component', compact('vehicles'));
    }
}
