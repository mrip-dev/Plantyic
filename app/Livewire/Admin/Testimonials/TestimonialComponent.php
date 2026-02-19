<?php

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use PHPUnit\Event\Code\Test;

class TestimonialComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    public bool $showForm = false;
    public bool $isEditing = false;
    public string $search = '';
    public ?Testimonial $editingTestimonial;
    public string $name = '';
    public string $rank = '';
    public string $comment = '';
    public $image_path;
    public bool $status = false;

    protected function rules()
    {
        return [
            'name' => 'required',
            'rank' => 'required',
            'comment' => 'nullable',
            'image_path' => [
                $this->isEditing ? 'nullable' : 'required',
                'image',
            ],
            'status' => 'boolean',
        ];
    }

    public function addNew()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = false;
    }
    public function editTestimonial(Testimonial $testimonial)
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = true;
        $this->editingTestimonial = $testimonial;
        $this->name = $testimonial->name ?? '';
        $this->rank = $testimonial->rank ?? '';
        $this->comment = $testimonial->comment ?? '';
        $this->status = $testimonial->status ?? false;
        $this->dispatch('clear-filepond-files');
    }

    public function saveTestimonial()
    {
        $validatedData = $this->validate();

        if ($this->image_path) {
            $validatedData['image_path'] = $this->image_path->store('testimonial-images', 'public');
        } elseif ($this->isEditing && $this->editingTestimonial->image_path) {
            $validatedData['image_path'] = $this->editingTestimonial->image_path;
        } else {
            $validatedData['image_path'] = null;
        }

        if ($this->isEditing) {
            $this->editingTestimonial->update($validatedData);
            session()->flash('success', 'Testimonial updated successfully.');
            $this->dispatch('success-notification', message: 'Record Updated Successfully');
        } else {
            Testimonial::create($validatedData);
            session()->flash('success', 'Testimonial created successfully.');
            $this->dispatch('success-notification', message: 'Record Saved Successfully');
        }

        $this->showForm = false;
    }
    #[On('deleteItem')]
    public function deleteItem($id)
    {
        $testimonial = Testimonial::find($id);

        if (!$testimonial) {
            session()->flash('error', 'Testimonial post not found.');
            return;
        }
        if ($testimonial->image_path) {
            Storage::disk('public')->delete($testimonial->image_path);
        }

        $testimonial->delete();
        session()->flash('success', 'Testimonial post deleted successfully.');
    }

    // Reset form fields and validation
    private function resetForm()
    {
        $this->reset(['name', 'comment', 'rank', 'image_path', 'status', 'editingTestimonial']);
        $this->resetErrorBag();
    }


    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {

        $data = Testimonial::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.testimonials.testimonial-component', [
            'data' => $data,
        ]);
    }
}
