<?php

namespace App\Livewire\Admin\Purchase;

use Livewire\WithPagination;
use App\Models\VehicleEnquiry;
use Livewire\Component;

class PurchaseListManagementComponent extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public $vehicleId = null;
    public $search = '';
    public $selectedEnquiryId = null;

   
    public function updatingSearch()
    {
        $this->resetPage();
    }

   
    public function viewDetails($enquiryId)
    {
        $this->selectedEnquiryId = $this->selectedEnquiryId === $enquiryId ? null : $enquiryId;
    }

    
    public function delete($enquiryId)
    {
        $enquiry = VehicleEnquiry::find($enquiryId);

        if ($enquiry) {
            $enquiry->delete();
            session()->flash('success', 'Purchase enquiry deleted successfully.');
            $this->selectedEnquiryId = null;
        }
    }

    public function render()
    {
        $purchaseEnquiries = VehicleEnquiry::Where('type','purchase')->with('vehicle','imageSet')
            ->when($this->vehicleId, function ($query) {
                $query->where('vehicle_id', $this->vehicleId);
            })
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('vehicle', function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.purchase.purchase-list-management-component', [
            'purchaseEnquiries' => $purchaseEnquiries,
        ]);
    }
}
