<?php

namespace App\Livewire\Admin;

use App\Models\VehicleBid;
use App\Notifications\BidConfirmation;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class ManageBids extends Component
{
    use WithPagination;

    public $search = '';
    public $allbids = [];
    public $selected = []; // for bulk delete
    public $selectAll = false; // checkbox toggle
    public $filterStatus = 'all'; // 'all', 'pending', 'accepted', 'rejected'
    protected string $paginationTheme = 'bootstrap';

    protected $queryString = ['search', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function toggleBidStatus($bidId)
    {
        $bid = VehicleBid::find($bidId);

        if ($bid) {
            if ($bid->status === 'accepted') {
                $bid->status = 'pending';
            } else {
                $bid->status = 'accepted';
                if ($bid->user) {
                    Notification::send($bid->user, new BidConfirmation($bid));
                }
            }
            $bid->save();

            $this->dispatch('success-notification', message: 'Bid status updated successfully.');
        } else {
            session()->flash('error', 'Bid not found.');
        }
    }

    public function deleteBid($bidId)
    {
        $bid = VehicleBid::find($bidId);
        if ($bid) {
            $bid->delete();
            session()->flash('message', 'Bid deleted successfully.');
        } else {
            session()->flash('error', 'Bid not found.');
        }
    }

    // ✅ Bulk delete selected bids
    public function deleteSelected()
    {
        if (!empty($this->selected)) {
            VehicleBid::whereIn('id', $this->selected)->delete();
            $this->selected = [];
            $this->selectAll = false;
            session()->flash('message', 'Selected bids deleted successfully.');
        } else {
            session()->flash('error', 'No bids selected.');
        }
        $this->selected = [];
    }

    // ✅ Toggle select all checkbox
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->allbids;
        } else {
            $this->selected = [];
        }
        dd($this->selected);
    }

    // ✅ Update selectAll when selected items change
    public function updatedSelected()
    {
        $this->selectAll = count($this->selected) === count($this->allbids);
    }
    // ✅ Add this in your ManageBids class
    public function updateSelectedFromJs($ids)
    {
        $this->selected = $ids;
        $this->selectAll = count($this->selected) === count($this->allbids);
    }

    // You could add a separate method for explicit rejection if needed
    public function rejectBid($bidId)
    {
        $bid = VehicleBid::find($bidId);
        if ($bid) {
            $bid->status = 'rejected';
            $bid->save();
            session()->flash('message', 'Bid rejected.');
        } else {
            session()->flash('error', 'Bid not found.');
        }
    }

    public function render()
    {
        $query = VehicleBid::with(['user', 'vehicle'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('bid_amount', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $bids = $query->paginate(10);
        $this->allbids = $bids->pluck('id')->map(function ($id) {
            return (string) $id;
        })->toArray();

        // Update selectAll based on current selection
        $this->selectAll = !empty($this->allbids) && count($this->selected) === count($this->allbids);

        return view('livewire.admin.manage-bids', [
            'bids' => $bids,
        ]);
    }
}
