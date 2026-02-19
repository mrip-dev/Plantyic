<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesManagementComponent extends Component
{
    use WithPagination;

    // --- Component Properties ---
    public $role_id = null;
    public $isEditing = false;
    public $name;
    public $selectedPermissions = [];

    // --- NEW: Property to control form visibility ---
    public $showForm = false;

    // --- Search and Pagination ---
    public $search = '';
    public $perPage = 10;

    protected function rules()
    {
        $roleIdToIgnore = $this->isEditing ? $this->role_id : null;
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $roleIdToIgnore,
            'selectedPermissions' => 'nullable|array',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->with('permissions')
            ->paginate($this->perPage);

        $allPermissions = Permission::all()->groupBy(function($item) {
            return explode('-', $item->name)[0];
        });

        return view('livewire.admin.roles-management-component', [
            'roles' => $roles,
            'allPermissions' => $allPermissions,
        ]);
    }

    public function resetForm()
    {
        $this->role_id = null;
        $this->isEditing = false;
        $this->name = '';
        $this->selectedPermissions = [];
        $this->resetValidation();
    }

    public function addNew()
    {
        $this->resetForm();
        $this->showForm = true; // Show the form
    }

    public function editItem($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showForm = true; // Show the form
    }

    public function saveRole()
    {
        $validated = $this->validate();
        $message = $this->isEditing ? 'Role updated successfully.' : 'Role created successfully.';

        if ($this->isEditing) {
            $role = Role::findOrFail($this->role_id);
            $role->update(['name' => $validated['name']]);
        } else {
            $role = Role::create(['name' => $validated['name']]);
        }
        $role->syncPermissions($validated['selectedPermissions']);

        $this->dispatch('success-notification', message: $message);
        $this->resetForm();
        $this->showForm = false; // Hide the form after saving
    }
     public function selectAllPermissionsInGroup(array $permissionsInGroup = [])
    {
        $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $permissionsInGroup));
    }

    /**
     * THE FIX (Part 2): Backend method to deselect all permissions in a group.
     */
    public function deselectAllPermissionsInGroup(array $permissionsInGroup = [])
    {
        $this->selectedPermissions = array_diff($this->selectedPermissions, $permissionsInGroup);
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false; // Hide the form
    }

    #[On('deleteItem')]
    public function deleteItem($id)
    {
        try {
            Role::findOrFail($id)->delete();
            $this->dispatch('success-notification', message: 'Role deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error-notification', message: 'Failed to delete role. It may be assigned to users.');
        }
        $this->showForm = false; // Ensure form is hidden if the user deletes the role they were editing
    }
}