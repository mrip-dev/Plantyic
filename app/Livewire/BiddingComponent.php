<?php

namespace App\Livewire;

use App\Models\VehicleBid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BiddingComponent extends Component
{
    public $selected_vehicle;
    public $tags;
    public $bids = [];

    // Bid properties
    public $current_bid;
    public $max_bid;


    public $minimumNextBid;
    public $minimumMaxBid;

    // Bid statistics
    public $totalBids;
    public $highestBid;

    public $is_not_login = true;

    const BID_INCREMENT = 500;

    public function mount($selected_vehicle)
    {
        $this->selected_vehicle = $selected_vehicle;
        $this->tags = $this->selected_vehicle->features->where('type', 'tag');

        $this->refreshBidData();
        $this->initializeBidInputs();
        if (Auth::check()) {
            $this->is_not_login = false;
        }
    }


    public function updatedCurrentBid($value)
    {

        if (!is_numeric($value) || $value < $this->minimumNextBid) {
            $this->current_bid = $this->minimumNextBid;
        }


        $this->minimumMaxBid = $this->current_bid + self::BID_INCREMENT;
        if ($this->current_bid >= $this->max_bid) {
            $this->max_bid = $this->current_bid;
        }
    }

    public function updatedMaxBid($value)
    {

        if (!is_numeric($value) || $value < $this->minimumMaxBid) {
            $this->max_bid = $this->minimumMaxBid;
        }
    }


    public function saveBid()
    {
        if (!Auth::check()) {
            $this->dispatch('show-login-modal');
        }
        $this->validate([
            'current_bid' => "required|numeric|min:{$this->minimumNextBid}",
            'max_bid'     => "required|numeric",
        ], [

            'current_bid.min' => "Your bid must be at least " . number_format($this->minimumNextBid),
            'max_bid.gt'      => 'The max bid must be greater than your current bid.',
        ]);

        try {
            VehicleBid::create([
                'bid_amount' => $this->current_bid,
                'max_bid'    => $this->max_bid,
                'vehicle_id' => $this->selected_vehicle->id,
                'user_id'    => auth()->id(),
            ]);

            $this->refreshBidData();
            $this->initializeBidInputs();
        } catch (\Exception $e) {
            Log::error('Bid placement failed: ' . $e->getMessage());
            session()->flash('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    private function initializeBidInputs(): void
    {

        $this->minimumNextBid = $this->highestBid > 0
            ? $this->highestBid + self::BID_INCREMENT
            : $this->selected_vehicle->starting_bid_amount ?? 0;


        $this->current_bid = $this->minimumNextBid;


        $this->minimumMaxBid = $this->current_bid;
        $this->max_bid = $this->minimumMaxBid;
    }

    private function refreshBidData(): void
    {
        $this->totalBids = VehicleBid::where('vehicle_id', $this->selected_vehicle->id)->count();
        $this->highestBid = VehicleBid::where('vehicle_id', $this->selected_vehicle->id)->max('bid_amount') ?? 0;

        $this->bids = VehicleBid::with('user')
            ->where('vehicle_id', $this->selected_vehicle->id)
            ->latest()
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.bidding-component');
    }
}
