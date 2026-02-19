<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // For slug generation

class DownloadBrandImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:download-sources
                            {--json=brands.json : The name of the JSON file (e.g., brands.json) located in storage/app}
                            {--output-dir=brand_sources : The directory within storage/app to save all source images}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads only the "source" brand images into a single flat directory.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $jsonFileName = $this->option('json');
        $outputDir = $this->option('output-dir'); // No 'Base' now, it's the final output dir

        $this->info("Starting brand 'source' image download process into a single folder...");

        // 1. Read the JSON file
        if (!Storage::disk('local')->exists($jsonFileName)) {
            $this->error("Error: JSON file '{$jsonFileName}' not found in storage/app.");
            return Command::FAILURE;
        }

        $jsonContent = Storage::disk('local')->get($jsonFileName);
        $brandsData = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Error decoding JSON: " . json_last_error_msg());
            return Command::FAILURE;
        }

        if (!is_array($brandsData) || empty($brandsData)) {
            $this->error("No brand data found in the JSON file or it's not a valid array.");
            return Command::FAILURE;
        }

        $this->info("Found " . count($brandsData) . " brands in '{$jsonFileName}'.");

        // 2. Prepare download queue for 'source' images only
        $downloadQueue = [];

        foreach ($brandsData as $brand) {
            if (isset($brand['slug']) && isset($brand['image']) && is_array($brand['image'])) {
                $slug = Str::slug($brand['slug']);
                $imageUrl = $brand['image']['source'] ?? null; // Get only the 'source' URL

                if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    $downloadQueue[] = [
                        'url' => $imageUrl,
                        'slug' => $slug, // Keep slug for naming the file
                    ];
                } else {
                    $this->warn("Skipping 'source' for '{$brand['name']}' (slug: {$slug}) - URL not found or invalid.");
                }
            } else {
                $this->warn("Skipping malformed brand entry: " . json_encode($brand));
            }
        }

        if (empty($downloadQueue)) {
            $this->error("No valid 'source' image URLs found to download.");
            return Command::FAILURE;
        }

        $this->info("Found " . count($downloadQueue) . " 'source' images to download.");

        // Create a progress bar
        $progressBar = $this->output->createProgressBar(count($downloadQueue));
        $progressBar->start();

        // 3. Download images into the single flat directory
        foreach ($downloadQueue as $item) {
            $url = $item['url'];
            $slug = $item['slug'];

            // Determine filename using the brand's slug for uniqueness
            $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
            
            // Try to get a clean extension, default to jpg
            $extension = isset($pathInfo['extension']) && ctype_alnum($pathInfo['extension']) && strlen($pathInfo['extension']) <= 5
                         ? $pathInfo['extension'] : 'jpg';

            // Use the slug for the main filename to ensure uniqueness and clarity
            $filename = "{$slug}.{$extension}";

            $fullPath = $outputDir . '/' . $filename;

            try {
                // Check if file already exists to avoid re-downloading
                if (Storage::disk('local')->exists($fullPath)) {
                    $this->comment("Skipping '{$filename}' for '{$slug}' - Already exists.");
                    $progressBar->advance();
                    continue;
                }

                $response = Http::timeout(60)->get($url); // 60-second timeout

                if ($response->successful()) {
                    Storage::disk('local')->put($fullPath, $response->body());
                    $this->line(" <info>Downloaded:</info> <comment>{$filename}</comment>");
                } else {
                    $this->error("Failed to download '{$url}' for '{$slug}': HTTP Status " . $response->status());
                }
            } catch (\Throwable $e) {
                $this->error("An error occurred downloading '{$url}' for '{$slug}': " . $e->getMessage());
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("Download process completed successfully!");

        return Command::SUCCESS;
    }
}