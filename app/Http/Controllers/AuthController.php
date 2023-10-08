<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegisterUser;

class AuthController extends Controller
{
    use Notifiable;
    //
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|required|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email, 
            'password' => bcrypt($request->password),
        ]);
        Notification::send($user, new RegisterUser());

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
