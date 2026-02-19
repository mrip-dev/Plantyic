<?php

namespace App\Livewire\Admin\Vehicle;

use Livewire\Component;

class VehicleMaintenanceComponent extends Component
{
    public $vehicleId;

    public function mount($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }
    public function render()
    {
        return view('livewire.admin.vehicle.vehicle-maintenance-component');
    }
}
