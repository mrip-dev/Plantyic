<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImagePreviewInput extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $label The label for the input field.
     * @param string|null $existingImage The URL of an existing image to show on load.
     */
    public function __construct(
        public string $label,
        public ?string $existingImage = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.image-preview-input');
    }
}