<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FavoriteButtonDetailComponent extends Component
{

    public int $vehicleId;
    public bool $isFavorited;

    public function mount($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $this->checkIfFavorited();
    }

    public function toggleFavorite()
    {
        if (Auth::check()) {
            Auth::user()->favoriteVehicles()->toggle($this->vehicleId);
        } else {
            // User is a guest, use the session
            $favorites = session()->get('favorites', []);

            if (in_array($this->vehicleId, $favorites)) {
                // Remove from favorites
                $favorites = array_diff($favorites, [$this->vehicleId]);
            } else {
                // Add to favorites
                $favorites[] = $this->vehicleId;
            }
            session(['favorites' => $favorites]);
        }

        // After toggling, refresh the component's state
        $this->checkIfFavorited();

        // Emit an event that the favorites list might have changed
        $this->dispatch('favoritesUpdated');
        $this->dispatch('success-notification', message: 'Favorited Successfully');
    }

    /**
     * Helper method to update the component's state.
     */
    public function checkIfFavorited()
    {
        if (Auth::check()) {
            // Check the database for the authenticated user
            $this->isFavorited = Auth::user()->favoriteVehicles()->where('vehicle_id', $this->vehicleId)->exists();
        } else {
            // Check the session for the guest user
            $favorites = session()->get('favorites', []);
            $this->isFavorited = in_array($this->vehicleId, $favorites);
        }
    }
    public function render()
    {
        return view('livewire.favorite-button-detail-component');
    }
}
