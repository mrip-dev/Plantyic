<?php

namespace App\Livewire\Admin\Vehicle;

use App\Models\Vehicle;
use App\Models\VehicleImage; // Make sure you have this model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleStatusComponent extends Component
{
    use WithFileUploads;

    public Vehicle $vehicle;
    public array $vehicleData = [];


    public function mount($vehicleId)
    {

        $this->vehicle =  Vehicle::findOrFail($vehicleId);
        $this->vehicleData = $this->vehicle->toArray();
    }
    public function setSingleSelection(string $property, $value)
    {
        $currentValue = $this->vehicleData[$property] ?? null;
        $this->vehicleData[$property] = ($currentValue == $value) ? null : $value;
    }
   public function saveVehicle()
    {
        

        try {
            DB::transaction(function () {
                $this->vehicle->update($this->vehicleData);
            });

            $this->dispatch('success-notification', message: 'Vehicle updated successfully');
        } catch (\Exception $e) {
            $this->dispatch('error-notification', message: 'Failed to update vehicle: ' . $e->getMessage());
        }
    }



    public function render()
    {
        return view('livewire.admin.vehicle.vehicle-status-component');
    }
}
