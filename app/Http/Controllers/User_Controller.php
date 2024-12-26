<?php

namespace App\Http\Controllers;

use App\Models\approve_reject_analytics;
use App\Models\audit;
use Illuminate\Http\Request;
use App\Models\resources;
use App\Models\user_accounts;
use App\Models\cms;
use App\Models\cms_about;
use App\Models\cms_reservation_duration;
use App\Models\departments;
use App\Models\Facility;
use App\Models\Facility_Reservation;
use App\Models\feedback;
use App\Models\Resource_Type;
use App\Models\user_reservation_requests;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;


class User_Controller extends Controller
{

    public function cms()
    {
        // CMS
        $dept = Session::get('dept_name');
        $department = departments::where('department_name', '!=', 'All')->pluck('department_name');

        $dept_sidebar = departments::where('department_id', '!=', '0')->pluck('department_name');

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

        $equipment_policies = DB::table('policies')
            ->join('departments', 'policies.department_owner', '=', 'departments.department_name')
            ->where('policies.department_owner', $dept)->get();

        // Emblem
        $path_emblem = $emblem;
        $url_emblem = asset('storage/' . $path_emblem);

        // Background Image
        $path_image = $bg_image;
        $url_bg_image = asset('storage/' . $path_image);

        // Sanitize the Incoming Files
        function sanitizeFilename($string)
        {
            // Replace spaces with underscores
            $string = str_replace(' ', '_', $string);
            // Remove any character that is not alphanumeric or an underscore
            return preg_replace('/[^A-Za-z0-9_]/', '', $string);
        }

        $dept_label = DB::table('cms')
            ->join('departments', 'cms.dept_id', '=', 'departments.department_id')
            ->where('departments.department_name', $dept)
            ->value('departments.department_name');

        return compact('url_emblem', 'url_bg_image', 'dept_label', 'accent_color', 'equipment_policies', 'dept', 'department', 'dept_sidebar');
    }

    // Views
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $userData = [
            'user_id' => $userId,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
        ];

