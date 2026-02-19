<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;

class VehicleListingComponent extends Component
{

    public $vehicles = [];
    public $section = 'Vehicles';
    public $title= 'Featured Vehicles';
    public $type = 'featured';

    public function mount($section, $type = 'featured')
    {
        $this->section = $section;
        $this->type = $type;
    }

    public function render()
    {

        $query = Vehicle::query();
        if ($this->section == 'Auctions') {
            $this->title = 'Auction Vehicles';
            $query->where('is_auction', 1)
                ->with('latestBid', 'bids'); // Eager load only for auctions
        } else {

            $query->where(function ($q) {
                $q->where('is_auction', 0)
                    ->orWhereNull('is_auction');
            });
            if ($this->type == 'featured') {
                $this->title = 'Featured Vehicles';
                $query->where('is_featured', 1);
                $query->where('status','!=' ,'sold');
            } elseif ($this->type == 'sold') {
                $this->title = 'Sold Vehicles';
                $query->where('status', 'sold');
            }
        }
        $this->vehicles = $query->get();

        return view('livewire.vehicle-listing-component');
    }
}
