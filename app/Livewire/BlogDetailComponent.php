<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Blog;


class BlogDetailComponent extends Component
{
    public $selected_blog;
     public $related_blogs = [];
     public function mount($slug)
    {
        $this->selected_blog = Blog::where('slug',$slug)->first();
        $this->related_blogs = Blog::orderBy('id', 'desc')->take(3)->get();
    }
    public function render()
    {
        return view('livewire.blog-detail-component');
    }
}
