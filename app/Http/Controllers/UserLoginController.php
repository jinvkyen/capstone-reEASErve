<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_accounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\departments;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    // Change Department
    public function change(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->status == 0) {
            return redirect()->route('login')->with('error', 'Invalid Access (Contact Department)');
        }

        // Handle department selection
        if ($request->filled('department')) {
            $selectedDepartment = $request->department;
            Session::put('dept_name', $selectedDepartment);

            return redirect()->route('dashboard');
        }

        // Handle role switching
        if ($request->filled('role')) {
            $role = $request->role;

            Session::put('current_role', $role);

            $department = $user->dept_name;

            if ($department) {
                Session::put('dept_name', $department);
            }

            if ($role == 1) {
                return redirect()->route('dashboard');
            } elseif ($role == 2) {
                return redirect()->route('admin.dashboard');
            }
        }

        return redirect()->back()->with('error', 'Invalid selection.');
    }

    public function login(Request $request)
    {
        $customMessages = [
            'username.required' => 'Username is Required',
            'password.required' => 'Password is Required',
        ];
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], $customMessages);

        // Query
        $user = user_accounts::where('user_id', $request->username)->first();

        $department = user_accounts::where('dept_name', $user->dept_name)->first();

        if ($user) {

            // User
            if ($user->user_type == 1) {
                if (Hash::check($request->password, $user->password)) {

                    // Check Active or Not
                    if ($user->status == 0) {
                        return redirect('/')->with('error', 'Account Deactivated (Contact Your Department)');
                    }

                    Auth::login($user);

                    $department = user_accounts::where('dept_name', $user->dept_name)->first();

                    if ($request->has('remember_me')) {
                        $request->session()->put('username', $request->username);
                        Cookie::queue('remember_user_username', $request->username, 60 * 24 * 30); // Remember 30 days
                    }

                    // Store department in the session and redirect to dashboard
                    Session::put('dept_name', $department->dept_name);
                    return redirect()->route('dashboard');
                } else {
                    return redirect('/')->with('error', 'Invalid Credentials');
                }
            }

            // DA
            if ($user->user_type == 2) {
                if (Hash::check($request->password, $user->password)) {

                    // Check Active or Not
                    if ($user->status == 0) {
                        return redirect('/')->with('error', 'Account Deactivated (Contact Your Department)');
                    }

                    Auth::login($user);

                    $role = $user->user_type;

                    Session::put('current_role', $role);
                    $department = user_accounts::where('dept_name', $user->dept_name)->first();

                    if ($request->has('remember_me')) {
                        $request->session()->put('username', $request->username);
                        Cookie::queue('remember_user_username', $request->username, 60 * 24 * 30); // Remember 30 days
                    }

                    // Store department in the session and redirect to dashboard
                    Session::put('dept_name', $department->dept_name);
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect('/')->with('error', 'Invalid Credentials');
                }
            }

            // MA
            if ($user->user_type == 3) {

                // Check Active or Not
                if ($user->status == 0) {
                    if ($request->password === $user->password) {
                        session()->put('user_id', $request->username);
                        return redirect()->route('ma.email')->with('message', 'Your account is not verified. Please complete the verification process.');
                    } else {
                        return redirect()->route('login')->with('error', 'Invalid Credentials');
                    }
                }

                if (Hash::check($request->password, $user->password)) {
                    Auth::login($user);

                    if ($request->has('remember_me')) {
                        Cookie::queue('remember_master_username', $request->username, 60 * 24 * 30); // Remember for 30 days
                    }

                    Session::put('dept_name', $user->dept_name);
                    return redirect()->route('ma.dashboard');
                } else {
                    return redirect()->route('login')->with('error_message', 'Invalid Credentials');
                }
            }

            // If user_type is not recognized
            return redirect('/')->with('error', 'Invalid Credentials');
        } else {
            // If no user was found
            return redirect('/')->with('error', 'Login Failed, Please Try Again');
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::flush();
        return redirect('/');
    }
}
