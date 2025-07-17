<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

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
}
