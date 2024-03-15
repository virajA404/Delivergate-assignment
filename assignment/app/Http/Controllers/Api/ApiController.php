<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class ApiController extends Controller
{
    public function saveRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email'],
            'type' => ['required'],
            'password' => ['required', 'confirmed', 'min:3', 'max:255']
        ]);
  
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);

        return response() -> json([
            'status' => true,
            'message' => 'User created successfully'
        ], 201);
    }
}
