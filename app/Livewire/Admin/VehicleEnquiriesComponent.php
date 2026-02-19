<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Vehicle;

class VehicleEnquiriesComponent extends Component
{

    public string $activeTab = 'sale';
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
    public function mount($type)
    {
        $this->activeTab=$type;
    }
    public function setActiveTab(string $tabName)
    {
        $this->activeTab = $tabName;
        return redirect()->route('admin.vehicles.enquiries',$tabName);
    }
    public function render()
    {
        return view('livewire.admin.vehicle-enquiries-component');
    }
}
