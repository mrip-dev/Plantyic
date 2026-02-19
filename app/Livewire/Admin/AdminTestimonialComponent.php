<?php

namespace App\Livewire\Admin;

use App\Models\Testimonial;
use Livewire\Component;
use Livewire\WithPagination;

class AdminTestimonialComponent extends Component
{
    use WithPagination;
    public bool $isEditable = false;
    public ?Testimonial $testimonial = null;
    public $allTestimonials;

    public function toggleEditable()
    {
        $this->isEditable = !$this->isEditable;
    }

    protected function rules()
    {
        return [
            'testimonial.name' => 'required',
            'testimonial.rank' => 'required',
            'testimonial.comment' => 'required',
            'testimonial.status' => 'nullable'
        ];
    }

    public function mount()
    {
        $this->testimonial = new Testimonial();
    }


    public function save()
    {
        $this->validate();
        $this->testimonial->save();
        $this->dispatch('success-notification', message: 'Record Updated Successfully');
        $this->toggleEditable();
        $this->resetForm();
    }


    public function render()
    {

        $this->allTestimonials = Testimonial::all();
        return view('livewire.admin.admin-testimonial-component', [
            'allTestimonials' => $this->allTestimonials,
        ]);
    }
}
