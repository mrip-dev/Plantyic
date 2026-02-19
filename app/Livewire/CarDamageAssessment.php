<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CarDamage;

class CarDamageAssessment extends Component
{
    public $damages = [];
    public $inspectionId;
    public $currentDamageType = 'a';
    public $currentSeverity = 'low';
    public $mode = 'add';
    public $showLabels = false;
    public $showPaintedAreas = false;
    public $viewAllFaults = false;
    public $remark = '';
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
    public $severityLevels = [
        'low' => ['name' => 'Low', 'description' => 'Minor damage, cosmetic only'],
        'medium' => ['name' => 'Medium', 'description' => 'Moderate damage, may need attention'],
        'high' => ['name' => 'High', 'description' => 'Significant damage, requires repair'],
        'critical' => ['name' => 'Critical', 'description' => 'Severe damage, immediate attention needed'],
    ];

    protected $rules = [
        'currentDamageType' => 'required',
        'currentSeverity' => 'required',
    ];

    public function mount()
    {
        $this->loadDamages();
    }
    private function loadDamages()
    {
        $this->damages = CarDamage::where('inspection_id', $this->inspectionId)->get()->toArray();
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function setCurrentDamageType($type)
    {
        $this->currentDamageType = $type;
    }

    public function setCurrentSeverity($severity)
    {
        $this->currentSeverity = $severity;
    }

    public function addDamage($x, $y, $bodyPart)
    {
        $this->validate();

        $damage = CarDamage::create([
            'inspection_id' => $this->inspectionId,
            'type' => $this->currentDamageType,
            'body_part' => $bodyPart,
            'severity' => $this->currentSeverity,
            'x' => $x,
            'y' => $y,
            'remark' => '',
        ]);
        $this->damages = CarDamage::all()->toArray();
        $this->loadDamages();
        $this->dispatch('damageAdded');
    }

    public function removeDamage($id)
    {

        CarDamage::where('inspection_id', $this->inspectionId)->find($id)?->delete();
        $this->loadDamages();
    }

    public function updateRemark($id, $remark)
    {
        $damage = CarDamage::where('inspection_id', $this->inspectionId)->find($id);
        if ($damage) {
            $damage->remark = $remark;
            $damage->save();
            $this->loadDamages();
        }
    }

    public function clearAll()
    {
        CarDamage::where('inspection_id', $this->inspectionId)->delete();
        $this->damages = [];
    }

    public function render()
    {
        return view('livewire.car-damage-assessment');
    }
}
