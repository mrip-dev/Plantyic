<?php

namespace App\Livewire\Admin;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\FuelType;
use App\Models\Transmission;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleListingComponent extends Component
{
    use WithPagination;

    public $showForm = false;
    public $search = '';
    public $type;
    protected string $paginationTheme = 'bootstrap';
    protected $listeners = ['vehicleSaved' => '$refresh', 'cancelForm' => 'cancel', 'deleteVehicle' => 'delete'];
    public function mount($type)
    {
        $this->type = $type;

        if ($this->type == 'add') {
            $this->addNew();
        }
    }
    public function addNew()
    {
        $this->showForm = true;
        $this->dispatch('showCreateForm');
    }
    public function cancel()
    {
        $this->showForm = false;
    }
    public function editVehicle($id)
    {
        $this->showForm = true;
        $this->dispatch('showEditForm', $id);
    }

    public function delete($id)
    {
        Vehicle::findOrFail($id)->delete();

        $this->dispatch('success-notification', message: 'Vehicle deleted successfully.');
    }
    public function render()
    {
        $query = Vehicle::query();
         
        // Apply status filter based on type
        if ($this->type == 'sold') {
            $query->where('status', 'sold');
        } elseif ($this->type == 'listed') {
            $query->where('status', 'published');
        } elseif ($this->type == 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->type == 'draft') {
            $query->where('status', 'draft');
        }

        // Apply search filter with proper grouping
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('vin', 'like', '%' . $this->search . '%');
            });
        }

        // Get results with relations and pagination
        $vehicles = $query->with(['brand', 'vehicleModel'])->orderBy('created_at','desc')->paginate(10);
       
        return view('livewire.admin.vehicle-listing-component', compact('vehicles'));
    }
}
