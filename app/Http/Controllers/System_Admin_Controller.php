<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPass;
use App\Mail\Mails;
use App\Models\audit;
use App\Models\audits_archive;
use App\Models\cms;
use App\Models\college;
use App\Models\departments;
use App\Models\Policy;
use App\Models\registration_policy;
use App\Models\System_Admin_Account;
use App\Models\user_accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\VerifyToken;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session as FacadesSession;


class System_Admin_Controller extends Controller
{
    ///////////////////////////////////////////////////////////////////////
    // SYSTEM ADMIN LOGIN MODULE (VIEWS)
    ///////////////////////////////////////////////////////////////////////

    public function cms()
    {
        // CMS
        $dept = Session::get('dept_name');

        // $department_id = departments::where('department_id', $dept)->select('department_name')->first();

        $emblem = DB::table('departments')
            ->join('colleges', 'departments.college', '=', 'colleges.name') // Joining departments and colleges on college name
            ->where('departments.department_name', $dept)
            ->value('colleges.emblem');

        $accent_color = DB::table('cms')
            ->join('departments', 'cms.dept_id', '=', 'departments.department_id')
            ->where('departments.department_name', $dept)
            ->value('cms.color');

        $bg_image = DB::table('cms')
            ->join('departments', 'cms.dept_id', '=', 'departments.department_id')
            ->where('departments.department_name', $dept)
            ->value('cms.bg_image');

        $equipment_policies =
            DB::table('policies')
            ->join('departments', 'policies.department_owner', '=', 'departments.department_name')
            ->where('policies.department_owner', $dept)->get();

        // Emblem
        $path_emblem = $emblem;
        $url_emblem = asset('storage/' . $path_emblem);

        // Background Image
        $path_image = $bg_image;
        $url_bg_image = asset('storage/' . $path_image);

        $dept_label = DB::table('cms')
            ->join('departments', 'cms.dept_id', '=', 'departments.department_id')
            ->where('departments.department_name', $dept)
            ->value('departments.department_name');

        return [
            'url_emblem' => $url_emblem,
            'url_bg_image' => $url_bg_image,
            'dept_label' => $dept_label,
            'accent_color' => $accent_color,
            'equipment_policies' => $equipment_policies,
            'dept' => $dept
        ];
    }

    public function system()
    {
        return view('login.systemadmin.system_login');
    }


    //Forgot Password: Send Email
    public function forgotpass()
    {
        return view('login.systemadmin.system_forgot_password');
    }

    //Forgot Password: Send Email
    public function otpforgotpass()
    {
        return view('login.systemadmin.system_otp_forgot');
    }

    //Forgot Password: Send Email
    public function changepass()
    {
        return view('login.systemadmin.system_forgot_changepass');
    }

    //Email verification
    public function email()
    {
        return view('login.systemadmin.system_email_verification');
    }

    // OTP Verification
    public function otp()
    {
        return view('login.systemadmin.system_otp_verification');
    }

    //Credentials
    public function credentials()
    {
        if (!session()->has('email')) {
            return redirect()->route('system.login')->with('error', 'No Email Found in Session.');
        }

        // ID of 1 for Registration Policy
        $registration_policies = registration_policy::where('id', 1)->first();

        return view('login.systemadmin.system_credentials', compact('registration_policies'));
    }


    ///////////////////////////////////////////////////////////////////////
    // SYSTEM CONTROL (VIEWS)
    ///////////////////////////////////////////////////////////////////////
    public function control_panel()
    {
        $colleges = College::where('id', '!=', 0)->get(); // Retrieve all colleges except the one with ID 0

        $departments = DB::table('departments')
            ->select('college', DB::raw('COUNT(*) as count'))
            ->whereIn('college', $colleges->pluck('name')) // Use 'name' for the college name
            ->groupBy('college')
            ->get()
            ->keyBy('college');

        $cms = $this->cms();

        return view('login.systemadmin.system_control_panel', compact('colleges', 'departments', 'cms'));
    }