        // Query for Reserved Items of User
        $reserved_items = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->select(
                'user_reservation_requests.transaction_id',
                'user_reservation_requests.pickup_datetime',
                'user_reservation_requests.return_datetime',
                'user_reservation_requests.status',
                'user_reservation_requests.purpose',
                'user_reservation_requests.serial_number',
                'user_reservation_requests.professor',
                'user_reservation_requests.resource_id',
                'user_reservation_requests.created_at',
                'resources.department_owner',
                'resources.resource_name',
                'resources.image as resource_image',
                'resource_type.resource_type',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('user_reservation_requests.user_id', $userId)
            ->whereIn('user_reservation_requests.status', [2, 3, 5])
            ->orderBy('user_reservation_requests.created_at', 'desc')
            ->get();

        // Query for Reserved Facilities of User
        $facility_reservations = DB::table('facility_reservation')
            ->join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
            ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
            ->select(
                'facility_reservation.id',
                'facility_reservation.facility_name',
                'facility_reservation.user_id',
                'facility_reservation.purpose',
                'facility_reservation.start_datetime',
                'facility_reservation.end_datetime',
                'facility_reservation.status',
                'facility_reservation.created_at',
                'facilities.facility_name as facility_name',
                'facilities.department_owner',
                'facilities.image as facility_image',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('facility_reservation.user_id', $userId)
            ->whereIn('facility_reservation.status', [2, 3, 5]) // Include statuses 2, 3, 5
            ->get();

        // Convert reserved items to a collection and format datetime fields
        $reserved_items = collect($reserved_items)->map(function ($reserved_item) {
            $reserved_item->pickup_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->pickup_datetime)->format('F j, Y g:i A');
            $reserved_item->return_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->return_datetime)->format('F j, Y g:i A');
            $reserved_item->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->created_at)->format('F j, Y g:i A');
            return $reserved_item;
        });

        // Convert facility reservations to a collection and format datetime fields
        $facility_reservations = collect($facility_reservations)->map(
            function ($facility_reservation) {
                // Optionally validate or cast the object
                if (is_array($facility_reservation)) {
                    $facility_reservation = (object) $facility_reservation;
                }

                // Format datetime fields
                $facility_reservation->start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->start_datetime)->format('F j, Y g:i A');
                $facility_reservation->end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->end_datetime)->format('F j, Y g:i A');
                $facility_reservation->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->created_at)->format('F j, Y g:i A');

                return $facility_reservation;
            }
        );
        // Fetch reservations
        $reserved_items = $reserved_items->map(function ($item) {
            $item->type = 'resource'; // Add a type attribute
            return $item;
        });

        $facility_reservations = $facility_reservations->map(function ($item) {
            $item->type = 'facility'; // Add a type attribute
            return $item;
        });

        // Merge and sort the collections
        $reservations = $reserved_items->merge($facility_reservations)->sortByDesc('created_at')->take(3);

        // General Policies
        $policies = DB::table('general_policies')
            ->where('dept_owner', $cms['dept'])
            ->get();
        // End of General Policies

        $facilities = Facility::with(['reservations' => function ($query) {
            $query->whereIn('facility_reservation.status', [3, 5]) // Approved or On-Going
                ->orderBy('end_datetime', 'desc');
        }])
            ->where('facilities.department_owner', $cms['dept'])
            ->select('facilities.*', 'facilities.status as facility_status')
            ->get();

        return view('login.users.dashboard', compact(
            'userData',
            'reservations',
            'cms',
            'policies',
            'facilities'
        ));
    }

    public function about()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $userData = [
            'user_id' => $userId,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
        ];


        $department = session('dept_name');
        $aboutContent = cms_about::where('department', $department)->first();

        return view('login.users.about_us', compact('userData', 'cms', 'aboutContent'));
    }

    public function equipment_form(Request $request)
    {
        $user = Auth::user();
        $userData = null;

        $resource_id = $request->input('resource_id');
        $cms = $this->cms();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        } elseif ($user) {
            $userId = $user->user_id;
            $userData = [
                'user_id' => $userId,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_pic' => $user->profile_pic,
                'position' => $user->position,
            ];
        }

        $resources = resources::join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('reservation_status', 'resources.status', '=', 'reservation_status.status_id')
            ->select(
                'resources.resource_name',
                'resources.resource_id',
                'resources.image',
                'resources.serial_number',
                'resources.status',
                'resource_type.category_id',
                'resource_type.resource_type',
                'reservation_status.status_id'
            )
            ->where('resource_type.category_id', '=', '1')
            ->where('resources.department_owner', '=', $cms['dept'])
            ->whereNotIn('reservation_status.status_id', [7, 10, 12, 15]) // Exclude For Replacement, Out of Stock, and Maintenance
            ->get()
            ->mapWithKeys(function ($resource) {
                $storagePath = 'storage/' . $resource->image;

                return [
                    $resource->resource_id => [
                        'resource_name' => $resource->resource_name,
                        'image' => $storagePath,
                        'serial_number' => $resource->serial_number,
                        'status' => $resource->status,
                        'resource_type_id' => $resource->category_id,
                        'resource_type_name' => $resource->resource_type
                    ]
                ];
            });

        $policy = DB::table('resources')
            ->join('policies', 'resources.policy_id', '=', 'policies.policy_id')
            ->select('policies.policy_name', 'policies.policy_content', 'policies.inclusions')
            ->where('resources.resource_id', $resource_id)
            ->first();

        if (is_null($policy)) {
            $policy = (object) [
                'policy_name' => 'No Policy',
                'policy_content' => 'This resource does not have a policy.',
                'inclusions' => 'N/A'
            ];
        }

        if ($request->ajax()) {
            return response()->json($policy);
        }

        // Retrieve Professor
        $professor = user_accounts::where('position', 'Faculty') // Assuming role 2 is for professors/admins
            ->select('user_id', 'first_name', 'last_name')
            ->get();

        return view('login.users.equipment_form', compact(
            'userData',
            'cms',
            'resources',
            'policy',
            'professor',
        ));
    }

    public function lab_form(Request $request)
    {
        $user = Auth::user();
        $userData = null;

        $resource_id = $request->input('resource_id');
        $cms = $this->cms();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        } elseif ($user) {
            $userId = $user->user_id;
            $userData = [
                'user_id' => $userId,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_pic' => $user->profile_pic,
                'position' => $user->position,
            ];
        }

        $resources = resources::join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('reservation_status', 'resources.status', '=', 'reservation_status.status_id')
            ->select(
                'resources.resource_name',
                'resources.resource_id',
                'resources.image',
                'resources.serial_number',
                'resources.status',
                'resource_type.category_id',
                'resource_type.resource_type',
                'reservation_status.status_id'
            )
            ->where('resource_type.category_id', '=', '3')
            ->where('resources.department_owner', '=', $cms['dept'])
            ->whereNotIn('reservation_status.status_id', [7, 10, 12, 13, 15]) // Exclude For Replacement, Out of Stock, and Maintenance
            ->get()
            ->mapWithKeys(function ($resource) {
                $storagePath = 'storage/' . $resource->image;

                return [
                    $resource->resource_id => [
                        'resource_name' => $resource->resource_name,
                        'image' => $storagePath,
                        'serial_number' => $resource->serial_number,
                        'status' => $resource->status,
                        'resource_type_id' => $resource->category_id,
                        'resource_type_name' => $resource->resource_type
                    ]
                ];
            });

        $policy = DB::table('resources')
            ->join('policies', 'resources.policy_id', '=', 'policies.policy_id')
            ->select('policies.policy_name', 'policies.policy_content', 'policies.inclusions')
            ->where('resources.resource_id', $resource_id)
            ->first();

        if ($request->ajax()) {
            return response()->json($policy);
        }

        // Retrieve Professor
        $professor = user_accounts::where('position', 'Faculty') // Assuming role 2 is for professors/admins
            ->select('user_id', 'first_name', 'last_name')
            ->get();

        return view('login.users.lab_form', compact(
            'userData',
            'cms',
            'resources',
            'policy',
            'professor'
        ));
    }

    public function facility_form(Request $request)
    {
        $user = Auth::user();
        $userData = null;

        $cms = $this->cms();
        $facilities_id = $request->input('facility_id');

        if (!$user || $user->user_type == 3 || $user->position == 'Student') {
            return redirect()->route('login');
        } elseif ($user) {
            $userId = $user->user_id;
            $userData = [
                'user_id' => $userId,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_pic' => $user->profile_pic,
                'position' => $user->position,
            ];
        }

        // Facilities
        $facilities = Facility::select('facility_name', 'facilities_id', 'department_owner', 'image', 'is_available', 'status')
            ->where('department_owner', '=', $cms['dept'])
            ->get()
            ->mapWithKeys(function ($facilities) {
                $storagePath = 'storage/' . $facilities->image;

                return [$facilities->facilities_id => [
                    'facility_name' => $facilities->facility_name,
                    'image' => $storagePath,
                    'is_available' => $facilities->is_available, // Use the actual value from the database
                    'status' => $facilities->status, // Include the status field properly
                ]];
            });

        $policy = DB::table('facilities')
            ->join('policies', 'facilities.policy', '=', 'policies.policy_id')
            ->select('policies.policy_name', 'policies.policy_content', 'policies.inclusions')
            ->where('facilities.facilities_id', $facilities_id)
            ->first();

        if ($request->ajax()) {
            return response()->json($policy);
        }

        return view('login.users.facility_form', compact(
            'userData',
            'cms',
            'facilities',
            'policy'
        ));
    }

    public function getReservations(Request $request)
    {
        $resource_id = $request->input('resource_id');

        // Fetch reservations with status 3 (Approved) and 5 (On-Going)
        $reservations = user_reservation_requests::where('resource_id', $resource_id)
            ->whereIn('status', [3, 5])
            ->select('pickup_datetime', 'return_datetime')
            ->get();

        if ($reservations->isEmpty()) {
            return response()->json([
                'success' => true,
                'reservations' => [],
                'message' => 'No Reservations Found for this Resource.',
            ]);
        }

        // Format reservations for the response
        $formattedReservations = $reservations->map(function ($reservation) {
            return [
                'pickup' => Carbon::parse($reservation->pickup_datetime)->format('M d, Y h:i A'),
                'return' => Carbon::parse($reservation->return_datetime)->format('M d, Y h:i A'),
            ];
        });

        return response()->json([
            'success' => true,
            'reservations' => $formattedReservations,
        ]);
    }

    public function getFacilityReservations(Request $request)
    {
        $resource_id = $request->input('resource_id');

        // Fetch reservations with status 3 (Approved) and 5 (On-Going)
        $reservations = Facility_Reservation::where('facility_name', $resource_id)
            ->whereIn('status', [3, 5])
            ->select('start_datetime', 'end_datetime')
            ->get();

        if ($reservations->isEmpty()) {
            return response()->json([
                'success' => true,
                'reservations' => [],
                'message' => 'No Reservations Found for this Resource.',
            ]);
        }

        // Format reservations for the response
        $formattedReservations = $reservations->map(function ($reservation) {
            return [
                'start' => Carbon::parse($reservation->start_datetime)->format('M d, Y h:i A'),
                'end' => Carbon::parse($reservation->end_datetime)->format('M d, Y h:i A'),
            ];
        });

        return response()->json([
            'success' => true,
            'reservations' => $formattedReservations,
        ]);
    }


    public function reserve_resources()
    {
        $user = Auth::user();

        if ($user) {
            $userId = $user->user_id;
            $userData = [
                'user_id' => $userId,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_pic' => $user->profile_pic,
                'position' => $user->position,
            ];
        } elseif (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        } else {
            redirect()->route('login');
        }

        $cms = $this->cms();

        return view('login.users.reserve_resource', compact('userData', 'cms'));
    }

    public function my_reservations(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user || $user->user_type == 3) {
                return redirect()->route('login');
            }

            $userId = $user->user_id;
            $userData = [
                'user_id' => $userId,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_pic' => $user->profile_pic,
                'position' => $user->position,
            ];

            $cms = $this->cms();

            // CARD Query for Reserved Items of User
            $card_reserved_items = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
                ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
                ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
                ->select(
                    'user_reservation_requests.transaction_id',
                    'user_reservation_requests.pickup_datetime',
                    'user_reservation_requests.return_datetime',
                    'user_reservation_requests.status',
                    'user_reservation_requests.purpose',
                    'user_reservation_requests.serial_number',
                    'user_reservation_requests.professor',
                    'user_reservation_requests.resource_id',
                    'user_reservation_requests.created_at',
                    'resources.department_owner',
                    'resources.resource_name',
                    'resources.image as resource_image',
                    'resources.serial_number',
                    'resource_type.resource_type',
                    'reservation_status.status_state',
                    'reservation_status.status_color'
                )
                ->where('user_reservation_requests.user_id', $userId)
                ->orderBy('user_reservation_requests.created_at', 'desc')
                ->get();

            // CARD Query for Reserved Facilities of User
            $card_facility_reservations = DB::table('facility_reservation')
                ->join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
                ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
                ->select(
                    'facility_reservation.id',
                    'facility_reservation.facility_name',
                    'facility_reservation.user_id',
                    'facility_reservation.purpose',
                    'facility_reservation.start_datetime',
                    'facility_reservation.end_datetime',
                    'facility_reservation.status',
                    'facility_reservation.created_at',
                    'facilities.facility_name as facility_name',
                    'facilities.department_owner',
                    'facilities.image as facility_image',
                    'reservation_status.status_state',
                    'reservation_status.status_color'
                )
                ->where('facility_reservation.user_id', $userId)
                ->orderBy('facility_reservation.created_at', 'DESC')
                ->get();

            // Convert reserved items to a collection and format datetime fields
            $card_reserved_items = collect($card_reserved_items)->map(function ($reserved_item) {
                $reserved_item->pickup_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->pickup_datetime)->format('F j, Y g:i A');
                $reserved_item->return_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->return_datetime)->format('F j, Y g:i A');
                $reserved_item->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->created_at)->format('F j, Y g:i A');
                return $reserved_item;
            });

            // Convert facility reservations to a collection and format datetime fields
            $card_facility_reservations = collect($card_facility_reservations)->map(function ($facility_reservation) {
                // Optionally validate or cast the object
                if (is_array($facility_reservation)) {
                    $facility_reservation = (object) $facility_reservation;
                }

                // Format datetime fields
                $facility_reservation->start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->start_datetime)->format('F j, Y g:i A');
                $facility_reservation->end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->end_datetime)->format('F j, Y g:i A');
                $facility_reservation->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->created_at)->format('F j, Y g:i A');

                return $facility_reservation;
            });

            // Fetch reservations
            $card_reserved_items = $card_reserved_items->map(function ($item) {
                $item->type = 'resource';
                return $item;
            });

            $card_facility_reservations = $card_facility_reservations->map(function ($item) {
                $item->type = 'facility';
                return $item;
            });

            // Merge and Sort the Collections
            $card_reservations = $card_reserved_items->merge($card_facility_reservations)->sortByDesc('created_at');


            $search = $request->input('search', '');


            // TABLE Query for Reserved Items of User
            $reserved_items_query = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
                ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
                ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
                ->select(
                    'user_reservation_requests.transaction_id',
                    'user_reservation_requests.pickup_datetime',
                    'user_reservation_requests.return_datetime',
                    'user_reservation_requests.status',
                    'user_reservation_requests.purpose',
                    'user_reservation_requests.serial_number',
                    'user_reservation_requests.professor',
                    'user_reservation_requests.resource_id',
                    'user_reservation_requests.created_at',
                    'resources.department_owner',
                    'resources.resource_name',
                    'resources.serial_number',
                    'resources.image as resource_image',
                    'resource_type.resource_type',
                    'reservation_status.status_state',
                    'reservation_status.status_color'
                )
                ->where('user_reservation_requests.user_id', $userId)
                ->when($search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('resources.resource_name', 'like', '%' . $search . '%')
                            ->orWhere('user_reservation_requests.serial_number', 'like', '%' . $search . '%')
                            ->orWhere('user_reservation_requests.transaction_id', 'like', '%' . $search . '%')
                            ->orWhere('user_reservation_requests.pickup_datetime', 'like', '%' . $search . '%')
                            ->orWhere('user_reservation_requests.return_datetime', 'like', '%' . $search . '%')
                            ->orWhere('reservation_status.status_state', 'like', '%' . $search . '%')
                            ->orWhere('resource_type.resource_type', 'like', '%' . $search . '%');
                    });
                })->orderBy('user_reservation_requests.created_at', 'desc');

            // Paginate Reserved Items
            $reserved_items = $reserved_items_query->paginate(20);

            // TABLE Query for Reserved Facilities of User
            $facility_reservations_query = Facility_Reservation::join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
                ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
                ->select(
                    'facility_reservation.id',
                    'facility_reservation.facility_name',
                    'facility_reservation.user_id',
                    'facility_reservation.purpose',
                    'facility_reservation.start_datetime',
                    'facility_reservation.end_datetime',
                    'facility_reservation.status',
                    'facility_reservation.created_at',
                    'facilities.facilities_id',
                    'facilities.facility_name as facility_name',
                    'facilities.department_owner',
                    'facilities.image as facility_image',
                    'reservation_status.status_state',
                    'reservation_status.status_color'
                )
                ->where('facility_reservation.user_id', $userId)
                ->when($search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('facilities.facility_name', 'like', '%' . $search . '%')
                            ->orWhere('facility_reservation.id', 'like', '%' . $search . '%')
                            ->orWhere('reservation_status.status_state', 'like', '%' . $search . '%')
                            ->orWhere('facilities.department_owner', 'like', '%' . $search . '%');
                    });
                })->orderBy('facility_reservation.created_at', 'desc');

            // Paginate Facility Reservations
            $reserved_facility = $facility_reservations_query->paginate(20);



            // Convert reserved items to a collection and format datetime fields
            $reserved_items_collection = collect($reserved_items->items())->map(function ($reserved_item) {
                $reserved_item->pickup_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->pickup_datetime)->format('F j, Y g:i A');
                $reserved_item->return_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->return_datetime)->format('F j, Y g:i A');
                $reserved_item->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $reserved_item->created_at)->format('F j, Y g:i A');
                return $reserved_item;
            });

            // Convert facility reservations to a collection and format datetime fields
            $facility_reservations_collection = collect($reserved_facility->items())->map(function ($facility_reservation) {
                $facility_reservation->start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->start_datetime)->format('F j, Y g:i A');
                $facility_reservation->end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->end_datetime)->format('F j, Y g:i A');
                $facility_reservation->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $facility_reservation->created_at)->format('F j, Y g:i A');
                return $facility_reservation;
            });

            if ($request->ajax()) {
                return response()->json([
                    'reserved_items_html' => view('my_reservations', ['reserved_items' => $reserved_items_collection])->render(),
                    'reserved_items_pagination' => $reserved_items->appends(request()->query())->links()->render(),
                    'facility_html' => view('my_reservations', ['reserved_facility' => $facility_reservations_collection])->render(),
                    'facility_pagination' => $reserved_facility->links()->render(),
                ]);
            }

            $reserved_items_collection = $reserved_items->getCollection();
            $reserved_facility_collection = $reserved_facility->getCollection();
            $combinedRequests = $reserved_items_collection
                ->merge($reserved_facility_collection)
                ->unique('status_state')
                ->values();
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 20;
            $items = $combinedRequests->slice(($currentPage - 1) * $perPage, $perPage)->all();

            $combinedRequestsPaginated = new LengthAwarePaginator($items, $combinedRequests->count(), $perPage, $currentPage, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);



            return view('login.users.my_reservations', compact(
                'userData',
                'cms',
                'card_reservations',
                'reserved_items',
                'reserved_facility',
                'search',
                'combinedRequestsPaginated'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function overview(Request $request, $transactionId)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $userData = [
            'user_id' => $userId,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
        ];

        // Fetch the transaction details
        $transaction = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->leftJoin('policies', 'resources.policy_id', '=', 'policies.policy_id')
            ->leftJoin('user_accounts as noted_by', 'user_reservation_requests.noted_by', '=', 'noted_by.user_id')
            ->leftJoin('user_accounts as approver', 'user_reservation_requests.approved_by', '=', 'approver.user_id') // Join for Approved by
            ->leftJoin('user_accounts as releaser', 'user_reservation_requests.released_by', '=', 'releaser.user_id') // Join for Released to
            ->leftJoin('user_accounts as returner', 'user_reservation_requests.returned_to', '=', 'returner.user_id') // Join for Returned to
            ->where('user_reservation_requests.transaction_id', $transactionId)
            ->where('user_reservation_requests.user_id', $userId)
            ->select(
                'user_reservation_requests.*',
                'resource_type.resource_type',
                'resources.resource_name as name',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'policies.policy_name',
                'policies.policy_content',
                'policies.inclusions',
                'noted_by.first_name as noted_by_first_name',
                'noted_by.last_name as noted_by_last_name',
                'approver.first_name as approved_by_first_name',  // Fetch Approved by first name
                'approver.last_name as approved_by_last_name',    // Fetch Approved by last name
                'releaser.first_name as released_by_first_name',  // Fetch Releaser to first name
                'releaser.last_name as released_by_last_name',    // Fetch Releaser to last name
                'returner.first_name as returned_to_first_name',  // Fetch Returned to first name
                'returner.last_name as returned_to_last_name'     // Fetch Returned to last name
            )
            ->first();

        $professor = user_reservation_requests::join('user_accounts', 'user_reservation_requests.professor', '=', 'user_accounts.user_id')
            ->where('user_reservation_requests.transaction_id', $transactionId)
            ->select('user_reservation_requests.*', 'user_accounts.first_name', 'user_accounts.last_name', 'user_accounts.user_id') // Select relevant columns
            ->first();

        if (!$transaction) {
            return redirect()->route('dashboard')->with('error', 'Transaction not Found.');
        }

        // Fetch the department owner
        $department_owner = DB::table('resources')
            ->where('resource_id', $transaction->resource_id)
            ->value('department_owner');

        // Check if feedback already exists
        $feedbackExists = DB::table('user_feedback')
            ->where('username', $userId)
            ->where('resource_id', $transaction->resource_id)
            ->exists();

        // Format dates
        $transaction->pickup_datetime = \Carbon\Carbon::parse($transaction->pickup_datetime)->format('F j, Y g:i A');
        $transaction->return_datetime = \Carbon\Carbon::parse($transaction->return_datetime)->format('F j, Y g:i A');
        $transaction->created_at = \Carbon\Carbon::parse($transaction->created_at)->format('F j, Y g:i A');

        $cms = $this->cms();

        return view('login.users.overview', compact(
            'userData',
            'cms',
            'transaction',
            'department_owner',
            'feedbackExists',
            'professor'
        ));
    }

    public function facility_overview(Request $request, $facilityReservationId)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $userData = [
            'user_id' => $userId,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
        ];

        // Fetch the facility reservation details
        $facility = DB::table('facility_reservation')
            ->join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
            ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
            ->leftJoin('policies', 'facilities.policy', '=', 'policies.policy_id')
            ->leftJoin('user_accounts as approver', 'facility_reservation.approved_by', '=', 'approver.user_id') // Join for Approved by
            ->leftJoin('user_accounts as releaser', 'facility_reservation.released_by', '=', 'releaser.user_id') // Join for Released to
            ->where('facility_reservation.id', $facilityReservationId)
            ->where('facility_reservation.user_id', $userId)
            ->select(
                'facility_reservation.*',
                'facilities.facility_name as name',
                'facility_reservation.remarks',
                'facilities.department_owner',
                'facilities.location',
                'facilities.facilities_id',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'approver.first_name as approved_by_first_name',
                'approver.last_name as approved_by_last_name',
                'releaser.first_name as released_by_first_name',
                'releaser.last_name as released_by_last_name',
                'policies.policy_name',
                'policies.policy_content',
                'policies.inclusions',
            )
            ->first();

        if (!$facility) {
            return redirect()->route('dashboard')->with('error', 'Reservation not Found.');
        }

        // Format dates
        $facility->start_datetime = \Carbon\Carbon::parse($facility->start_datetime)->format('F j, Y g:i A');
        $facility->end_datetime = \Carbon\Carbon::parse($facility->end_datetime)->format('F j, Y g:i A');
        $facility->created_at = \Carbon\Carbon::parse($facility->created_at)->format('F j, Y g:i A');

        $cms = $this->cms();

        return view('login.users.facility_overview', compact('userData', 'cms', 'facility'));
    }

    public function reservation_calendar(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $userData = [
            'user_id' => $userId,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
        ];

        $cms = $this->cms();

        $reserved_items = DB::table('user_reservation_requests')
            ->join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('resource_type', 'user_reservation_requests.resource_type', '=', 'resource_type.category_id')
            ->join('departments', 'resources.department_owner', '=', 'departments.department_name')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->where('departments.department_name', $cms['dept'])
            ->where('user_reservation_requests.user_id', $userId)
            ->whereIn('user_reservation_requests.status', ['2', '3', '5'])
            ->select(
                'resources.resource_name',
                'user_reservation_requests.resource_id',
                'user_reservation_requests.pickup_datetime as start',
                'user_reservation_requests.return_datetime as end',
                'reservation_status.status_state as status',
                'resource_type.resource_type as type',
                'user_accounts.first_name',
                'user_accounts.last_name',
            )->unionAll(
                DB::table('facility_reservation')
                    ->join('user_accounts', 'facility_reservation.user_id', '=', 'user_accounts.user_id')
                    ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
                    ->join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
                    ->where('user_accounts.dept_name', $cms['dept'])
                    ->whereIn('facility_reservation.status', ['2', '3', '5'])
                    ->select(
                        'facilities.facility_name as resource_name',
                        'facility_reservation.id as resource_id',
                        'facility_reservation.start_datetime as start',
                        'facility_reservation.end_datetime as end',
                        'reservation_status.status_state as status',
                        DB::raw("'Facility' as type"),
                        'user_accounts.first_name',
                        'user_accounts.last_name',
                    )
            )
            ->get();


        return view('login.users.reservation_calendar', compact('userData', 'cms', 'reserved_items'));
    }

    public function account()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $userData = [
            'user_id' => $userId,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
            'email' => $user->email,

        ];

        $cms = $this->cms();

        return view('login.users.account', compact('userData', 'cms'));
    }


    // Post Functions
    public function equipment_request(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->user_type == 3) {
                return redirect()->route('login');
            }
            $userId = $user->user_id;
            $resource_type = Resource_Type::find(1); //Equipment

            $validate = $request->validate(
                [
                    'items' => 'required|array',
                    'professor' => 'required_if:position,Student|int',
                    'purpose' => 'required',
                    'subject' => 'required|max:100',
                    'section' => 'required|regex:/^[0-9]+$/|max:100',
                    'schedule' => 'required|max:100',
                    'group_members' => 'nullable|max:2048',
                    'pickup_datetime' => 'required|after_or_equal:now',
                    'return_datetime' => 'required|after:pickup_datetime',
                ],
                ['professor.required_if' => 'Professor is required and must be registered in the system']
            );

            $professor = ($user->position == 'Faculty') ? $userId : $validate['professor'];

            // Retrieve the max duration for the department
            $department = Session::get('dept_name');
            $maxDuration = cms_reservation_duration::where('department', $department)
                ->where('cms_resource_type', $resource_type->resource_type)
                ->value('duration');

            // Maximum Duration
            $pickupDate = Carbon::parse($validate['pickup_datetime']);
            $returnDate = Carbon::parse($validate['return_datetime']);

            // Calculate duration in hours
            $durationInHours = $pickupDate->diffInHours($returnDate);

            if ($durationInHours > $maxDuration) {
                return redirect()->route('equipment.form')
                    ->with('error', "The borrowing duration must not exceed $maxDuration hours.")
                    ->withInput();
            }

            // Loop through each item
            foreach ($validate['items'] as $index => $item) {

                // Extraction from Dropdown
                $resourceDetails = explode('|', $item);
                $resource_id = (int) $resourceDetails[0];
                $resource_name = $resourceDetails[1];
                $imagePath = $resourceDetails[2];

                $resource = resources::where('resource_id', $resource_id)->first();
                $serial_number = $resource->serial_number ?? null;

                if ($resource) {

                    // Check for existing conflicting reservations
                    $conflictingReservations = user_reservation_requests::where('resource_id', $resource_id)
                        ->whereIn('status', [3, 5]) // Approved or On-Going
                        ->where(function ($query) use ($pickupDate, $returnDate) {
                            $query->whereBetween('pickup_datetime', [$pickupDate, $returnDate])
                                ->orWhereBetween('return_datetime', [$pickupDate, $returnDate])
                                ->orWhere(function ($subQuery) use ($pickupDate, $returnDate) {
                                    $subQuery->where('pickup_datetime', '<=', $pickupDate)
                                        ->where('return_datetime', '>=', $returnDate);
                                });
                        })
                        ->get();

                    if ($conflictingReservations->isNotEmpty()) {
                        // Format conflicting reservations for display
                        $conflictList = $conflictingReservations->map(function ($conflict) {
                            return Carbon::parse($conflict->pickup_datetime)->format('M d, Y h:i A') . ' - ' .
                                Carbon::parse($conflict->return_datetime)->format('M d, Y h:i A');
                        })->toArray();

                        // Redirect back with error, conflict list, and input
                        return redirect()->route('equipment.form')->with('conflictList', $conflictList)
                            ->withInput();
                    }

                    // Check if the current logged user has already requested this item with status 2(Pending), 3(Approved), or 5(On-Going)
                    $existingRequest = user_reservation_requests::where('user_id', $userId)
                        ->where('resource_id', $resource_id)
                        ->whereIn('status', [2, 3, 5])
                        ->first();

                    if ($existingRequest) {
                        return redirect()->route('equipment.form')->with('error', 'You cannot request this item again, finish your transaction with this item first.')->withInput();
                    }

                    $transactionId = rand(100, 99999);
                    user_reservation_requests::create([
                        'transaction_id' => $transactionId,
                        'user_id' => $userId,
                        'resource_id' => $resource_id,
                        'resource_type' => $resource_type->category_id,
                        'serial_number' => $serial_number,
                        'group_members' => $validate['group_members'],
                        'subject' => $validate['subject'],
                        'section' => $validate['section'],
                        'schedule' => $validate['schedule'],
                        'professor' => $professor,
                        'purpose' => $validate['purpose'],
                        'pickup_datetime' => $validate['pickup_datetime'],
                        'return_datetime' => $validate['return_datetime']
                    ]);
                } else {
                    return redirect()->route('equipment.form')->withInput()->with('error', 'No Resource Found');
                }
            }

            return redirect()->route('dashboard')->with('success', 'Requested Successfully!');
        } catch (\Exception $e) {
            return redirect()->route('equipment.form')->withInput()->with('error', $e->getMessage());
        }
    }

    public function lab_request(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->user_type == 3) {
                return redirect()->route('login');
            }
            $userId = $user->user_id;
            $resource_type = Resource_Type::find(3); //Laboratory

            $validate = $request->validate(
                [
                    'items' => 'required|array',
                    'professor' => 'required_if:position,Student|int',
                    'purpose' => 'required',
                    'subject' => 'required|max:100',
                    'section' => 'required|regex:/^[0-9]+$/|max:100',
                    'schedule' => 'required|max:100',
                    'group_members' => 'nullable|max:100',
                    'pickup_datetime' => 'required|after_or_equal:now',
                    'return_datetime' => 'required|after:pickup_datetime',
                ],
                ['professor.required_if' => 'Professor is required and must be registered in the system']
            );

            $professor = ($user->position == 'Faculty') ? $userId : $validate['professor'];

            // Retrieve the max duration for the department
            $department = Session::get('dept_name');
            $maxDuration = cms_reservation_duration::where('department', $department)
                ->where('cms_resource_type', $resource_type->resource_type)
                ->value('duration');

            // Maximum Duration
            $pickupDate = Carbon::parse($validate['pickup_datetime']);
            $returnDate = Carbon::parse($validate['return_datetime']);

            // Calculate duration in hours
            $durationInHours = $pickupDate->diffInHours($returnDate);

            if ($durationInHours > $maxDuration) {
                return redirect()->route('lab.form')
                    ->with('error', "The borrowing duration must not exceed $maxDuration hours.")
                    ->withInput();
            }

            // Loop through each item
            foreach ($validate['items'] as $index => $item) {

                // Extraction from Dropdown
                $resourceDetails = explode('|', $item);
                $resource_id = (int) $resourceDetails[0];
                $resource_name = $resourceDetails[1];
                $imagePath = $resourceDetails[2];

                $resource = resources::where('resource_id', $resource_id)->first();
                $serial_number = $resource->serial_number ?? null;

                if ($resource) {
                    // Check if the current logged user has already requested this item with status 2(Pending), 3(Approved), or 5(On-Going)
                    $existingRequest = user_reservation_requests::where('user_id', $userId)
                        ->where('resource_id', $resource_id)
                        ->whereIn('status', [2, 3, 5])
                        ->first();

                    if ($existingRequest) {
                        return redirect()->route('lab.form')->with('error', 'You cannot request this item again, finish your transaction with this item first.')->withInput();
                    }

                    $transactionId = rand(100, 99999);
                    user_reservation_requests::create([
                        'transaction_id' => $transactionId,
                        'user_id' => $userId,
                        'resource_id' => $resource_id,
                        'resource_type' => $resource_type->category_id,
                        'serial_number' => $serial_number,
                        'group_members' => $validate['group_members'],
                        'subject' => $validate['subject'],
                        'section' => $validate['section'],
                        'schedule' => $validate['schedule'],
                        'professor' => $professor,
                        'purpose' => $validate['purpose'],
                        'pickup_datetime' => $validate['pickup_datetime'],
                        'return_datetime' => $validate['return_datetime']
                    ]);
                } else {
                    return redirect()->route('lab.form')->withInput()->with('error', 'No Resource Found');
                }
            }
            return redirect()->route('dashboard')->with('success', 'Requested Successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function facility_request(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->user_type == 3 || $user->position == 'Student') {
                return redirect()->route('login');
            }

            $userId = $user->user_id;
            $transactionId = rand(100, 99999);
            $resource_type = Resource_Type::find(2); //Facility

            $validate = $request->validate([
                'facilities' => 'required',
                'purpose' => 'required|max:45',
                'start_datetime' => 'required|after_or_equal:now',
                'end_datetime' => 'required|after:pickup_datetime',
            ]);

            // Retrieve the max duration for the department
            $department = Session::get('dept_name');
            $maxDuration = cms_reservation_duration::where('department', $department)
                ->where('cms_resource_type', $resource_type->resource_type)
                ->value('duration');

            // Maximum Duration
            $pickupDate = Carbon::parse($validate['start_datetime']);
            $returnDate = Carbon::parse($validate['end_datetime']);

            if ($pickupDate->diffInDays($returnDate) > $maxDuration) {
                return redirect()->route('facility.form')->with('error', "The Return Date Must be Within $maxDuration Days of the Start Date.")->withInput();
            }

            $splitItems = explode('|', $validate['facilities']);

            // Extract resource ID and convert it to an integer
            $facility_id = (int) $splitItems[0];
            $facility_name = $splitItems[1];

            // Check if the user already has a reservation with status 2, 3, or 5
            $existingReservation = Facility_Reservation::where('user_id', $userId)
                ->where('facility_name', $facility_id)
                ->whereIn('status', [2, 3, 5])
                ->first();

            if ($existingReservation) {
                return redirect()->route('facility.form')->with('error', 'You already have a pending or active reservation for this facility.')->withInput();
            }

            // Check Availability
            $facility = Facility::where('facilities_id', $facility_id)->first();
            $is_available = Facility::pluck('is_available')->first();

            // Check for existing conflicting reservations
            $conflictingReservations = Facility_Reservation::where('facility_name', $facility_id)
                ->whereIn('status', [3, 5]) // Approved or On-Going
                ->where(function ($query) use ($pickupDate, $returnDate) {
                    $query->whereBetween('start_datetime', [$pickupDate, $returnDate])
                        ->orWhereBetween('end_datetime', [$pickupDate, $returnDate])
                        ->orWhere(function ($subQuery) use ($pickupDate, $returnDate) {
                            $subQuery->where('start_datetime', '<=', $pickupDate)
                                ->where('end_datetime', '>=', $returnDate);
                        });
                })
                ->get();

            if ($conflictingReservations->isNotEmpty()) {
                // Format conflicting reservations for display
                $conflictList = $conflictingReservations->map(function ($conflict) {
                    return Carbon::parse($conflict->start_datetime)->format('M d, Y h:i A') . ' - ' .
                        Carbon::parse($conflict->end_datetime)->format('M d, Y h:i A');
                })->toArray();

                // Redirect back with error, conflict list, and input
                return redirect()->route('facility.form')->with('conflictList', $conflictList)
                    ->withInput();
            }

            if ($facility && $is_available == 1) {

                Facility_Reservation::create([
                    'id' => $transactionId,
                    'user_id' => $userId,
                    'facility_name' => $facility_id,
                    'purpose' => $validate['purpose'],
                    'start_datetime' => $validate['start_datetime'],
                    'end_datetime' => $validate['end_datetime']
                ]);

                return redirect()->route('dashboard')->with('success', 'Requested Successfully!');
            } else {
                return redirect()->route('facility.form')->withInput()->with('error', 'Requested is Currently Facility Not Available');
            }
        } catch (\Exception $e) {
            return redirect()->route('facility.form')->withInput()->with('error', $e->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->user_type == 3) {
                return redirect()->route('login');
            }

            $transactionId = $request->input('transaction_id');
            $action = 'cancel';

            $reservationRequest = user_reservation_requests::where('transaction_id', $transactionId)->first();

            if ($reservationRequest && ($reservationRequest->status == '2' || $reservationRequest->status == '3')) {
                if ($action === 'cancel') {
                    $reservationRequest->status = '8';
                    $reservationRequest->save();

                    $resource = resources::where('resource_id', $reservationRequest->resource_id)->first();
                    if ($resource) {
                        $resource->status = 1;
                        $resource->save();
                    }

                    return redirect()->route('dashboard')->with('success', 'Reservation Cancelled');
                } else {
                    return redirect()->route('dashboard')->with('error', 'Request Error');
                }
            } else {
                return redirect()->route('dashboard')->with('error', 'Reservation Not Found');
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Reservation Not Found');
        }
    }

    public function facility_cancel(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->user_type == 3) {
                return redirect()->route('login');
            }

            $transactionId = $request->input('transaction_id');
            $action = 'cancel';

            $reservationRequest = Facility_Reservation::where('id', $transactionId)->first();

            if ($reservationRequest && ($reservationRequest->status == '2' || $reservationRequest->status == '3')) {
                if ($action === 'cancel') {
                    $reservationRequest->status = '8';
                    $reservationRequest->save();

                    return redirect()->route('dashboard')->with('success', 'Facility Reservation Cancelled');
                } else {
                    return redirect()->route('dashboard')->with('error', 'Request Error');
                }
            } else {
                return redirect()->route('dashboard')->with('error', 'Facility Reservation Not Found');
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Facility Reservation Not Found');
        }
    }

    public function feedback(Request $request)
    {

        $user = Auth::user();
        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $resourceId = $request->input('resource_id');
        $feedback = $request->input('comment');
        $rating = $request->input('rating');

        try {

            $request->validate([
                'resource_id' => 'required|integer',
                'comment' => 'max:5000',
                'rating' => 'required|min:1|max:5',
            ]);

            DB::beginTransaction();

            $newFeedback = feedback::create([
                'username' => $user->user_id,
                'feedback' => $feedback,
                'resource_id' => $resourceId,
                'rating' => $rating,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update Average Rating for the Resource
            $resource = resources::find($resourceId);
            $averageRating = $resource->feedbacks()->avg('rating');
            $resource->rating = $averageRating;
            $resource->save();

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Feedback Added!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', $e->getMessage());

            // Prevent the page from reloading when the form is submitted
            return redirect()->back()->withInput()->with('modal', 'true');
        }
    }

    public function change_password(Request $request)
    {
        $user = Auth::user();

        // Redirect to login if user is not authenticated or user type is 3
        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        // Validation rules using the Request's validate() method
        $request->validate([
            'old_pass' => 'required',
            'new_pass' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
            'confirm_pass' => 'required|same:new_pass',
        ], [
            'old_pass.required' => 'Please enter your old password.',
            'new_pass.required' => 'Please enter a new password.',
            'new_pass.min' => 'New password must be at least 8 characters long.',
            'new_pass.regex' => 'New password must include an uppercase letter, a lowercase letter, a number, and a special character.',
            'confirm_pass.required' => 'Please confirm your new password.',
            'confirm_pass.same' => 'New password and confirmation do not match.',
        ]);

        // Verify that the old password matches the current password
        if (!Hash::check($request->old_pass, $user->password)) {
            return redirect()->route('account')->with('info', 'Old password does not match. Try again.');
        }

        // Update the password
        DB::table('user_accounts')
            ->where('user_id', $user->user_id)
            ->update(['password' => Hash::make($request->new_pass)]);

        // Redirect back with a success message
        return redirect()->route('account')->with('success', 'Password changed successfully!');
    }

    public function image_upload(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $cms = $this->cms();

        // Handle reset request
        if ($request->has('reset_image')) {
            try {
                // Reset logic is handled in the `resetImage` function
                return $this->resetImage($request);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'An error occurred while resetting the image.'], 500);
            }
        }

        // Proceed with image upload
        if (!$request->hasFile('emblem')) {
            return response()->json(['success' => false, 'message' => 'No image was uploaded.'], 400);
        }

        try {
            // Validate the image upload
            $request->validate([
                'emblem' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maximum file size of 2MB
            ]);

            // Image Upload
            $image = $request->file('emblem');

            $userId = sanitizeFilename($request->user()->user_id);
            $lastName = sanitizeFilename($request->user()->last_name);
            $deptLabel = sanitizeFilename($cms['dept_label']);

            // Get current image path from database
            $currentImagePath = DB::table('user_accounts')->where('user_id', $userId)->value('profile_pic');

            $defaultImagePath = 'storage/assets/default_image.png';

            // Delete the existing image if it's not the default image
            if ($currentImagePath && $currentImagePath !== $defaultImagePath) {
                $publicImagePath = str_replace('storage/', 'public/', $currentImagePath);

                if (Storage::exists($publicImagePath)) {
                    Storage::delete($publicImagePath);
                }
            }

            // Generate new file name
            $timestamp = now()->timestamp;
            $fileName = $userId . '_' . $lastName . '_' . $timestamp . '.' . $image->getClientOriginalExtension();

            // Store the new image
            $path = $image->storeAs('public/' . $deptLabel . '/user_profiles', $fileName);

            // Convert the storage path to public path
            $path = str_replace('public/', 'storage/', $path);

            // Update the image path in the database
            DB::table('user_accounts')
                ->where('user_id', $userId)
                ->update(['profile_pic' => $path]);

            return response()->json(['success' => true, 'message' => 'Image Uploaded Successfully.']);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['success' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
        }
    }

    public function resetImage(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        try {
            // Define the path to the default image
            $defaultImagePath = 'storage/assets/default_image.png';

            // Get the user's current image path from the database
            $currentImagePath = DB::table('user_accounts')->where('user_id', $user->user_id)->value('profile_pic');

            // If the current image is not the default, delete it
            if ($currentImagePath && $currentImagePath !== $defaultImagePath) {
                $publicImagePath = str_replace('storage/', 'public/', $currentImagePath);

                if (Storage::exists($publicImagePath)) {
                    Storage::delete($publicImagePath);
                }
            }

            // Update the user's profile_pic to the default image path
            DB::table('user_accounts')
                ->where('user_id', $user->user_id)
                ->update(['profile_pic' => $defaultImagePath]);

            return response()->json(['success' => true, 'message' => 'Image reset to default.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while resetting the image.'], 500);
        }
    }

    // Faculty Feature
    public function noted_by(Request $request)
    {
        $user = Auth::user();
        $userData = null;
        $cms = $this->cms();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userData = [
            'user_id' => $user->user_id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
        ];

        $resource_id = $request->input('resource_id');

        $query = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('user_accounts as professor', 'user_reservation_requests.professor', '=', 'professor.user_id') // Join for the professor's user account
            ->join('user_accounts as requester', 'user_reservation_requests.user_id', '=', 'requester.user_id') // Join for the requester's user account
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->where('user_reservation_requests.professor', $user->user_id) // Filter by professor's user_id
            ->where('user_reservation_requests.status', '=', 2)
            ->where('user_reservation_requests.user_id', '!=', $user->user_id)
            ->whereNull('user_reservation_requests.noted_by');

        if ($resource_id) {
            $query->where('resources.resource_id', $resource_id);
        }

        // Get the count of requests
        $requests_count = $query->count();

        // Retrieve the requests data
        $requests = $query->select(
            'user_reservation_requests.*',
            'resources.resource_name',
            'resources.department_owner',
            'professor.first_name as professor_first_name',  // Professor's first name
            'professor.last_name as professor_last_name',    // Professor's last name
            'requester.first_name as requester_first_name',  // Requester's first name
            'requester.last_name as requester_last_name',    // Requester's last name
            'requester.position',
            'reservation_status.status_state',
            'reservation_status.status_color'
        )
            ->orderBy('user_reservation_requests.created_at', 'DESC')
            ->get();

        return view('login.users.noted_by', compact('userData', 'cms', 'requests', 'requests_count'));
    }

    public function faculty_overview(Request $request, $transactionId)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $userData = [
            'user_id' => $userId,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_pic' => $user->profile_pic,
            'position' => $user->position,
        ];

        // Fetch the transaction details
        $transaction = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->leftJoin('policies', 'resources.policy_id', '=', 'policies.policy_id')
            ->leftJoin('user_accounts as approver', 'user_reservation_requests.approved_by', '=', 'approver.user_id') // Join for Approved by
            ->leftJoin('user_accounts as returner', 'user_reservation_requests.returned_to', '=', 'returner.user_id') // Join for Returned to
            ->where('user_reservation_requests.transaction_id', $transactionId)
            ->where('user_reservation_requests.professor', $userId)
            ->select(
                'user_reservation_requests.*',
                'resource_type.resource_type',
                'resources.resource_name as name',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'policies.policy_name',
                'policies.policy_content',
                'policies.inclusions',
                'approver.first_name as approved_by_first_name',  // Fetch Approved by first name
                'approver.last_name as approved_by_last_name',    // Fetch Approved by last name
                'returner.first_name as returned_to_first_name',  // Fetch Returned to first name
                'returner.last_name as returned_to_last_name'     // Fetch Returned to last name
            )
            ->first();

        $professor = user_reservation_requests::join('user_accounts', 'user_reservation_requests.professor', '=', 'user_accounts.user_id')->first();

        if (!$transaction) {
            return redirect()->route('noted_by')->with('error', 'Transaction not Found.');
        }

        // Fetch the department owner
        $department_owner = DB::table('resources')
            ->where('resource_id', $transaction->resource_id)
            ->value('department_owner');

        // Check if feedback already exists
        $feedbackExists = DB::table('user_feedback')
            ->where('username', $userId)
            ->where('resource_id', $transaction->resource_id)
            ->exists();

        // Format dates
        $transaction->pickup_datetime = \Carbon\Carbon::parse($transaction->pickup_datetime)->format('F j, Y g:i A');
        $transaction->return_datetime = \Carbon\Carbon::parse($transaction->return_datetime)->format('F j, Y g:i A');
        $transaction->created_at = \Carbon\Carbon::parse($transaction->created_at)->format('F j, Y g:i A');

        $cms = $this->cms();

        return view('login.users.faculty_overview', compact(
            'userData',
            'cms',
            'transaction',
            'department_owner',
            'feedbackExists',
            'professor'
        ));
    }

    public function approve(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userType = $user->userType->user_type;
        $position = $user->position;

        $cms = $this->cms();

        $reservationRequest = user_reservation_requests::find($request->reservation_id);

        $reservationRequest->update([
            'noted_by' => $user->user_id,
        ]);

        audit::create([
            'action' => 'Approved Request',
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_type' => $userType . ' ' . $position,
            'user_id' => $user->user_id,
            'action_type' => 'Noted By',
            'department' => $cms['dept'],
        ]);

        return redirect()->route('noted_by')->with('success', 'Request Approved Successfully');
    }

    public function reject(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 3) {
            return redirect()->route('login');
        }

        $userType = $user->userType->user_type;
        $position = $user->position;

        $cms = $this->cms();

        $reservationRequest = user_reservation_requests::find($request->reservation_id);

        $reservationRequest->update([
            'noted_by' => null,
            'status' => 4
        ]);

        audit::create([
            'action' => 'Rejected Request',
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_type' => $userType . ' ' . $position,
            'user_id' => $user->user_id,
            'action_type' => 'Noted By',
            'department' => $cms['dept'],
        ]);

        return redirect()->route('noted_by')->with('success', 'Request Rejected');
    }
}
