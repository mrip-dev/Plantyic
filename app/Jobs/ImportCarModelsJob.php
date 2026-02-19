<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Brand;
use App\Models\VehicleModel;

class ImportCarModelsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $makeName;
    public $year;

    public function __construct($makeName, $year)
    {
        $this->makeName = $makeName;
        $this->year = $year;
    }

    public function handle()
    {
        \Log::info("Starting job: make={$this->makeName}, year={$this->year}");
        $brand = Brand::firstOrCreate(['name' => $this->makeName]);

        $existingModels = VehicleModel::where('brand_id', $brand->id)
            ->pluck('name')
            ->map(fn($v) => strtolower($v))
            ->toArray();

        try {
            $modelsResponse = Http::retry(3, 5000)
                ->timeout(30)
                ->get("https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeYear/make/{$this->makeName}/modelyear/{$this->year}?format=json");

            $models = $modelsResponse->json()['Results'] ?? [];

            foreach ($models as $m) {
                $modelName = $m['Model_Name'];

                if (!in_array(strtolower($modelName), $existingModels)) {
                    VehicleModel::create([
                        'brand_id' => $brand->id,
                        'name' => $modelName,
                    ]);
                    $existingModels[] = strtolower($modelName);
                }
                \Log::info("Processing make {$this->makeName}, year {$this->year}");

            }
        } catch (\Exception $e) {
            // Log the error and retry later
            \Log::error("Failed to import models for {$this->makeName} ({$this->year}): {$e->getMessage()}");
        }
    }
}
