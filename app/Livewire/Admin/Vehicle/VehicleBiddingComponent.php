<?php

namespace App\Livewire\Admin\Vehicle;

use App\Models\Vehicle;
use App\Models\VehicleBid;
use Livewire\Component;

class VehicleBiddingComponent extends Component
{
    public $vehicleId;
    public $latestBid;

    public $bids=[];

    public function mount($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $vehicle=Vehicle::find($this->vehicleId);
        $this->bids=$vehicle->bids;
        $this->latestBid=$vehicle->latestBid?->bid_amount ?? 0;
    }
    public function render()
    {
        return view('livewire.admin.vehicle.vehicle-bidding-component');
    }
}
