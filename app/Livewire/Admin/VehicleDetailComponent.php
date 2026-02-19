<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Vehicle;

class VehicleDetailComponent extends Component
{

    public Vehicle $vehicle;

    public string $activeTab = 'info';
    public bool $sidebarVisible = true;
    public string $sidebarState = 'full';
    public function getMainContentGridClass(): string
    {
        switch ($this->sidebarState) {
            case 'full':
                return 'col-md-9';
            case 'collapsed':
                // The 'col' class will automatically fill the remaining space
                return 'col';
            case 'hidden':
            default:
                return 'col-md-12';
        }
    }

    // CHANGED: This method now cycles through the three states
    public function cycleSidebarState()
    {
        if ($this->sidebarState === 'full') {
            $this->sidebarState = 'collapsed';
        } elseif ($this->sidebarState === 'collapsed') {
            $this->sidebarState = 'hidden';
        } else { // It was 'hidden'
            $this->sidebarState = 'full';
        }
    }

    public function mount($id)
    {
        $this->vehicle = Vehicle::find($id);
    }
    public function setActiveTab(string $tabName)
    {
        $this->activeTab = $tabName;
    }

    public function render()
    {
        return view('livewire.admin.vehicle-detail-component');
    }
}
