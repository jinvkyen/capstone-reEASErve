<?php

namespace App\Http\Controllers;

use App\Models\VerifyToken;
use Illuminate\Http\Request;
use App\Models\user_accounts;
use App\Models\departments;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mails;
use App\Models\cms;
use App\Models\registration_policy;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class RegisterController extends Controller
{
    public function index()
    {
        // ID of 1 for Registration Policy
        $registration_policies = registration_policy::where('id', 1)->first();

        // General CMS for the Whole System
        $cms = cms::where('cms_id', 0)->first();

        $departments = departments::select(
            'department_id',
            'department_name'
        )->get();

        return view('register', compact('departments', 'registration_policies', 'cms'));
    }

    public function verify_account()
    {
        return view('verification');
    }

    public function create(Request $request)
    {

        // Email Restriction
        $email = Cms::where('cms_id', 0)->value('email');

        $customMessages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute must not exceed :max characters.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => $email . ' email is required.',
            'email.email' => $email . ' Email Only.',
            'user_id.required' => 'Student/Employee number is required.',
            'password.required' => 'Password is Required.',
            'password.regex' => 'Your password is considered weak.',
            'password.confirmed' => 'Password does not Match.',
            'position.required' => 'Position is required.',
            'position.in' => 'Select a valid position.',
            'departments.required' => 'Department Name is Required.',
            'departments.in' => 'Select a Valid Department.',
            'first_name.regex' => 'First Name must Contain only Letters.',
            'last_name.regex' => 'Last Name must Contain only Letters.',
            'user_id.integer' => 'Quantity Must be a Whole Number.',
            'user_id.min' => 'Negative Value are not Allowed.',
        ];

        $request->validate([
            'first_name' => 'required|max:45|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|max:45|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|max:75|email|ends_with:' . $email,
            'user_id' => 'required|integer|min:0',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:150',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
            'password_confirmation' => 'required',
            'position' => 'required',
            'departments' => 'required'
        ], $customMessages);

        if (user_accounts::where('email', $request->email)->exists()) {
            return redirect()->route('register.index')->withInput()->with('error', 'Email Already Exist');
        }

        if (user_accounts::where('user_id', $request->user_id)->exists()) {
            return redirect()->route('register.index')->withInput()->with('error', 'Student Number Already Exist');
        }

        // Store Inputs Temporarily
        session()->put('user_inputs', $request->all());

        try {
            $validToken = rand(10, 1000) . '2024';
            $get_token = new VerifyToken();
            $get_token->token = $validToken;
            $get_token->email = $request['email'];
            $get_token->save();
            $get_user_email = $request['email'];

            $user = $request['first_name'] . ' ' .  $request['last_name'];
            Mail::to($request['email'])->send(new Mails($get_user_email, $validToken, $user));

            return redirect()->route('verify_account');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function account_activation(Request $request)
    {
        $verifycoursetoken = $request->token;
        if (!$request->has('token') || empty($request->token)) {
            return redirect()->back()->with('error', 'OTP Field is Empty');
        }

        $email = session()->get('user_inputs.email');

        $verifycoursetoken = VerifyToken::where('token', $request->token)
            ->where('email', $email)->first();

        if (!$verifycoursetoken) {
            return redirect()->back()->with('error', 'Invalid OTP');
        }

        if ($verifycoursetoken) {
            // Retrieve temporarily stored user inputs
            $userInputs = session()->get('user_inputs');

            // Create the user account with hashed password
            $hashedPassword = Hash::make($userInputs['password']);
            $user = user_accounts::create([
                'first_name' => ucwords(strtolower($userInputs['first_name'])),
                'last_name' => ucwords(strtolower($userInputs['last_name'])),
                'email' => $userInputs['email'],
                'user_id' => $userInputs['user_id'],
                'password' => $hashedPassword,
                'position' => $userInputs['position'],
                'dept_name' => $userInputs['departments']
            ]);

            $verifycoursetoken->is_activated = 1;
            $user = user_accounts::where('email', $verifycoursetoken->email)->first();
            $verifycoursetoken->save();
            $user->status = 1;
            $user->save();
            $verifycoursetoken->delete();
            session()->forget('user_inputs');

            return redirect()->route('login')->with('success', 'Account Verified!');
        } else {
            return redirect('/verification')->with('error', 'Invalid OTP');
        }
    }
}