    public function about_us()
    {
        $cms = $this->cms();

        return view('login.systemadmin.system_about_us', compact('cms'));
    }

    public function departments($collegeId)
    {
        $college = college::where('id', $collegeId)
            ->select('name')
            ->first();

        // Get the departments for the selected college
        $departments = DB::table('departments')
            ->where('college', $college->name)
            ->get();

        $cms = $this->cms();

        // Iterate through the departments and get counts for each role
        foreach ($departments as $department) {
            // Reset master_admin and counts for each department iteration
            $department->master_admin = null;
            $department->user_count = DB::table('user_accounts')
                ->where('dept_name', $department->department_name)
                ->where(
                    'user_type',
                    1
                )  // Role 1 is for users
                ->count();

            $department->d_admin_count = DB::table('user_accounts')
                ->where('dept_name', $department->department_name)
                ->where('user_type', 2)  // Role 2 is for department admins
                ->count();

            $department->m_admin_count = DB::table('user_accounts')
                ->where('dept_name', $department->department_name)
                ->where('user_type', 3)  // Role 3 is for master admins
                ->count();

            $department->head = DB::table('user_accounts')
                ->where('dept_name', $department->department_name)
                ->where('user_type', 3)  // Role 3 is for master admins
                ->select('user_accounts.first_name', 'user_accounts.last_name', 'user_accounts.user_id')
                ->get();

            // Fetch the Master Admin for this department
            $masterAdmin = DB::table('user_accounts')
                ->where('dept_name', $department->department_name)
                ->where('user_type', 3)  // Role 3 is for master admins
                ->select(
                    'user_id',
                    'first_name',
                    'last_name',
                    'email',
                    'position',
                    'status'
                )
                ->first();

            // Assign the master admin if found, otherwise set it to null
            if ($masterAdmin) {
                $department->master_admin = $masterAdmin;
                $department->m_admin_count = 1;
            } else {
                $department->master_admin = null;
                $department->m_admin_count = 0;
            }


            // Fetch the college emblem for the department
            $emblem = DB::table('departments')
                ->join('colleges', 'departments.college', '=', 'colleges.name')
                ->where('departments.department_name', $department->department_name)
                ->select('colleges.emblem')
                ->first();

            $department->emblem = $emblem->emblem ?? 'emblem/default_emblem.png';

            // Fetch the department's background image
            $bg = DB::table('departments')
                ->join('cms', 'departments.department_id', '=', 'cms.dept_id')
                ->where('departments.department_name', $department->department_name)
                ->select('cms.bg_image')
                ->first();

            $department->bg_image = $bg->bg_image ?? 'assets/default_bg.png';
        }

        $positions = DB::table('positions')->get();

        // Pass the college and departments data to the view
        return view('login.systemadmin.system_add_departments', compact('college', 'departments', 'positions', 'collegeId', 'cms'));
    }

