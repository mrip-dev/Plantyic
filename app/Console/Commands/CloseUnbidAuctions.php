<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CloseUnbidAuctions extends Command
{
    protected $signature = 'auctions:close-unbid';
    protected $description = 'Close auctions with no bids after auction end date';

    public function handle()
    {
        Log::info('CloseUnbidAuctions command ran at ' . now());
        $now = Carbon::now();

        $vehicles = Vehicle::where('is_auction', 1)
            ->where('auction_end_date', '<', $now)
            ->whereDoesntHave('bids', function ($q) {
                $q->where('status', 'accepted');
            })
            ->get();

        if ($vehicles->isEmpty()) {
            $this->info('No unaccepted auctions to close.');
            Log::info('No unaccepted auctions to close at ' . $now);
            return 0;
        }

        $count = $vehicles->count();

        // Close auctions
        Vehicle::whereIn('id', $vehicles->pluck('id'))
            ->update(['is_auction' => 0]);

        $this->info("Closed {$count} auctions with no accepted bids.");

        foreach ($vehicles as $vehicle) {
            Log::info("Auction closed automatically for vehicle ID: {$vehicle->id}, no accepted bids at {$now}");
        }

        return 0;
    }
}
