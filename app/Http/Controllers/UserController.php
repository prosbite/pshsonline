<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,club adviser,student'],
            'gender' => ['required', 'string', 'in:male,female'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);

        // Create teacher record if role is club adviser
        if ($request->role === 'club adviser') {
            $nameParts = explode(' ', $request->name);
            Teacher::create([
                'first_name' => $nameParts[0] ?? '',
                'last_name' => $nameParts[count($nameParts) - 1] ?? '',
                'middle_name' => count($nameParts) > 2 ? $nameParts[1] : '',
                'gender' => $request->gender,
                'email' => $request->email,
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('users')->with('success', 'User created successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'role' => ['required'],
            'gender' => ['required'],
        ]);
        $user = User::find($request->id);
        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);
            $user->teacher()->update([
                'gender' => $request->gender,
            ]);
        }

        return redirect()->route('users');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
        ]);
        $user = User::findOrFail($request->user_id);
        if ($user) {
            Auth::login($user);

            return redirect()->route('dashboard');
        }
    }
}
