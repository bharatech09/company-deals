<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // List Products
    public function adminlist()
    {
        $admins = Admin::all();
        return view('admin.pages.admin.list', compact('admins'));
    }
    public function create()
    {
        return view('admin.pages.admin.addedit', ['admin' => new Admin()]);
    }



    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.pages.admin.addedit', compact('admin'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required',
        ]);
        $validatedData['password'] = \Hash::make($request->input('password'));
        Admin::create($validatedData);

        return redirect()->route('admin.adminlist')->with('message', 'Admin added successfully.');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
        ]);
        if ($request->filled('password')) {
            $validatedData['password'] = \Hash::make($request->input('password'));
        }

        $admin->update($validatedData);

        return redirect()->route('admin.adminlist')->with('message', 'Admin updated successfully.');
    }

    public function delete($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.adminlist')->with('message', 'Admin deleted successfully');
    }


    public function about(Request $request)
    {

        $aboutContent = DB::table('pages')->where('slug', $request->slug)->first();

        return view('admin.pages.home.about',compact('aboutContent'));
    }

    public function aboutupdate(Request $request)
    {
         DB::table('pages')->where('slug', $request->slug)->update([
            'content' => $request->content,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Updated successfully!');
    }
}
