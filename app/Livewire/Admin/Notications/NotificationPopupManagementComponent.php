<?php

namespace App\Livewire\Admin\Notications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationPopupManagementComponent extends Component
{
    public $notifications = [];

    public function mount()
    {
        // Fetch notifications for the authenticated admin
        $this->notifications = Auth::guard('admin')->user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get();
    }
    public function removeNotification($id)
{
    $user = Auth::guard('admin')->user();
    $notification = $user->notifications()->where('id', $id)->first();

    if ($notification) {
        $notification->delete();
    }


    $this->notifications = $user->notifications()->get();
}

    public function render()
    {   $this->notifications = Auth::guard('admin')->user()->notifications()->get();
        return view('livewire.admin.notications.notification-popup-management-component');
    }
}
