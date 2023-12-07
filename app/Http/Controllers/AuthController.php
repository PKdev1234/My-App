<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegistrationForm(){
        return view('auth.register');
    }

    //register code
    public function register(Request $request){

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'user_role' => $request->input('user_role'),
        ]);

        Auth::login($user);

        return response()->json(['message' => 'Registration successful. Redirecting...'], 200);
    }

    public function showLoginForm(){

        return view('auth.login');
    }

    //login code
    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        try {
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return response()->json(['message' => 'Login successful. Redirecting...'], 200);
            } else {
                throw ValidationException::withMessages(['email' => __('auth.failed')]);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    //logout code
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        try {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Logout successful. Redirecting...'], 200);
            } else {
                return redirect('/');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Logout failed.'], 500);
        }
    }
}
