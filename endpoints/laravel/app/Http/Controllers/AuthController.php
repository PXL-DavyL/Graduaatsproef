<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::where('email', $request->email)->first();
        
            $session = $request->session()->getId();
            return response()->json([
                'user' => $user,
                'session' => $session,
            ]);
        }
        
        return response()->json([
            'errors' => [
                'email' => ['The provided credentials do not match our records.']
            ]
        ], 401);
    }

    public function logout(Request $request)
    {   
        if(Auth::check()) {
            return response()->json(Auth::user());
        }
        else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function user()
    {   
        if(Auth::check()) {
            return response()->json(Auth::user());
        }
        else {
            return response()->json([
                'errors' => [
                    'message' => ['The provided credentials do not match our records.']
                ]
            ], 401);
        }
    }
}