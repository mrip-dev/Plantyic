<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Brand;

class ImportBrands extends Command
{
    protected $signature = 'brands:import {file}';
    protected $description = 'Import car brands from a JSON file into the brands table';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $jsonData = File::get($filePath);
        $brands = json_decode($jsonData, true)['Results'] ?? [];

        $this->info("Found " . count($brands) . " brands in JSON.");

        $inserted = 0;
        foreach ($brands as $brand) {
            $name = trim($brand['Make_Name']);

            if (!$name) continue;

            // Insert only if brand does not exist
            $existing = Brand::where('name', $name)->first();
            if (!$existing) {
                Brand::create([
                    'name' => $name
                ]);
                $inserted++;
            }
        }

        $this->info("Inserted {$inserted} new brands successfully!");
        return 0;
    }
}
