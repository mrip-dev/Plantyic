<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileUploadService
{
    /**
     * Upload a file and optionally delete the old one.
     *
     * @param UploadedFile $file The physical file to upload
     * @param string $path The directory path
     * @param string|null $oldFile Path of the old file to delete
     * @param string $disk The storage disk to use
     * @return string|false The path to the uploaded file or false on failure
     */
    public function upload(UploadedFile $file, string $path = 'uploads', ?string $oldFile = null, string $disk = 'public')
    {
        try {
            if ($oldFile) {
                $this->delete($oldFile, $disk);
            }

            return $file->store($path, $disk);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a file from storage.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function delete(string $path, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }
        } catch (\Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage());
        }

        return false;
    }
}
