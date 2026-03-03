<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizations\OrganizationMemberController;

Route::prefix('organization-members')->middleware('auth:api')->group(function () {
    Route::get('/user/{userId}/organizations', [OrganizationMemberController::class, 'getUserOrganizations'])
        ->whereNumber('userId');
});

