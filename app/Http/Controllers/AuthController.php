<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegisterUser;
use Illuminate\Support\Facades\Password;

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

    public function verify_email(Request $request){
        $request->validate([
            'email' => 'email|required',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if($user){
            $otp_object = new \Otp;
            $otp = $otp_object->validate($request->email, $request->otp);
            if($otp->status){
                $user->email_verified_at = now();
                $user->save();
                return response()->json([
                    'message' => 'Email verified successfully',
                ]);
            }else{
                return response()->json([
                    'message' => 'Invalid OTP',
                ]);
            }
        }else{
            return response()->json([
                'message' => 'User not found',
            ]);
        }
    }

    public function reset_password_otp(Request $request){
        $request->validate([
            'email' => 'email|required',
        ]);

        $user = User::where('email', $request->email)->first();
        if($user){

           // Notification::send($user, new ResetPassword());

            return response()->json([
                'message' => 'OTP sent successfully',
            ]);
        }else{
            return response()->json([
                'message' => 'User not found',
            ]);
        }
    }

    public function generate_reset_password_token(Request $request){
        $request->validate([
            'email' => 'email|required',
            'otp' => 'required',
        ]);
        $otp_object = new \Otp;
        $otp = $otp_object->validate($request->email, $request->otp);
        if($otp->status){
            $user = User::where('email', $request->email)->first();
            // TODO: create password reset token  
        

    }
}
}
