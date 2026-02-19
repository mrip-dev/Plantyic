<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class MyFavoritesComponent extends Component
{

    public $vehicles;

    // Listen for the event emitted by the button to refresh the list
    protected $listeners = ['favoritesUpdated' => 'loadFavorites'];

    public function mount()
    {
        $this->loadFavorites();
    }

    public function loadFavorites()
    {
        $favoriteIds = [];
        if (Auth::check()) {
            // Logged-in user: get IDs from the database relationship
            $favoriteIds = Auth::user()->favoriteVehicles()->pluck('vehicle_id')->toArray();
        } else {
            // Guest: get IDs from the session
            $favoriteIds = session()->get('favorites', []);
        }


        if (!empty($favoriteIds)) {
            // Fetch all vehicle models for the given IDs
            $this->vehicles = Vehicle::whereIn('id', $favoriteIds)->get();

        } else {
            $this->vehicles = collect(); // Ensure it's always a collection
        }
    }
    public function render()
    {

        return view('livewire.my-favorites-component', [
            'isGuest' => !Auth::check()
        ]);
    }
}
