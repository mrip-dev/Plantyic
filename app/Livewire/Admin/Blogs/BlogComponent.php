<?php

namespace App\Livewire\Admin\Blogs;

use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class BlogComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    public bool $showForm = false;
    public bool $isEditing = false;
    public string $search = '';
    public ?Blog $editingBlog;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public $image;
    public bool $is_published = false;

    protected function rules()
    {
        return [
            'title' => 'required',
            'slug' => 'required',
            'content' => 'nullable',
            'image' => [
                $this->isEditing ? 'nullable' : 'required',
                'image',
            ],
            'is_published' => 'boolean',
        ];
    }
    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }
    public function addNew()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = false;
    }
    public function editBlog(Blog $blog)
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = true;
        $this->editingBlog = $blog;
        $this->title = $blog->title;
        $this->slug = $blog->slug;
        $this->content = $blog->content;
        $this->is_published = $blog->is_published;
        $this->dispatch('clear-filepond-files');
    }

    public function saveBlog()
    {
        $validatedData = $this->validate();
        if ($this->image) {
            $validatedData['image'] = $this->image->store('blog-images', 'public');
        } elseif ($this->isEditing && $this->editingBlog->image) {
            $validatedData['image'] = $this->editingBlog->image;
        } else {
            $validatedData['image'] = null;
        }

        if ($this->isEditing) {
            $this->editingBlog->update($validatedData);
            $this->dispatch('success-notification', message: 'Item Updated Successfully');
            session()->flash('success', 'Blog post updated successfully.');
        } else {
            Blog::create($validatedData);
            $this->dispatch('success-notification', message: 'Item Created Successfully');
            session()->flash('success', 'Blog post created successfully.');
        }

        $this->showForm = false;
    }
    #[On('deleteItem')]
    public function deleteItem($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            session()->flash('error', 'Blog post not found.');
            return;
        }
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();
        $this->dispatch('success-notification', message: 'Item Deleted Successfully');
        session()->flash('success', 'Blog post deleted successfully.');
    }

    // Reset form fields and validation
    private function resetForm()
    {
        $this->reset(['title', 'slug', 'content', 'image', 'is_published', 'editingBlog']);
        $this->resetErrorBag();
    }


    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {

        $blogs = Blog::query()
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.blogs.blog-component', [
            'blogs' => $blogs,
        ]);
    }
}
