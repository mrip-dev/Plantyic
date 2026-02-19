<?php

namespace App\Livewire\Admin\Vehicle;

use App\Models\Vehicle;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class VehicleHistoryComponent extends Component
{

    use WithFileUploads;

    public Vehicle $vehicle;

    public $uploads = [];

    public function mount($vehicleId)
    {
        $this->vehicle = Vehicle::findOrFail($vehicleId);
    }

    public function save()
    {
        $this->validate([
            'uploads.*' => 'image', // 2MB Max per image
        ]);

        foreach ($this->uploads as $photo) {
            $path = $photo->store('vehicles', 'public');

            // Assuming you have an Image model with 'vehicle_id' and 'path' columns
            $this->vehicle->images()->create(['path' => $path]);
        }

        // Reset the upload input and refresh the component
        $this->uploads = [];
        $this->vehicle->refresh();
        session()->flash('success', 'Images uploaded successfully.');
    }

    public function render()
    {
        return view('livewire.admin.vehicle.vehicle-history-component');
    }
}
