<?php

namespace App\Livewire\Admin\Inspection;

use Livewire\Component;
use App\Models\CarDamage;

class CarDamageView extends Component
{
    public $damages = [];
    public $inspectionId;

    // These are needed to display names and colors correctly in the view
       public $damageTypes = [
        'a' => ['name' => 'Scratch',       'color' => '#FF0000'], // Vivid Red (urgent)
        'b' => ['name' => 'Multiple Scratches', 'color' => '#FF7F00'], // Bright Orange (clustered damage)
        'c' => ['name' => 'Cosmetic Paint', 'color' => '#FFD700'], // Gold/Yellow (surface-level)
        'd' => ['name' => 'Chip',          'color' => '#00AA00'], // Deep Green (localized damage)
        'e' => ['name' => 'Dent',          'color' => '#0000FF'], // Pure Blue (structural)
        'f' => ['name' => 'Repainted',     'color' => '#4B0082'], // Indigo (aftermarket work)
        'g' => ['name' => 'Repaired',      'color' => '#b87bd2ff'], // Violet (repair history)
        'h' => ['name' => 'Foiled Wrap',      'color' => '#706c6eff'], // Violet (repair history)
        'i' => ['name' => 'Full PPF',      'color' => '#d80881ff'], // Violet (repair history)
        'j' => ['name' => 'Rust',      'color' => '#6b5407ff'], // Violet (repair history)
    ];
    /**
     * Mount the component and load the damages for the given inspection ID.
     *
     * @param int $inspectionId
     */
    public function mount($inspectionId)
    
    {
        $this->loadDamages();
    }

    /**
     * Load damages from the database based on the inspection ID.
     */
    private function loadDamages()
    {
        $this->damages = CarDamage::where('inspection_id', $this->inspectionId)->get()->toArray();
    }

    /**
     * Render the view-only component.
     */
    public function render()
    {
        return view('livewire.admin.inspection.car-damage-view');
    }
}
