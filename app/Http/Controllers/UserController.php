<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'role' => ['required'],
            'gender' => ['required']
        ]);
        $user = User::find($request->id);
        if($user){
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
