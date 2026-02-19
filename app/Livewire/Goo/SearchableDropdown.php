<?php

namespace App\Livewire\Goo;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\Modelable;

class SearchableDropdown extends Component
{
    /**
     * This special attribute makes the component's $value property bindable
     * to a parent component's property using wire:model.
     * @var mixed
     */
    #[Modelable]
    public $value;

    // --- Component Properties (Passed from Parent as props) ---

    /**
     * The full class name of the Eloquent model to search (e.g., \App\Models\Brand::class).
     * @var string
     */
    public string $model;

    /**
     * The database column to search within the model (e.g., 'name').
     * @var string
     */
    public string $searchColumn;

    /**
     * The text for the HTML <label> element.
     * @var string
     */
    public string $label;

    /**
     * The placeholder text for the input field.
     * @var string
     */
    public string $placeholder = 'Search...';

    /**
     * If true, allows the user to add a new entry if it's not found.
     * @var bool
     */
    public bool $allowAdding = false;


    // --- Internal State Properties ---

    /**
     * The current text inside the search input field.
     * @var string
     */
    public string $searchTerm = '';

    /**
     * The collection of search results from the database.
     * @var Collection
     */
    public Collection $results;

    /**
     * The display name of the currently selected item.
     * @var string
     */
    public string $selectedName = '';

    /**
     * Controls the visibility of the dropdown list.
     * @var bool
     */
    public bool $showDropdown = false;

    /**
     * A unique ID for the component instance to avoid HTML ID conflicts.
     * @var string
     */
    public string $componentId;

    /**
     * Initializes the component. Runs once when the component is mounted.
     */
    public function mount()
    {
        $this->componentId = 'searchable-dropdown-' . uniqid();
        $this->results = new Collection();

        // If an initial value is passed via wire:model, load its name for display.
        // This is crucial for pre-populating the dropdown when editing a form.
        if ($this->value) {
            $initialModel = $this->model::find($this->value);
            if ($initialModel) {
                $this->selectedName = $initialModel->{$this->searchColumn};
            }
        }
    }

    /**
     * Runs when the user focuses on the input.
     * It loads the initial list of items if the search box is empty.
     */
    public function loadInitialResults()
    {
        if (empty($this->searchTerm)) {
            $this->results = $this->model::limit(50)->get();
        }
        $this->showDropdown = true;
    }

    /**
     * Runs automatically whenever the 'searchTerm' property is updated by the user.
     */
    public function updatedSearchTerm()
    {
        // When the search term is cleared, also clear the selected value and show initial list.
        if (empty($this->searchTerm)) {
            $this->value = null;
            $this->selectedName = '';
            $this->loadInitialResults();
            return;
        }

        // Search the database for results and show the dropdown.
        $this->results = $this->model::where($this->searchColumn, 'like', '%' . $this->searchTerm . '%')
            ->limit(10)
            ->get();
        $this->showDropdown = true;
    }

    /**
     * Called when a user clicks on an item in the dropdown.
     */
    public function selectItem($id, $name)
    {
        $this->value = $id;             // Update the modelable property for wire:model.
        $this->selectedName = $name;    // Set the display name.
        $this->searchTerm = $name;      // Set the input text to the selected name.
        $this->showDropdown = false;    // Hide the dropdown.
        $this->dispatch('newItemSelected', model: $this->model, id: $newModel->id);
    }

    /**
     * Called when the user clicks the "Add New" button.
     */
    public function addNew()
    {
        // Security check: Don't do anything if adding is not allowed.
        if (!$this->allowAdding) {
            return;
        }

        // Create the new model instance.
        $newModel = $this->model::create([
            $this->searchColumn => $this->searchTerm,
        ]);

        // Immediately select the newly created item.
        $this->selectItem($newModel->id, $newModel->{$this->searchColumn});

        // Dispatch an event to let the parent know a new item was added (optional but good practice).
        $this->dispatch('newItemAdded', model: $this->model, id: $newModel->id);
    }

    /**
     * Renders the component's view.
     */
    public function render()
    {
        return view('livewire.goo.searchable-dropdown');
    }
}