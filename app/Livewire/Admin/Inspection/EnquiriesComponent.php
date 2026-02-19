<?php

namespace App\Livewire\Admin\Inspection;

use App\Models\InspectionEnquiry;
use Livewire\Component;
use Livewire\WithPagination;

class EnquiriesComponent extends Component
{
     use WithPagination;
     protected string $paginationTheme = 'bootstrap';
    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'DESC';
    public bool $showModal = false;
    public ?InspectionEnquiry $selectedEnquiry;

    protected $listeners = ['closeModal' => 'closeModal'];

    public function setSortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'DESC';
        }
    }

    public function delete(InspectionEnquiry $inspection)
    {
        $inspection->delete();
         $this->dispatch('success-notification', message: 'Item Deleted Successfully');
    }
    public function view(InspectionEnquiry $inspection)
    {
        $this->selectedEnquiry = $inspection;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedEnquiry = null;
    }

    public function render()
    {
        $inspections = InspectionEnquiry::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy, $this->sortDir)->with('brand','vehicleModel')
            ->paginate(10);

        return view('livewire.admin.inspection.enquiries-component', [
            'inspections' => $inspections
        ]);
    }

}
