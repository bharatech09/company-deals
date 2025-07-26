<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\CustomVerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;

class UserLoginController extends Controller
{
    public function send_verify_email($id){
        try {
            $user = User::findOrFail($id);
            
            // Check if email is already verified
            if ($user->email_verified == 1) {
                return redirect()->route('user.verfy.email_phone.form', $id)
                    ->with('status', 'Your email is already verified.');
            }

            // Send verification email
            Mail::to($user->email)->send(new CustomVerifyEmail($user));
            
            return redirect()->route('user.verfy.email_phone.form', $id)
                ->with('status', 'Verification email has been sent to your registered email address.');
        } catch (\Exception $e) {
            \Log::error('Email verification error: ' . $e->getMessage());
            return redirect()->route('user.verfy.email_phone.form', $id)
                ->with('error', 'Failed to send verification email. Please try again later.');
        }
    }
    public function verify_email_phone($id){
        try {
            $user = User::findOrFail($id);
            return view('pages.user.verify_email_phone', compact('user'));
        } catch (\Exception $e) {
            \Log::error('Verification page error: ' . $e->getMessage());
            return redirect()->route('user.login')
                ->with('error', 'Invalid verification request. Please try again.');
        }
    }
    public function verify_email($id,$hash){
        try {
            $user = User::findOrFail($id);
            
            // Check if email is already verified
            if ($user->email_verified == 1) {
                return redirect()->route('user.verfy.email_phone.form', $id)
                    ->with('status', 'Your email is already verified.');
            }

            // Verify the hash
            if (sha1($user->getEmailForVerification()) == $hash) {
                $user->update(['email_verified' => 1]);
                
                // Log successful verification
                \Log::info('Email verified successfully for user: ' . $user->email);
                
                return redirect()->route('user.verfy.email_phone.form', $id)
                    ->with('status', 'Thank you for verifying your email address.');
            }

            // Log failed verification attempt
            \Log::warning('Invalid verification attempt for user: ' . $user->email);
            
            return redirect()->route('user.verfy.email_phone.form', $id)
                ->with('error', 'Invalid verification link. Please request a new verification email.');
        } catch (\Exception $e) {
            \Log::error('Email verification error: ' . $e->getMessage());
            return redirect()->route('user.login')
                ->with('error', 'Verification failed. Please try again later.');
        }
    }
    public function showChangePasswordForm()
    {
        return view('pages.user.change-password');
    }

    // Handle the password update
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, \Auth::guard('user')->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        \Auth::guard('user')->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('user.change-password.form')->with('status', 'Password changed successfully');
    }
    public function logout(Request $request) {
        \Auth::guard('user')->logout();
         return redirect()->route('user.login')->with('status', 'You have successfully logged out!');
    }

    public function showForgotPasswordForm()
    {
        return view('pages.user.forgot-password');
    }
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );
       
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
    public function showResetPasswordForm($token)
    {
        return view('pages.user.reset-password', ['token' => $token]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('user.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    public function showRegistrationForm(Request $request)
    {
        return view('pages.user.register',["ragisteras"=>$request->input('as')]);
    }

    public function register(Request $request)
    {
        try {
            // Validate user input
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'digits:10', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'g-recaptcha-response' => ['required', new ReCaptcha],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'email_verified' => 0,
                'phone_verified' => 0,
            ]);

            // Send verification email
            Mail::to($user->email)->send(new CustomVerifyEmail($user));
            
            // Log successful registration
            \Log::info('New user registered: ' . $user->email);

            return redirect()->route('user.verfy.email_phone.form', $user->id)
                ->with('status', 'Registration successful! Please verify your email address.');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again later.')
                ->withInput();
        }
    }
    public function showLoginForm(Request $request) {
        return view('pages.user.login');
    }
    
    
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            
        ]);
        if (\Auth::guard('user')->attempt($credentials)) {
            \Session::put('role', $request->input('role'));
             $request->session()->regenerate();
            if ($request->input('role') === 'seller') {
                return redirect()->route('user.seller.dashboard');
            }elseif ($request->input('role') === 'buyer'){
                return redirect()->route('user.buyer.dashboard');
            }else{
                abort(403, 'Unauthorized');
            }
            
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

}
