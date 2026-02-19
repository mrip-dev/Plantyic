<?php

namespace App\Livewire\Admin\Vehicle;

use App\Models\VehicleDocument;
use Livewire\Attributes\On;
use Livewire\Component;

class VehicleDocumentsComponent extends Component
{
    public $vehicleId;

    public $documents=[];

    public function mount($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $this->documents=VehicleDocument::where('vehicle_id',$this->vehicleId)->get();
    }


    public function render()
    {
        return view('livewire.admin.vehicle.vehicle-documents-component');
    }
}
