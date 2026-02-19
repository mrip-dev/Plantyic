<?php

namespace App\Livewire\Admin;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class SubmissionList extends Component
{
    use WithPagination; // Enables pagination

    // Search and Sort properties
    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'DESC';

    // Modal properties
    public bool $showModal = false;
    public ?ContactSubmission $selectedSubmission; // Holds the submission to show in the modal

    // Listen for the 'closeModal' event to hide the modal
    protected $listeners = ['closeModal' => 'closeModal'];

    // Method to handle sorting
    public function setSortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'DESC';
        }
    }

    // Method to delete a submission
    public function delete(ContactSubmission $submission)
    {
        $submission->delete();

         $this->dispatch('success-notification', [
                'message' => 'Submission deleted successfully.'
            ]);
    }

    // Method to open the modal and load submission data
    public function view(ContactSubmission $submission)
    {
        $this->selectedSubmission = $submission;
        $this->showModal = true;
    }

    // Method to close the modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSubmission = null;
    }

    // The main render method
    public function render()
    {
        $submissions = ContactSubmission::query()
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(10); // Paginate the results

        return view('livewire.admin.submission-list', [
            'submissions' => $submissions
        ]);
    }
}