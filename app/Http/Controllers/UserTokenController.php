<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Validation\ValidationException;

class UserTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
           'email' => 'required|email',
           'password' => 'required',
           'device_name' => 'required',
        ]);

        $user = User::where('email',$request->email)->first();

        if(! $user  || !Hash::check($request->password,$user->password)){
            throw ValidationException::withMessages([
                'email'=>'El email o contraseña incorrecta'
            ]);
        }

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken
        ]);
    }
}
