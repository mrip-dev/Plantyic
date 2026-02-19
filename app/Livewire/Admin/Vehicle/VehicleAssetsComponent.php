<?php

namespace App\Livewire\Admin\Vehicle;

use App\Models\Vehicle;
use App\Models\VehicleImage; // Make sure you have this model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleAssetsComponent extends Component
{
    use WithFileUploads;

    public Vehicle $vehicle;

    // This will hold the new files to be uploaded.
    public $new_images = [];

    // This property will be bound to the radio buttons for selecting the cover image.
    public $cover_image_id = null;

    protected function rules()
    {
        return [
            // Validate that new_images is an array of image files.
            'new_images.*' => 'image', // 2MB Max per image
        ];
    }

    public function mount($vehicleId)
    {
        // Eager load the images to prevent N+1 issues in the view
        $this->vehicle = Vehicle::with('images')->findOrFail($vehicleId);
        $this->setInitialCoverId();
    }

    /**
     * A helper method to find the current cover image and set the property.
     */
    public function setInitialCoverId()
    {
        $currentCover = $this->vehicle->images()->where('is_cover', true)->first();
        if ($currentCover) {
            $this->cover_image_id = $currentCover->id;
        }
    }

    /**
     * This is a Livewire lifecycle hook that runs whenever a property is updated.
     * We use it to save new images as soon as they are selected.
     */
    public function updatedNewImages()
    {
        $this->validate(['new_images.*' => 'image']);

        // Check if a cover image already exists for this vehicle
        $hasCover = $this->vehicle->images()->where('is_cover', true)->exists();

        foreach ($this->new_images as $key => $photo) {
            $path = $photo->store('vehicles', 'public');

            $newImage = $this->vehicle->images()->create(['path' => $path]);

            // If no cover exists, make the FIRST new image the cover by default.
            if (!$hasCover && $key === 0) {
                $newImage->update(['is_cover' => true]);
                $hasCover = true;
            }
        }

        // Give feedback to the user
        session()->flash('success', 'New images uploaded successfully.');

        // Reset the upload input and refresh the component state
        $this->new_images = [];
        $this->vehicle->refresh();
        $this->setInitialCoverId();
    }

    /**
     * This hook runs when the user selects a different radio button for the cover image.
     */
    public function updatedCoverImageId($photoId)
    {
        if (!$photoId) return;

        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($photoId) {
            // 1. Set all images for this vehicle to NOT be the cover.
            $this->vehicle->images()->update(['is_cover' => false]);

            // 2. Set the newly selected image as the cover.
            VehicleImage::where('id', $photoId)->update(['is_cover' => true]);
        });

        // Refresh the vehicle model to get the latest data
        $this->vehicle->refresh();
        session()->flash('success', 'Cover image updated.');
    }

    /**
     * Deletes an image and its file, and handles re-assigning the cover if needed.
     */
    public function deleteImage($imageId)
    {
        $imageToDelete = VehicleImage::find($imageId);

        if ($imageToDelete) {
            Storage::disk('public')->delete($imageToDelete->path);
            $wasCover = $imageToDelete->is_cover;
            $imageToDelete->delete();

            // If the deleted image was the cover, we need to assign a new one.
            if ($wasCover) {
                // Find the first remaining image (if any) and make it the cover.
                $newCover = $this->vehicle->images()->first();
                if ($newCover) {
                    $newCover->update(['is_cover' => true]);
                }
            }
        }

        // Refresh the component to show the changes
        $this->vehicle->refresh();
        $this->setInitialCoverId();
        session()->flash('success', 'Image deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.vehicle.vehicle-assets-component');
    }
}
