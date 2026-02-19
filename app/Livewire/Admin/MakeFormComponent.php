<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Support\Str;
use Livewire\Component;

class MakeFormComponent extends Component
{
    public $showForm = false;
    public $isEditing = false;
    public $makeId;
    public $name;

    // repeater for models
    public $models = [];  

    protected $listeners = ['showCreateForm' => 'showCreateForm', 'showEditForm' => 'showEditForm'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:brands,name,' . $this->makeId,
            'models.*' => 'nullable|string|max:255', // validate each model input
        ];
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showForm = true;
    }

    public function showEditForm($id)
    {
        $make = Brand::findOrFail($id);

        $this->makeId = $make->id;
        $this->name = $make->name;
        $this->isEditing = true;
        $this->showForm = true;

        // load existing models if editing
        $this->models = $make->models()->pluck('name')->toArray();
    }

    public function addModelField()
    {
        $this->models[] = '';
    }

    public function removeModelField($index)
    {
        unset($this->models[$index]);
        $this->models = array_values($this->models); 
    }

    public function save()
    {
        $this->validate();
    
        // check if brand already exists (case insensitive)
        $existingBrand = Brand::whereRaw('LOWER(name) = ?', [strtolower($this->name)])
            ->when($this->makeId, fn($q) => $q->where('id', '!=', $this->makeId))
            ->first();
    
        if ($existingBrand) {
            $this->addError('name', 'This brand already exists.');
            return;
        }
    
        $brand = Brand::updateOrCreate(
            ['id' => $this->makeId],
            ['name' => $this->name, 'slug' => Str::slug($this->name)]
        );
    
        // save models only when provided
        if (!empty($this->models)) {
            foreach ($this->models as $modelName) {
                $modelName = trim($modelName);
    
                if (!empty($modelName)) {
                    // check if this model already exists for the brand
                    $existingModel = VehicleModel::where('brand_id', $brand->id)
                        ->whereRaw('LOWER(name) = ?', [strtolower($modelName)])
                        ->first();
    
                    if ($existingModel) {
                        continue; // skip duplicate
                    }
    
                    VehicleModel::create([
                        'brand_id' => $brand->id,
                        'name' => $modelName,
                    ]);
                }
            }
        }
    
        $this->dispatch('success-notification', message: $this->isEditing ? 'Make & Models updated successfully.' : 'Make & Models created successfully.');
        $this->dispatch('makeSaved'); // refresh listing
    
        $this->cancel();
    }
    

    

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('closeForm');
    }

    protected function resetForm()
{
    $this->reset([
        'makeId',
        'name',
        'isEditing',
        'models',
    ]);

    $this->models = [];
    $this->resetErrorBag(); 
    $this->resetValidation(); 
}


    public function render()
    {
        return view('livewire.admin.make-form-component');
    }
}