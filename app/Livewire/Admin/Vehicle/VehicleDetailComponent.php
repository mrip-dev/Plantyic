<?php

namespace App\Livewire\Admin\Vehicle;

use App\Models\Vehicle;
use Livewire\Component;

class VehicleDetailComponent extends Component
{
    public Vehicle $vehicle;

    public $showForm = false;
    public $interiorFeatures;
    public $exteriorFeatures;
    public $tags;

    protected $listeners = ['vehicleSaved' => '$refresh', 'cancelForm' => 'cancel'];

    public function cancel()
    {
        $this->showForm = false;
    }
    public function editVehicle($id)
    {
        $this->showForm = true;
        $this->dispatch('showEditForm', $id);
    }
    public function mount($vehicleId)
    {
        // Eager load all relationships for performance
        $this->vehicle = Vehicle::with('features')->find($vehicleId)->load([
            'brand',
            'vehicleModel',
            'bodyType',
            'fuelType',
            'transmission'
        ]);

        $this->exteriorFeatures = $this->vehicle->features->where('type', 'exterior');
        $this->interiorFeatures = $this->vehicle->features->where('type', 'interior');
        $this->tags = $this->vehicle->features->where('type', 'tag');
    }

    public function render()
    {
        // This makes it a "Full Page" component, rendering within your main layout.
        return view('livewire.admin.vehicle.vehicle-detail-component');
    }
}
