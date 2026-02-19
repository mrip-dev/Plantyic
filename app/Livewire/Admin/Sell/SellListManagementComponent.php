<?php

namespace App\Livewire\Admin\Sell;

use Livewire\WithPagination;
use App\Models\VehicleEnquiry;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

class SellListManagementComponent extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';
    public $search = '';
    public $selectedEnquiryId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Toggle the detail view for a specific enquiry.
     * If the same enquiry is clicked again, it closes the detail view.
     */
    public function viewDetails($enquiryId)
    {
        if ($this->selectedEnquiryId === $enquiryId) {
            $this->selectedEnquiryId = null; // Close if already open
        } else {
            $this->selectedEnquiryId = $enquiryId; // Open the new one
        }
    }

    /**
     * Listen for the delete confirmation event from the view.
     */

    public function delete($enquiryId)
    {

        $enquiry = VehicleEnquiry::with('imageSet')->find($enquiryId);

        if ($enquiry) {
            // Delete associated images from storage
            if ($enquiry->imageSet) {
                $imagesToDelete = [];
                foreach (range(1, 6) as $i) {
                    $imagePath = $enquiry->imageSet->{'image' . $i};
                    if ($imagePath) {
                        $imagesToDelete[] = $imagePath;
                    }
                }
                Storage::disk('public')->delete($imagesToDelete);
                $enquiry->imageSet->delete(); // Delete the image record
            }

            $enquiry->delete(); // Delete the main enquiry record
            session()->flash('success', 'Enquiry deleted successfully.');
            $this->selectedEnquiryId = null; // Close detail view if it was open
        }
    }

    public function render()
    {
        $saleEnquiries = VehicleEnquiry::Where('type','sale')->with(['brand', 'vehicleModel', 'imageSet'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('brand', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('vehicleModel', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.sell.sell-list-management-component', [
            'saleEnquiries' => $saleEnquiries,
        ]);
    }
}