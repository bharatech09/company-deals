<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \App\Models\Admin;
use App\Models\Banner;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.pages.login');
    }
    public function dashboard()
    {
        return view('admin.pages.dashboard');
    }
    public function homepage()
    {
        $banners = Banner::all();
        return view('admin.pages.home.index', compact('banners'));
    }

    public function bannercreate()
    {
        return view('admin.pages.home.create');
    }

    public function bannerstore(Request $request)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
        ]);

        $imageName = null;
        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/banners'), $imageName);
        }

        Banner::create([
            'image' => $imageName,
            'title' => $request->title,
            'short_description' => $request->short_description,
        ]);

        return redirect()->back()->with('message', 'Banner added successfully!');
    }


    public function bannerdestroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Delete image file from storage if it exists
        if ($banner->image && file_exists(public_path('uploads/banners/' . $banner->image))) {
            unlink(public_path('uploads/banners/' . $banner->image));
        }

        $banner->delete();

        return redirect()->back()->with('message', 'Banner deleted successfully!');
    }



    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (\Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        \Auth::guard('admin')->logout();
        return redirect('/admin/login')->with('status', 'You have successfully logged out!');
    }

    public function showForgotPasswordForm()
    {
        return view('admin.pages.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm($token)
    {
        return view('admin.pages.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, string $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showChangePasswordForm()
    {
        return view('admin.pages.change-password');
    }

    // Handle the password update
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, \Auth::guard('admin')->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        \Auth::guard('admin')->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('admin.change-password.form')->with('success', 'Password changed successfully');
    }
}
