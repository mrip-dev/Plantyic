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

require __DIR__.'/tasks.php';
require __DIR__.'/projects.php';
require __DIR__.'/notes.php';
require __DIR__.'/goals.php';

Route::view('/', 'home')->name('home');
Route::view('/docs', 'docs')->name('docs');
Route::view('/health', 'health')->name('health');
Route::view('/docs/auth', 'docs.auth');
Route::view('/docs/workspace', 'docs.workspace');
Route::view('/docs/tasks', 'docs.tasks');
Route::view('/docs/projects', 'docs.projects');
Route::view('/docs/notes', 'docs.notes');
Route::view('/docs/goals', 'docs.goals');
Route::view('/docs/team', 'docs.team');
Route::view('/docs/integrations', 'docs.integrations');
Route::view('/docs/notifications', 'docs.notifications');
Route::view('/docs/settings', 'docs.settings');
Route::view('/docs/bidding', 'docs.bidding');
Route::view('/docs/vehicle', 'docs.vehicle');
Route::view('/docs/inspection', 'docs.inspection');
Route::view('/docs/user', 'docs.user');
Route::view('/docs/blog', 'docs.blog');
Route::view('/docs/contact', 'docs.contact');
Route::view('/docs/favorite', 'docs.favorite');
Route::view('/docs/testimonial', 'docs.testimonial');
Route::view('/docs/ckeditor', 'docs.ckeditor');
Route::view('/docs/search', 'docs.search');
