<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // List Products
    public function userlist()
    {
        $users = User::all();
        return view('admin.pages.user.list', compact('users'));
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.user.edit', compact('user'));
    }
    

     public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);
        if ($request->filled('password')) {
        $validatedData['password'] = \Hash::make($request->input('password'));
        }

        $user->update($validatedData);

        return redirect()->route('admin.userlist')->with('message', 'User updated successfully.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.userlist')->with('message', 'User deleted successfully');
    }
    public function verifyemail($id)
    {
        $user = User::findOrFail($id);
        $user->update(array('email_verified'=>1));
        return redirect()->route('admin.userlist')->with('message', "User's email verified successfully");
    }

    public function verifyphone($id)
    {
        $user = User::findOrFail($id);
        $user->update(array('phone_verified'=>1));
        return back()->with('message', "User's phone verified successfully");
    }


    
}
