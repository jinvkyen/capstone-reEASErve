<?php

namespace App\Http\Controllers;

use App\Mail\Mails;
use Illuminate\Http\Request;
use App\Models\user_accounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\departments;
use App\Models\registration_policy;
use App\Models\VerifyToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Symfony\Component\CssSelector\Node\FunctionNode;

class AdminLoginController extends Controller
{

    // MA Default Account Procedure
    public function email()
    {
        return view('login.masteradmin.master_email_verification');
    }

    // OTP Verification
    public function otp()
    {
        return view('login.masteradmin.master_otp_verification');
    }

    //Credentials
    public function credentials()
    {
        if (!session()->has('email')) {
            return redirect()->route('system.login')->with('error', 'No Email Found in Session.');
        }

        // ID of 1 for Registration Policy
        $registration_policies = registration_policy::where('id', 1)->first();

        return view('login.masteradmin.master_credentials', compact('registration_policies'));
    }

    //Email verification
    public function email_post(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
            ]);

            session()->put('email', $request->email);

            $validToken = rand(10, 1000) . '2024';
            $get_token = new VerifyToken();
            $get_token->token = $validToken;
            $get_token->email = $request['email'];
            $get_token->save();
            $get_user_email = $request['email'];

            $user_id = session()->get('user_id');

            $user = user_accounts::where('email', $request->email)
                ->where('user_id', $user_id)
                ->first();

            if ($user) {
                $user = $user->last_name;

                Mail::to($request['email'])->send(new Mails($get_user_email, $validToken, $user));

                return redirect()->route('ma.otp.send');
            } else {
                return redirect()->back()->with('error', 'Account not Found on our Records or Username and Email Does not Match, Try Again');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    // OTP Verification
    public function otp_post(Request $request)
    {
        $verifycoursetoken = $request->token;
        if (!$request->has('token') || empty($request->token)) {
            return redirect()->back()->with('error', 'OTP Field is Empty');
        }

        $verifycoursetoken = VerifyToken::where('token', $request->token)->first();

        if (!$verifycoursetoken) {
            return redirect()->back()->with('error', 'Invalid OTP');
        }

        if ($verifycoursetoken) {
            return redirect()->route('ma.credentials');
        }
    }

    //Credentials  - Change Password Upon Activating Account
    public function credentials_post(Request $request)
    {
        // Ensure email exists in session
        if (!session()->has('email')) {
            return redirect()->route('login')->with('error', 'No Email Found in Session.');
        }

        $email = session()->get('email');

        $customMessages = [
            'password.required' => 'Password is Required.',
            'password.regex' => 'Your password is considered weak.',
            'password.confirmed' => 'Password does not Match.'
        ];

        // Validate the password confirmation
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'max:150',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
        ], $customMessages);

        // Hash the password before storing it
        $hashedPassword = Hash::make($request->password);

        // Find the user by email and update the account
        $user = user_accounts::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Account not found.');
        }

        $user->password = $hashedPassword;
        $user->status = 1; // Activate the account

        // Save and Activate
        $user->save();

        // Optionally remove the email from session after activation
        session()->forget('email');

        Session::flush();

        return redirect()->route('login')->with('success', 'Chairperson Account Activated!');
    }
}