    // edited: joined with archived audits tbl
    public function audit_trail(Request $request)
    {
        $user = Auth::user();

        $cms = $this->cms();

        $search = $request->input('search', '');

        $audit = DB::table('audits')
            ->where(function ($query) use ($search) {
                $query->where('audits.action', 'like', "%{$search}%")
                    ->orWhere('audits.id', 'like', "%{$search}%")
                    ->orWhere('audits.department', 'like', "%{$search}%")
                    ->orWhere('audits.made_by', 'like', "%{$search}%")
                    ->orWhere('audits.user_id', 'like', "%{$search}%")
                    ->orWhere('audits.action_type', 'like', "%{$search}%")
                    ->orWhere(DB::raw("DATE_FORMAT(audits.datetime, '%M %d, %Y %h:%i %p')"), 'like', "%{$search}%");
            })
            ->select('audits.*')
            ->orderBy('audits.datetime', 'desc')
            ->paginate(200)
            ->appends(['search' => $search]);


        foreach ($audit as $entry) {
            $entry->datetime = Carbon::parse($entry->datetime)->format('F d, Y h:i A');
        }

        // Get the collection of items from LengthAwarePaginator
        $reserved_items_collection = collect($audit->items()); // Convert items to collection if not already

        $combinedRequests = $reserved_items_collection
            ->merge($audit->items())
            ->filter(function ($item) {
                return is_object($item) && property_exists($item, 'department');
            })
            ->unique('department')
            ->sortBy('department')
            ->values();

        $currentPage = PaginationPaginator::resolveCurrentPage();
        $perPage = 200;
        $items = $combinedRequests->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $combinedRequestsPaginated = new LengthAwarePaginator($items, $combinedRequests->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        // 

        $audit_archived = DB::table('audits_archive')
            ->where(function ($query) use ($search) {
                $query->where('audits_archive.action', 'like', "%{$search}%")
                    ->orWhere('audits_archive.id', 'like', "%{$search}%")
                    ->orWhere('audits_archive.made_by', 'like', "%{$search}%")
                    ->orWhere('audits_archive.department', 'like', "%{$search}%")
                    ->orWhere('audits_archive.user_id', 'like', "%{$search}%")
                    ->orWhere('audits_archive.action_type', 'like', "%{$search}%")
                    ->orWhere(DB::raw("DATE_FORMAT(audits_archive.datetime, '%M %d, %Y %h:%i %p')"), 'like', "%{$search}%");
            })
            ->select('audits_archive.*')
            ->orderBy('audits_archive.datetime', 'desc')
            ->paginate(200)
            ->appends(['search' => $search]);


        foreach ($audit_archived as $entry) {
            $entry->datetime = Carbon::parse($entry->datetime)->format('F d, Y h:i A');
        }
        return view('login.systemadmin.system_audit_trail', compact('cms', 'audit', 'search', 'combinedRequestsPaginated', 'audit_archived'));
    }

    public function email_restriction()
    {
        return view('login.systemadmin.system_email_restriction');
    }

    public function registration_policy(Request $request)
    {
        $cms = $this->cms();

        $policy = registration_policy::first();

        $domain = cms::where('cms_id', 0)->value('email');

        return view('login.systemadmin.system_registration_policy', compact('cms', 'policy', 'domain'));
    }

    public function account()
    {
        $user = Auth::user();

        $email = $user->email;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic,
        ];
        
        $cms = $this->cms();

        // Register the action in the audit table
        audit::create([
            'action' => 'Changed Name',
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_id' => $user->id,
            'user_type' => 'System Admin',
            'action_type' => 'Account Information Update',
            'department' => 'System Maintenance',
        ]);

        return view('login.systemadmin.system_account', compact('cms', 'userData'));
    }

    public function audits_archived(Request $request)
    {
        $user = Auth::user();

        $cms = $this->cms();

        $search = $request->input('search', '');

        $audit_archived = DB::table('audits_archive')
            ->where(function ($query) use ($search) {
                $query->where('audits_archive.action', 'like', "%{$search}%")
                    ->orWhere('audits_archive.id', 'like', "%{$search}%")
                    ->orWhere('audits_archive.made_by', 'like', "%{$search}%")
                    ->orWhere('audits_archive.department', 'like', "%{$search}%")
                    ->orWhere('audits_archive.user_id', 'like', "%{$search}%")
                    ->orWhere('audits_archive.action_type', 'like', "%{$search}%")
                    ->orWhere(DB::raw("DATE_FORMAT(audits_archive.datetime, '%M %d, %Y %h:%i %p')"), 'like', "%{$search}%");
            })
            ->select('audits_archive.*')
            ->orderBy('audits_archive.datetime', 'desc')
            ->paginate(200)
            ->appends(['search' => $search]);


        foreach ($audit_archived as $entry) {
            $entry->datetime = Carbon::parse($entry->datetime)->format('F d, Y h:i A');
        }
        return view('login.systemadmin.system_archived_audits', compact('cms', 'audit_archived', 'search'));
    }


