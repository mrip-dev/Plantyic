<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;

class MakeListingComponent extends Component
{
    use WithPagination;
    public $showForm = false;
    public $isEditing = false;

    public $search = '';
    protected string $paginationTheme = 'bootstrap';
    protected $listeners = ['makeSaved' => 'closeForm', 'deleteMake' => 'delete', 'closeForm' => 'closeForm'];

    public function addNew()
    {
        $this->isEditing = false;
        $this->showForm = true;
        $this->dispatch('showCreateForm'); // tell form to open
    }

    public function editMake($id)
    {

        $this->isEditing = true;
        $this->showForm = true;

        $this->dispatch('showEditForm', $id); // tell form to open edit
    }

    public function delete($id)
    {
        Brand::findOrFail($id)->delete();
        $this->dispatch('success-notification', message: 'Make deleted successfully.');
    }
    public function updatingSearch()
{
    $this->resetPage();
}
public function closeForm()
    {
        
        $this->isEditing = false;
        $this->showForm = false;
       
    }

    public function render()
    {
        $query = Brand::query();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
            
        }

        $makes = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.make-listing-component', compact('makes'));
    }
}