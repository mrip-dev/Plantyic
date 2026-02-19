<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;

class GlobalSearch extends Component
{
    public $query = '';
    public $vehicles = [];
    public $isActive = false; // Property to control visibility

    // This is the core search logic
    public function updatedQuery()
    {
        if (strlen($this->query) > 0) {
            $this->vehicles = Vehicle::where('status','!=','sold')->where('title', 'like', '%' . $this->query . '%')->take(20)->get();
        } else {
            $this->vehicles = [];
        }
    }
    
    // Method to show the search bar
    public function showSearch()
    {
        $this->isActive = true;
    }

    // Method to hide and reset the search bar
    public function hideSearch()
    {
        $this->isActive = false;
        $this->reset(['query', 'vehicles']); // Reset search results and query
    }

    public function selectVehicle($vehicleId)
    {
        return redirect()->to('/car-detail/' . $vehicleId);
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}