    ///////////////////////////////////////////////////////////////////////
    // SYSTEM CONTROL (POST)
    ///////////////////////////////////////////////////////////////////////
    public function add_college(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming request data
        $validatedData = $request->validate([
            'college_name' => 'required|string|max:256|unique:colleges,name',
            'emblem' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $emblemPath = null;

        // Check if an emblem file is uploaded
        if ($request->hasFile('emblem')) {
            $file = $request->file('emblem');

            $emblemFileName = $validatedData['college_name'] . '_Emblem.' . $file->getClientOriginalExtension();

            $emblemPath = $file->storeAs('emblem', $emblemFileName, 'public');
        }

        // Creating a new college record in the database
        college::create([
            'name' => $validatedData['college_name'],
            'emblem' => $emblemPath,
            'created_at' => now(),
        ]);

        // Register the action in the audit table
        audit::create([
            'action' => 'Added College',
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_id' => $user->id,
            'user_type' => 'System Admin',
            'action_type' => 'System Update',
            'department' => 'System Maintenance',
        ]);

        // Return success response or redirect
        return redirect()->back()->with('success', $validatedData['college_name'] . ' Added Successfully!');
    }

    public function edit_college(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'college_name' => 'required|string|max:256|unique:colleges,name,' . $request->college_id,
            'emblem' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Find the college record
        $college = College::findOrFail($validatedData['college_id']);

        $changesMade = false;

        if ($college->name !== $validatedData['college_name']) {
            $college->name = $validatedData['college_name'];
            $changesMade = true;
        }

        if ($request->hasFile('emblem')) {
            if ($college->emblem) {
                Storage::disk('public')->delete($college->emblem); // Deletes the existing emblem file
            }

            // Store the new emblem
            $file = $request->file('emblem');
            $emblemFileName = $validatedData['college_name'] . '_Emblem.' . $file->getClientOriginalExtension();
            $emblemPath = $file->storeAs(
                'emblem',
                $emblemFileName,
                'public'
            );

            $college->emblem = $emblemPath;
            $changesMade = true;
        }

        // If changes were made, save the college record
        if ($changesMade) {
            $college->save(); // Save the changes

            // Register the action in the audit table
            audit::create([
                'action' => 'Edited ' . $college->name,
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->id,
                'user_type' => 'System Admin',
                'action_type' => 'System Update',
                'department' => 'System Maintenance',
            ]);

            return redirect()->back()->with('success', $validatedData['college_name'] . ' Updated Successfully!');
        }

        // If no changes were made, use SweetAlert
        return redirect()->back()->with('info', 'No changes were made to ' . $college->name . '.')->withInput();
    }

    public function add_dept(Request $request, $collegeId)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'department_name' => 'required|unique:departments,department_name',
        ]);

        // Pluck the college name based on the college ID
        $collegeName = College::where('id', $collegeId)->pluck('name')->first();

