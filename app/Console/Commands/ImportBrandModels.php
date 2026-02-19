<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportBrandModels extends Command
{
    protected $signature = 'brands:import-models {year?} {chunkSize=10}';
    protected $description = 'Import models for all brands from NHTSA API in chunks, avoid duplicates';

    public function handle()
    {
        $year = $this->argument('year') ?? now()->year;
        $chunkSize = (int) $this->argument('chunkSize');

        $totalBrands = Brand::count();
        $this->info("Found {$totalBrands} brands. Processing in chunks of {$chunkSize} for year {$year}.");
        Log::info("Starting import for year {$year} with chunk size {$chunkSize}. Total brands: {$totalBrands}");

        Brand::chunk($chunkSize, function($brands) use ($year) {
            foreach ($brands as $brand) {
                $makeName = urlencode($brand->name);
                $this->info("Processing brand: {$brand->name}");
                Log::info("Processing brand: {$brand->name}");

                try {
                    $response = Http::timeout(30)->get(
                        "https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeYear/make/{$makeName}/modelyear/{$year}?format=json"
                    );

                    if ($response->failed()) {
                        $this->warn("Failed to fetch models for {$brand->name}");
                        Log::error("Failed to fetch models for {$brand->name}");
                        continue;
                    } 

                    $models = $response->json()['Results'] ?? [];
                    $count = 0;

                                        foreach ($models as $m) {
                        $modelName = trim($m['Model_Name']);
                        if (!$modelName) continue;

                        $created = VehicleModel::firstOrCreate([
                            'brand_id' => $brand->id,
                            'name' => $modelName
                        ]);

                        if ($created->wasRecentlyCreated) $count++;
                    }

                    $this->info("Inserted {$count} new models for {$brand->name}");
                    Log::info("Inserted {$count} new models for {$brand->name}");

                } catch (\Exception $e) {
                    $this->error("Error processing {$brand->name}: " . $e->getMessage());
                    Log::error("Error processing {$brand->name}: " . $e->getMessage());
                }
            }
        });

        $this->info("All brands processed!");
        Log::info("All brands processed for year {$year}.");
    }
}