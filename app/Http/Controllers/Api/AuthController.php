<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(RegisterRequest $request){

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Registered Successfully', 'user' => $user]);
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)){

            throw ValidationException::withMessages([
                'email' => 'Invalid Credentials'
            ]);
            
        }

        $request->session()->regenerate();

    }


    public function logout(Request $request){
        
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
    }
}