        if ($collegeName) {
            departments::create([
                'department_name' => $validatedData['department_name'],
                'college' => $collegeName,
                'created_at' => now(),
            ]);

            $deptId = departments::where('department_name', $validatedData['department_name'])->pluck('department_id')->first();

            function generateUniqueId()
            {
                do {
                    $uniqueId = rand(10000, 99999);
                } while (cms::where('cms_id', $uniqueId)->exists()); // Check for uniqueness
                return $uniqueId;
            }

            // Generate Default CMS for the Department
            cms::create([
                'cms_id' => generateUniqueId(),
                'color' => '#E9F3FD',
                'dept_id' => $deptId,
                'bg_image' => 'assets/cosbg.png',
                'logo' => 'assets/reserved_resources.png',
                'created_at' => now(),
            ]);

            $defaultResourceTypes = ['Equipment', 'Laboratory', 'Facility'];
            foreach ($defaultResourceTypes as $resourceType) {
                DB::table('cms_reservation_duration')->insert([
                    'duration' => 3, // Default duration
                    'department' => $validatedData['department_name'],
                    'cms_resource_type' => $resourceType,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Register the action in the audit table
            audit::create([
                'action' => 'Added ' . $validatedData['department_name'],
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->id,
                'user_type' => 'System Admin',
                'action_type' => 'System Update',
                'department' => 'System Maintenance',
            ]);

            return redirect()->route('system.departments', ['college' => $collegeId])->with('success', $validatedData['department_name'] . ' is Added!');
        } else {
            // Handle the case where no college is found with the given ID
            return redirect()->route('system.departments')->with('error', 'College not Found.');
        }
    }

    public function edit_dept(Request $request, $department_id, $collegeId)
    {
        $user = Auth::user();

        // Pluck the college name based on the college ID
        $collegeName = College::where('id', $collegeId)->pluck('name')->first();

        // Validate the input
        $request->validate([
            'department_name' => 'required|string|max:50',
        ]);

        // Retrieve the department by ID using the Eloquent model
        $department = departments::findOrFail($department_id);

        $old_department = $department->department_name;

        // Update department details: name and college
        $department->department_name = $request->input('department_name');
        $department->college = $collegeName; // Update the college field
        $department->edited_at = now();
        $department->edited_by = $user->id;

        // Save the updated department details
        $department->save();

        // Register the action in the audit table
        audit::create([
            'action' => 'Edited ' . $old_department . ' Into ' . $request->input('department_name'),
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_id' => $user->id,
            'user_type' => 'System Admin',
            'action_type' => 'System Update',
            'department' => 'System Maintenance',
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Department Name has been changed.');
    }


    public function add_master_account(Request $request)
    {
        // Email Restriction
        $email = Cms::where('cms_id', 0)->value('email');

        $validatedData = $request->validate([
            'user_id' => 'required|unique:user_accounts,user_id',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:150',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
            'email' => 'required|unique:user_accounts,email|ends_with:' . $email,
        ]);

        try {
            DB::beginTransaction();

            user_accounts::create([
                'user_id' => $validatedData['user_id'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'password' => $validatedData['password'],
                'email' => $validatedData['email'],
                'position' => 'Chairperson',
                'dept_name' => $request->dept_name ?? 'IT and IS',
                'status' => 0,
                'user_type' => 3,
                'timestamp' => now()
            ]);

            DB::commit();

            return response()->json(['success' => 'Chairperson Added Successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An Error Occurred. Please Try Again.'], 500);
        }
    }


    public function edit_master_account(Request $request)
    {
        // Email Restriction
        $email = Cms::where('cms_id', 0)->value('email');

        // Get the current user account being edited
        $user_id = user_accounts::where('user_id', $request->orig_user_id)->first();

        if (!$user_id) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validatedData = $request->validate([
                'user_id' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|ends_with:' . $email,
                'status' => 'required',
            ]);

        try {
            // Check and update user_id if modified
            if ($request->user_id !== $user_id->user_id) {
                if (user_accounts::where('user_id', $request->user_id)
                ->where('user_id', '!=', $user_id->user_id)
                ->exists()) {
                    return response()->json(['error' => 'Employee ID Already Exists.'], 409);
                }
                $user_id->user_id = $validatedData['user_id'];
            }

            // Update other fields if modified
            if ($request->first_name !== $user_id->first_name) {
                $user_id->first_name = $validatedData['first_name'];
            }

            if ($request->last_name !== $user_id->last_name) {
                $user_id->last_name = $validatedData['last_name'];
            }

            if ($request->email !== $user_id->email) {
                if (user_accounts::where('email', $request->email)
                    ->where('user_id', '!=', $user_id->user_id)
                    ->exists()
                ) {
                    return response()->json(['error' => 'Email Already Exists.'], 409);
                }
                $user_id->email = $validatedData['email'];
            }

            if ($request->status !== $user_id->status) {
                $user_id->status = $validatedData['status'];
            }

            $user_id->save();

            return response()->json(['success' => 'User Account Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An Error Occurred. Please Try Again.'], 500);
        }
    }



    public function policy_save(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string|max:75',
            'content' => 'required',
        ]);

        try {
            //Insert or update
            $count = registration_policy::count();  // Get the total number of records

            if ($count === 1) {
                // Fetch the only record in the table
                $policy = registration_policy::first();

                // Update the existing record
                $policy->update([
                    'title' => request()->input('title'),
                    'content' => request()->input('content'),
                ]);

                // Register the action in the audit table
                audit::create([
                    'action' => 'Changed Registration Policy',
                    'made_by' => $user->first_name . ' ' . $user->last_name,
                    'user_id' => $user->id,
                    'user_type' => 'System Admin',
                    'action_type' => 'System Update',
                    'department' => 'System Maintenance',
                ]);
            } else {
                // Handle the case where there are no records or more than one
                return
                    response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
            }

            return response()->json(['success' => true, 'message' => 'Policy has been Updated.']);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['success' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
        }
    }

    public function domain_save(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|string|max:75',
        ]);

        $domain = cms::where('cms_id', 0)->first();

        try {
            if ($domain) {
                $domain->email = $request['email'];
                $domain->save();

                // Register the action in the audit table
                audit::create([
                    'action' => 'Changed Email Domain',
                    'made_by' => $user->first_name . ' ' . $user->last_name,
                    'user_id' => $user->id,
                    'user_type' => 'System Admin',
                    'action_type' => 'System Update',
                    'department' => 'System Maintenance',
                ]);
            } else {
                return
                    response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
            }

            return response()->json(['success' => true, 'message' => 'Email Domain has been Updated.']);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['success' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
        }
    }

    public function audit_archive(Request $request, $id)
    {
        try {
            $audit = audit::findOrFail($id);

            // Insert the record into audits_archive table
            DB::table('audits_archive')->insert([
                'id' => $audit->id,
                'action' => $audit->action,
                'made_by' => $audit->made_by,
                'user_type' => $audit->user_type,
                'user_id' => $audit->user_id,
                'action_type' => $audit->action_type,
                'datetime' => $audit->datetime,
                'department' => $audit->department,
            ]);

            // Delete the record from the audits table
            $audit->delete();

            return response()->json(['success' => true, 'message' => 'Record Archived Successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An Error Occurred while Archiving the Record.']);
        }
    }

    public function mass_archive(Request $request)
    {
        $threshold = Carbon::now()->subDays(7); // Get date 7 days ago

        // Fetch the records to be archived and deleted
        $audits = audit::where('datetime', '<', $threshold)->get();

        if ($audits->isEmpty()) {
            return redirect()->route('system.audit.trail')->with('error', 'No records found older than 7 days.');
        }

        // Loop through each record and insert into the audits_archive table
        foreach ($audits as $audit) {
            DB::table('audits_archive')->insert([
                'id' => $audit->id,
                'action' => $audit->action,
                'made_by' => $audit->made_by,
                'user_type' => $audit->user_type,
                'user_id' => $audit->user_id,
                'action_type' => $audit->action_type,
                'datetime' => $audit->datetime,
                'department' => $audit->department,
            ]);

            // Delete the record from the audits table
            $audit->delete();
        }

        $message = "Successfully Archived " . $audits->count() . " records older than 7 days.";
        return redirect()->route('system.audit.trail')->with(
            'success',
            $message
        );
    }

    public function restore_archive(Request $request, $id)
    {
        try {
            // Find the record in the archive
            $audit = audits_archive::findOrFail($id);

            // Insert the record back into the audits table
            audit::create([
                'id' => $audit->id,
                'action' => $audit->action,
                'made_by' => $audit->made_by,
                'user_type' => $audit->user_type,
                'user_id' => $audit->user_id,
                'action_type' => $audit->action_type,
                'datetime' => $audit->datetime,
                'department' => $audit->department,
            ]);

            // After restoring, delete the record from the audits_archive table
            audits_archive::destroy($id);

            return response()->json(['success' => true, 'message' => 'Record Restored Successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'An Error Occurred while Restoring the Record.']);
        }
    }

    public function account_change_credentials(Request $request)
    {
        // Validate input
        $request->validate([
            'firstname' => 'nullable|string|max:45',
            'lastname' => 'nullable|string|max:45',
            'email' => 'nullable|email|max:75|unique:system_admin_accounts,email,' . auth()->id(),
        ]);

        $user = System_Admin_Account::find(auth()->id());

        if (!$user) {
            return redirect()->back()->withErrors('User not found.');
        }

        if ($request->filled('firstname')) {
            $user->first_name = $request->firstname;
        }

        if ($request->filled('lastname')) {
            $user->last_name = $request->lastname;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        $user->save();

        return redirect()->back()->with('success', 'Credentials Updated Successfully.');
    }

    public function account_pass_change(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('system.login')->with('error', 'Unauthorized Access.');
        }

        try {
            $request->validate([
                'old_pass' => 'required',
                'new_pass' => [
                    'required',
                    'string',
                    'min:8',
                    'max:20',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*?&]/',
                ],
                'confirm_pass' => 'required|same:new_pass',
            ], [
                'new_pass.min' => 'The new password must be at least 8 characters long.',
                'new_pass.max' => 'The new password must not exceed 20 characters.',
                'new_pass.regex' => 'The new password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.',
                'confirm_pass.same' => 'New Password and Confirm Password do not match.',
            ]);

            if (!Hash::check($request->old_pass, $user->password)) {
                return redirect()->route('system.account')->with('info', 'Old Password does not match.');
            }

            DB::beginTransaction();

            // Update the Password
            DB::table('system_admin_accounts')
                ->where('id', $user->id)
                ->update(['password' => Hash::make($request->new_pass)]);

            // Register the action in the audit table
            audit::create([
                'action' => 'Changed Password',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->id,
                'user_type' => 'System Admin',
                'action_type' => 'Account Credential Update',
                'department' => 'System Maintenance',
            ]);

            DB::commit();

            return redirect()->route('system.account')->with('success', 'Password changed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('system.account')->withErrors(['error' => 'Unable to change password. Try again.']);
        }
    }



    ///////////////////////////////////////////////////////////////////////
    // SYSTEM ADMIN LOGIN MODULE (POST)
    ///////////////////////////////////////////////////////////////////////
    public function system_logged(Request $request)
    {
        $customMessages = [
            'email.required' => 'Email is required',
            'password.required' => 'Password is Required',
        ];
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], $customMessages);

        // Query 
        $user = System_Admin_Account::where('email', $request->email)->first();

        // Invalid Inputs
        if (!$user) {
            return redirect()->route('system.login')->with('error', 'Invalid Credentials');
        }

        if ($user) {
            // If user is inactive or not yet verified
            if ($user->status == 0) {

                // Check if the password is stored in plain text
                if ($request->password  === $user->password) {

                    // Redirect to a verification page because the password matches but in plain text
                    return redirect()->route('system.email')->with('message', 'Your account is not verified. Please complete the verification process.');
                } else {
                    // Password does not match
                    return redirect()->route('system.login')->with('error', 'Invalid Credentials');
                }

                // If user is activated, use Hash::check() to verify password
            } else {

                // Incorrect Credentials
                if (!Hash::check($request->password, $user->password)) {
                    return redirect()->route('system.login')->with('error', 'Invalid Credentials');
                }

                // Correct Credentials
                if (Hash::check($request->password, $user->password)) {
                    Auth::guard('sysad')->login($user);

                    Session::put('dept_name', 'System Admin');

                    if ($request->has('remember_me')) {
                        Cookie::queue('remember_system_email', $request->email, 60 * 24 * 30); // Remember for 30 days
                    }

                    return redirect()->route('system.control.panel');
                } else {
                    return redirect()->route('system.login')->with('error', 'Invalid Credentials');
                }
            }
        } else {
            return redirect()->route('system.login')->with('error', 'Login Failed');
        }

        return redirect()->route('system.login');
    }

    public function system_logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('system.login');
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

            $user = "System Admin";
            Mail::to($request['email'])->send(new Mails($get_user_email, $validToken, $user));

            return redirect()->route('system.otp');
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
            return redirect()->route('system.credentials');
        }
    }

    //Credentials  - Change Password Upon Activating Account
    public function credentials_post(Request $request)
    {
        if (!session()->has('email')) {
            return redirect()->route('system.login')->with('error', 'No Email Found in Session.');
        }

        $email = session()->get('email');

        $customMessages = [
            'password.required' => 'Password is Required.',
            'password.regex' => 'Your password is considered weak.',
            'password.confirmed' => 'Password does not Match.'
        ];

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:150',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
            'password_confirmation' => 'required',
        ], $customMessages);

