<?php

use App\Http\Controllers\admin\AdminBidController;
use App\Http\Controllers\admin\ContactSubmissionController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\VehicleManagerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SharedDocumentController;
use App\Livewire\Admin\Inspection\GenerationComponent;
use App\Models\Vehicle;
use App\Models\Blog;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from Laravel SMTP.', function ($message) {
            $message->to('theiqbal111@gmail.com') // Change to your email
                ->subject('SMTP Test Email');
        });

        return '✅ Test email sent successfully!';
    } catch (\Exception $e) {
        return '❌ Email sending failed: ' . $e->getMessage();
    }
});
// Create a temp route:
Route::get('/phpinfo', function() { phpinfo(); });

// Route::redirect('/', '');

Route::view('/', 'home')->name('home');
Route::view('/docs', 'docs')->name('docs');
Route::view('/health', 'health')->name('health');
