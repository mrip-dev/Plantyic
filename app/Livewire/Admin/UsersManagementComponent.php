<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;
use Spatie\Permission\Models\Role;


class UsersManagementComponent extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    // --- State Properties ---
    public bool $showForm = false;
    public string $search = '';
    public int $perPage = 10;

    // --- Form Properties (Separate Variables) ---
    public $editingUserId = null; // This will hold the ID of the user we are editing
    public string $name = '';
    public string $email = '';
    public string $role = '';
    public string $password = '';

    public $allRoles = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'role' => 'required',

            // The password is required only when creating a new user (editingUserId is null).
            'password' => ($this->editingUserId ? 'nullable' : 'required') . '|min:8',
        ];
    }

    public function mount()
    {
        $this->allRoles = Role::pluck('name')->toArray();
    }

    // No mount method is needed as properties are initialized above.

    public function render()
    {
        $users = User::query()
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.users-management-component', [
            'users' => $users,
        ]);
    }

    // --- Actions ---

    public function addNew()
    {
        $this->resetForm(); // Reset all form fields
        $this->showForm = true;
    }

    public function editItem(int $id)
    {
        $user = User::findOrFail($id);

        $this->resetErrorBag();
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // Clear password field for editing
        $this->showForm = true;
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    //toggle approval functtion 
    public function toggleApproval(int $id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = !$user->is_approved;
        $user->save();

        $status = $user->is_approved ? 'approved' : 'set to pending';
        $this->dispatch('success-notification', message: "User has been {$status}.");
    }

    public function saveUser()
    {
        $validatedData = $this->validate();

        // Prepare the data array for creation/update
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];


        // If a new password was provided, hash and add it to the data array.
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }
      
        // Use updateOrCreate to handle both creating and updating in one line.
       $user= User::updateOrCreate(['id' => $this->editingUserId], $data);
       
        $user->syncRoles($this->role);
        // Determine the success message
        $message = $this->editingUserId ? 'User updated successfully.' : 'User created successfully.';
        $this->dispatch('success-notification', message: $message);

        // Hide and reset the form
        $this->showForm = false;
        $this->resetForm();
    }

    // A helper method to reset all form-related properties.
    private function resetForm()
    {
        $this->reset(['editingUserId', 'name', 'email', 'role', 'password']);
        $this->resetErrorBag();
    }

    #[On('deleteItem')]
    public function deleteItem(int $id)
    {
        // Safety check: if the user being deleted is the one in the form, close the form.
        if ($this->editingUserId == $id) {
            $this->cancel();
        }

        User::findOrFail($id)->delete();
        $this->dispatch('success-notification', message: 'User deleted successfully.');
    }
}
