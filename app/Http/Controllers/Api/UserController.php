<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
   public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }
}
