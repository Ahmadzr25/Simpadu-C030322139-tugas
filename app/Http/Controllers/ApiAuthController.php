<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;



class ApiAuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
        'email' => 'required|string|email',
        'password'=> 'required'
    ]);
    
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        throw ValidationException::withMessages([
            'email'=> ['email incorrecct']
            ]);
  }
    if (!Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'password'=> ['password incorrect']
            ]);
}
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(
        [
            'jwt-token' => $token,
            'user' => $user,
        ]
        );
  }

  public function logout(Request $request)
  {
    $request->user()->tokens()->delete();
    return response()->json([
        'message'=> 'logout successfully'
    ]);
  }
}