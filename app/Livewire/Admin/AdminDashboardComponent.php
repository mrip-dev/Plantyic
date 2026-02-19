<?php

namespace App\Livewire\Admin;

use App\Models\InspectionEnquiry;
use App\Models\PurchaseEnquiry;
use App\Models\Vehicle;
use App\Models\VehicleEnquiry;
use Livewire\Component;

class AdminDashboardComponent extends Component
{
     public $vehicleCount;
    public $inspectionCount;
    public $auctionCount;
    public $listingCount;
    public $soldVehicleCount;
    public $purchaseenquiryCount;
    public $sellenquiryCount;

    public function mount()
    {
        $this->vehicleCount = Vehicle::count();
        $this->inspectionCount = InspectionEnquiry::count();
        $this->listingCount =  Vehicle::where('is_auction', false)->where('status', '!=', 'sold')->count();
        $this->auctionCount = Vehicle::where('is_auction', '1')->where('status','!=', 'sold')->count();
        $this->soldVehicleCount = Vehicle::where('status', 'sold')->count();
        $this->purchaseenquiryCount = VehicleEnquiry::where('type','purchase')->count();
        $this->sellenquiryCount = VehicleEnquiry::where('type','sale')->count();
        
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard-component');
    }
}
