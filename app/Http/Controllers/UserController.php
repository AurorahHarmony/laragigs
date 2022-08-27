<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Show Register/Create form.
    public function create()
    {
        return view('users.register');
    }

    // Create new user.
    public function store()
    {
        $formFields = request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        // Hash password.
        $formFields['password'] = bcrypt($formFields['password']);

        // Create user record.
        $user = User::create($formFields);

        // Login.
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in');
    }

    // Logout.
    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('message', 'Sucessfully logged out');
    }
}