        // Hash the password before storing it
        $hashedPassword = Hash::make($request->password);

        $defaultAdmin = System_Admin_Account::where('email', 'reeaserve.admin@gmail.com')->first();

        // If default admin exists, delete it
        if ($defaultAdmin) {
            $defaultAdmin->delete();
        }

        // Create the new admin record in the system_admin_accounts table
        System_Admin_Account::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => Carbon::now()->toDateTimeString(), // Current timestamp for created_at
            'status' => 1, // Active status
        ]);

        Session::flush();

        return redirect()->route('system.login')->with('success', 'System Admin Account Activated!');
    }

    // Forgot Pass: Send Email
    public function forgot_pass_email(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|max:150',
            ]);

            // Check if the email exists in the database
            $user = System_Admin_Account::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->route('system.forgotpass')->with('error', 'The Provided Email Address does not Exist in our Records.');
            }

            // Store Input Temporarily
            session()->put('user_inputs', $request->all());

            $validToken = rand(10, 1000) . '2024';
            $get_token = new VerifyToken();
            $get_token->token = $validToken;
            $get_token->email = $request['email'];
            $get_token->save();
            $get_user_email = $request['email'];
            $user = System_Admin_Account::where('email', $request->email)->first();


            Mail::to($request['email'])->send(new ForgotPass($get_user_email, $validToken, $user));

            return redirect()->route('system.forgotpass.otp');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('system.forgotpass')->with('error', $e->getMessage());
        }
    }

    public function forgot_pass_otp(Request $request)
    {
        // Validate the input token with a max of 8 digits
        $request->validate([
            'token' => 'required|integer|max_digits:8',
        ], [
            'token.required' => 'The OTP is required.',
            'token.integer' => 'The OTP must be a valid number.',
            'token.max_digits' => 'The OTP is invalid.',
        ]);

        // Check if the session contains the email key
        if (!session()->has('user_inputs.email')) {
            return redirect()->route('system.login')->with('error', 'Invalid Session Data. Please Restart the Password Reset Process');
        }

        // Get the email from session
        $email = session()->get('user_inputs.email');
        $token = $request->token;

        // Find the token associated with the email
        $verifyToken = VerifyToken::where('token', $token)
            ->where('email', $email)
            ->first();

        // If no matching token or email is found, show an error
        if (!$verifyToken) {
            return redirect()->back()->with('error', 'Invalid OTP or Email');
        }

        // If the token and email match, store the token in the session and redirect to the password change page
        session()->put('token', $verifyToken->token);
        return redirect()->route('system.forgotpass.change');
    }

    public function forgot_pass_password(Request $request)
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

        // Retrieve email and token from session
        $email = session()->get('user_inputs.email');
        $token = session()->get('token');

        // Check for invalid email session
        if (!$email) {
            return redirect()->route('system.forgotpass')->with('error', 'Invalid session data. Please restart the password reset process');
        }

        // Verify the token
        $verifycoursetoken = VerifyToken::where('token', $token)->first();
        if (!$verifycoursetoken) {
            return redirect()->route('system.forgotpass')->with('error', 'Invalid or expired token. Please restart the password reset process');
        }

        // Find user by email
        $user = System_Admin_Account::where('email', $email)->first();
        if ($user) {
            // Update the password
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // Delete the token and clear session data
            $verifycoursetoken->delete();
            session()->forget(['user_inputs', 'token']);

            // Logout the user and clear the session
            Auth::logout();
            session()->flush();

            return redirect()->route('system.login')->with('success', 'Password Changed');
        }

        // Return error if user not found
        return redirect()->route('system.forgotpass')->with('error', 'User not found. Please restart the process');
    }
    ///////////////////////////////////////////////////////////////////////

}
