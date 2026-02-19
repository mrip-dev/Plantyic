<?php

namespace App\Livewire;

use App\Models\Testimonial;
use Livewire\Component;

class TestimonialListingComponent extends Component
{

    public $testimonials = [];

    public function mount()
    {
        $this->testimonials = Testimonial::where('status',1)->get();
    }

    public function render()
    {

        return view('livewire.testimonial-listing-component');
    }
}
