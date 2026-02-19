<?php

namespace App\View\Components;
use Illuminate\View\Component;

class MultiImageUploader extends Component
{
    public function __construct(public array $existingImages = [])
    {
    }

    public function render()
    {
        return view('components.multi-image-uploader');
    }
}