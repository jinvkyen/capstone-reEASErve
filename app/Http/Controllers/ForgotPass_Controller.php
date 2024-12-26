<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPass;
use App\Mail\Mails;
use App\Models\user_accounts;
use App\Models\VerifyToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session as FacadesSession;

class ForgotPass_Controller extends Controller
{
    // Blade
    public function forgot_pass()
    {
        return view('forgot_pass');
    }

    public function verification_pass()
    {
        return view('verification_pass');
    }

    public function new_pass()
    {
        return view('new_pass');
    }

    // Post
    public function forgot_passPost(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|max:150',
            ]);

            // Check if the email exists in the database
            $user = user_accounts::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->route('forgot.pass')->with('error', 'The Provided Email Address does not Exist in our Records.');
            }

            // Store Input Temporarily
            session()->put('user_inputs', $request->all());

            $validToken = rand(10, 1000) . '2024';
            $get_token = new VerifyToken();
            $get_token->token = $validToken;
            $get_token->email = $request['email'];
            $get_token->save();
            $get_user_email = $request['email'];
            $user = user_accounts::where('email', $request->email)->first();


            Mail::to($request['email'])->send(new ForgotPass($get_user_email, $validToken, $user));

            return redirect()->route('verify.pass');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('forgot.pass')->with('error', $e->getMessage());
        }
    }

    public function verification_passPost(Request $request)
    {
        if (!session()->has('user_inputs.email')) {
            return redirect()->route('login')->with('error', 'Invalid Session Data. Please Restart the Password Reset Process.');
        }

        $verifycoursetoken = $request->token;
        $email = session()->get('user_inputs.email');

        $verifycoursetoken = VerifyToken::where('token', $verifycoursetoken)
            ->where('email', $email)->first();

        if (!$verifycoursetoken) {
            return redirect()->back()->with('error', 'Invalid OTP');
        }

        if ($verifycoursetoken) {
            return redirect()->route('reset.pass');
        } else {
            return redirect()->route('verify.pass')->with('error', 'Invalid OTP');
        }
    }

    public function new_passPost(Request $request)
    {
        // Check if the session has email
        if (!session()->has('user_inputs.email')) {
            return redirect()->route('login')->with('error', 'Invalid Session Data. Please Restart the Password Reset Process');
        }

        // Validate request
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
            'password_confirmation' => 'required',
        ], [
            'password.min' => 'The password must be at least 8 characters long',
            'password.max' => 'The password must not exceed 20 characters',
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one digit, and one special character',
            'password.confirmed' => 'The passwords do not match',
        ]);

        // Retrieve email from session
        $email = session()->get('user_inputs.email');

        // Check if email is valid
        if (!$email) {
            return redirect()->route('reset.pass')->with('error', 'Invalid session data. Please restart the password reset process');
        }

        // Find user by email
        $user = user_accounts::where('email', $email)->first();

        // If user exists, update password
        if ($user) {
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // Logout and flush session
            Auth::logout();
            FacadesSession::flush();

            return redirect()->route('login')->with('success', 'Password Changed');
        }

        // Return error if user not found
        return redirect()->route('reset.pass')->with('error', 'User not found. Please restart the password reset process');
    }
}
