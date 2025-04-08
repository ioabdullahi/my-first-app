<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //SignUp Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'user' => $user,
            'message' => 'Registration Succesful',
            'name' => $request->name,
            201
        ]);
    }

    //Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The Provided Email is Incorrect'],
                'password' => ['The Provided Password is Incorrect']
            ]);
        }
        $token = $user->createToken('auth-token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    //Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); #
        return response()->json(['message' => 'You are Logged Out Successfully', 200]);
    }
}
