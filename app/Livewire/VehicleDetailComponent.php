<?php

namespace App\Livewire;

use App\Models\Feature;
use Livewire\Component;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class VehicleDetailComponent extends Component
{
    public $selected_vehicle;
    public $allinteriorFeatures;
    public $allexteriorFeatures;
    public $interiorFeatures;
    public $exteriorFeatures;
    public $tags;
    public $mainImage;

    public function mount($id)
    {
        $this->selected_vehicle = Vehicle::with('features', 'images')->findOrFail($id);

        $this->allexteriorFeatures =  Feature::where('type', 'exterior')->get();
        $this->allinteriorFeatures =  Feature::where('type', 'interior')->get();
        $this->exteriorFeatures = $this->selected_vehicle->features->where('type', 'exterior')->pluck('name')->toArray();
        $this->interiorFeatures = $this->selected_vehicle->features->where('type', 'interior')->pluck('name')->toArray();
        $this->tags = $this->selected_vehicle->features->where('type', 'tag');
        $this->mainImage = $this->selected_vehicle->coverImage->path ?? null;
    }


    public function render()
    {
        return view('livewire.vehicle-detail-component');
    }
}
