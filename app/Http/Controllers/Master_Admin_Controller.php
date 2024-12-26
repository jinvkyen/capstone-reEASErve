<?php

namespace App\Http\Controllers;

use App\Mail\FacilityReservationReminder;
use App\Mail\SendFeedbackEmail;
use App\Mail\SendFeedbackEmailforLate;
use App\Mail\UserReservationReminder;
use App\Models\approve_reject_analytics;
use App\Models\archive_feedback;
use App\Models\audit;
use App\Models\audits_archive;
use App\Models\cms_reservation_duration;
use App\Models\cms;
use App\Models\cms_about;
use App\Models\departments;
use App\Models\Facility_Reservation;
use App\Models\Policy;
use App\Models\Reservation_Status;
use App\Models\Resource_Type;
use App\Models\resources;
use App\Models\Facility;
use App\Models\feedback;
use App\Models\general_policies;
use App\Models\user_accounts;
use App\Models\user_reservation_requests;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class Master_Admin_Controller extends Controller
{
    // Helpers
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

    // Sanitize the Incoming File Names
    function sanitizeFilename($string)
    {
        $string = str_replace(' ', '_', $string);
        return preg_replace('/[^A-Za-z0-9_]/', '', $string);
    }

    public function formatDataWithImage($data, $imagePaths, $imageKey = 'resource_id')
    {
        foreach ($data as $item) {
            if (isset($item->pickup_datetime)) {
                $item->pickup_datetime = $this->formatDatetime($item->pickup_datetime);
            }
            if (isset($item->return_datetime)) {
                $item->return_datetime = $this->formatDatetime($item->return_datetime);
            }
            if (isset($item->created_at)) {
                $item->created_at = $this->formatDatetime($item->created_at);
            }
            $item->image = $imagePaths[$item->{$imageKey}] ?? null;
        }
    }

    public function formatDatetime($datetime)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('F j, Y g:i A');
    }


    // Views
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        // Resource Type Chart (Including Facility Reservations)
        $userReservationRequests = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->select('user_reservation_requests.*', 'resource_type.resource_type')
            ->where('resources.department_owner', $cms['dept'])
            ->orderBy('user_reservation_requests.created_at', 'asc')
            ->get();

        $facilityReservations = Facility_Reservation::join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
            ->select('facility_reservation.*', DB::raw("'Facilities' as resource_type"))
            ->where('facilities.department_owner', $cms['dept'])
            ->orderBy('facility_reservation.created_at', 'asc')
            ->get();

        // Merge the two collections
        $allReservations = $userReservationRequests->merge($facilityReservations);

        // Sort the merged collection by created_at using object properties
        $allReservations = $allReservations->sortBy(function ($reservation) {
            return strtotime($reservation->created_at);
        });

        // Initialize the counts by type and by date as a collection
        $countsByType = collect([
            'Equipment' => collect(),
            'Laboratory' => collect(),
            'Facilities' => collect(),
        ]);

        // Format dates and accumulate reservation counts per day and per type
        foreach ($allReservations as $request) {
            $resource_type = $request->resource_type;
            $createdAt = new DateTime($request->created_at);
            $createdAtFormatted = $createdAt->format('F j, Y');

            // Use the collection's put() method to modify elements
            $currentCount = $countsByType[$resource_type]->get($createdAtFormatted, 0);
            $countsByType[$resource_type]->put($createdAtFormatted, $currentCount + 1);
        }

        // Prepare the labels (unique dates) for the x-axis
        $labels = $allReservations->map(function ($reservation) {
            return (new DateTime($reservation->created_at))->format('F j, Y');
        })->unique()->values()->all();

        // Initialize the data array for each resource type
        $dataArray = $countsByType->map(function ($counts, $resource_type) use ($labels) {
            $countsWithZeroes = [];

            // Ensure there is a count (or zero) for each date in the labels
            foreach ($labels as $label) {
                $countsWithZeroes[] = $counts->get($label, 0);  // If no data for this date, set to 0
            }

            return [
                'label' => $resource_type,
                'data' => $countsWithZeroes, // Ensure counts match the labels
            ];
        })->values()->all();
        // End of Bar Chart


        // Fetching daily approved requests from the approve_reject_analytics table
        $approved_requests = approve_reject_analytics::join('user_reservation_requests', 'approve_reject_analytics.request_id', '=', 'user_reservation_requests.transaction_id')
            ->join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->select(DB::raw('DATE(approve_reject_analytics.changed_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('resources.department_owner', $cms['dept'])
            ->whereIn('approve_reject_analytics.status', ['3'])
            ->groupBy(DB::raw('DATE(approve_reject_analytics.changed_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // Fetching daily rejected requests from the approve_reject_analytics table
        $rejected_requests = approve_reject_analytics::join('user_reservation_requests', 'approve_reject_analytics.request_id', '=', 'user_reservation_requests.transaction_id')
            ->join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->select(DB::raw('DATE(approve_reject_analytics.changed_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('resources.department_owner', $cms['dept'])
            ->where('approve_reject_analytics.status', '4')
            ->groupBy(DB::raw('DATE(approve_reject_analytics.changed_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // Combine data into an array format suitable for the chart
        $dates = $approved_requests->pluck('date')->merge($rejected_requests->pluck('date'))
            ->unique()
            ->sort()
            ->map(function ($date) {
                return (new DateTime($date))->format('F j, Y'); // Convert to F j, Y format
            })
            ->values();

        $approved_data = [];
        $rejected_data = [];

        foreach ($dates as $formattedDate) {
            $rawDate = (new DateTime($formattedDate))->format('Y-m-d');

            $approved_data[] = $approved_requests->firstWhere('date', $rawDate)?->total ?? 0;
            $rejected_data[] = $rejected_requests->firstWhere('date', $rawDate)?->total ?? 0;
        }

        $reservationData = [
            'labels' => $dates,
            'approved' => $approved_data,
            'rejected' => $rejected_data
        ];
        // End of Approve-Reject Statistics

        // Analytics Cards
        $pending = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->where('resources.department_owner', $cms['dept'])
            ->where('user_reservation_requests.status', '2')
            ->count();

        $approved = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->where('resources.department_owner', $cms['dept'])
            ->where('user_reservation_requests.status', '3')
            ->count();

        $ongoing = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->where('resources.department_owner', $cms['dept'])
            ->where('user_reservation_requests.status', '5')
            ->count();

        $replace = DB::table('resources')
            ->where('department_owner', $cms['dept'])
            ->where(function ($query) {
                $query->where('status', 'for replacement')
                    ->orWhere('status', 7);
            })
            ->count();

        // End of Analytics Cards

        // General Policies
        $policies = DB::table('general_policies')
            ->where('dept_owner', $cms['dept'])
            ->get();
        // End of General Policies

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $facilities = Facility::with(['reservations' => function ($query) {
            $query->whereIn('facility_reservation.status', [3, 5]) // Approved or On-Going
                ->orderBy('end_datetime', 'desc');
        }])
            ->where('facilities.department_owner', $cms['dept'])
            ->select('facilities.*', 'facilities.status as facility_status')
            ->get();


        return view('login.masteradmin.master_dashboard', compact(
            'userData',
            'cms',
            'dataArray',
            'pending',
            'approved',
            'ongoing',
            'replace',
            'reservationData',
            'policies',
            'facilities',
            'labels'
        ));
    }

    public function analytics_range(Request $request)
    {
        $cms = $this->cms();

        $dateRange = $request->input('range', 7); // Default to 7 days

        $startDate = now()->subDays($dateRange)->startOfDay();
        $endDate = now()->endOfDay();

        // Filter Resource Type Chart Data
        $userReservationRequests = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->select('user_reservation_requests.*', 'resource_type.resource_type')
            ->where('resources.department_owner', $cms['dept'])
            ->whereBetween('user_reservation_requests.created_at', [$startDate, $endDate])
            ->get();

        $facilityReservations = Facility_Reservation::join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
            ->select('facility_reservation.*', DB::raw("'Facilities' as resource_type"))
            ->where('facilities.department_owner', $cms['dept'])
            ->whereBetween('facility_reservation.created_at', [$startDate, $endDate])
            ->get();

        $allReservations = $userReservationRequests->merge($facilityReservations)->sortBy('created_at');

        // Prepare data for Resource Type Chart
        $countsByType = collect([
            'Equipment' => collect(),
            'Laboratory' => collect(),
            'Facilities' => collect(),
        ]);

        foreach ($allReservations as $request) {
            $resource_type = $request->resource_type;
            $createdAtFormatted = (new DateTime($request->created_at))->format('F j, Y');
            $currentCount = $countsByType[$resource_type]->get($createdAtFormatted, 0);
            $countsByType[$resource_type]->put($createdAtFormatted, $currentCount + 1);
        }

        $labels = $allReservations->map(function ($reservation) {
            return (new DateTime($reservation->created_at))->format('F j, Y');
        })->unique()->values()->all();

        $dataArray = $countsByType->map(function ($counts, $resource_type) use ($labels) {
            $countsWithZeroes = [];
            foreach ($labels as $label) {
                $countsWithZeroes[] = $counts->get($label, 0);
            }
            return ['label' => $resource_type, 'data' => $countsWithZeroes];
        })->values()->all();

        // Prepare data for Daily Requests Chart
        $approved_requests = approve_reject_analytics::join('user_reservation_requests', 'approve_reject_analytics.request_id', '=', 'user_reservation_requests.transaction_id')
            ->join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->select(DB::raw('DATE(approve_reject_analytics.changed_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('resources.department_owner', $cms['dept'])
            ->whereIn('approve_reject_analytics.status', ['3'])
            ->whereBetween('approve_reject_analytics.changed_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $rejected_requests = approve_reject_analytics::join('user_reservation_requests', 'approve_reject_analytics.request_id', '=', 'user_reservation_requests.transaction_id')
            ->join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->select(DB::raw('DATE(approve_reject_analytics.changed_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('resources.department_owner', $cms['dept'])
            ->where('approve_reject_analytics.status', '4')
            ->whereBetween('approve_reject_analytics.changed_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $approved_requests->pluck('date')->merge($rejected_requests->pluck('date'))
            ->unique()
            ->sort()
            ->map(function ($date) {
                return (new DateTime($date))->format('F j, Y'); // Convert to F j, Y format
            })
            ->values();

        // Initialize the formatted arrays for approved and rejected data
        $approved_data = [];
        $rejected_data = [];

        foreach ($dates as $formattedDate) {
            // Convert formatted date back to raw date for comparison
            $rawDate = (new DateTime($formattedDate))->format('Y-m-d');

            $approved_data[] = $approved_requests->firstWhere('date', $rawDate)?->total ?? 0;
            $rejected_data[] = $rejected_requests->firstWhere('date', $rawDate)?->total ?? 0;
        }

        return response()->json([
            'resourceType' => [
                'labels' => $labels,
                'datasets' => $dataArray,
            ],
            'dailyRequests' => [
                'labels' => $dates, // Formatted F j, Y dates
                'approved' => $approved_data,
                'rejected' => $rejected_data,
            ],
        ]);
    }


    public function department_requests(Request $request)
    {
        // Authenticate the user
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        // Extract user data
        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;
        $position = $user->position;


        // User data array for view
        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic,
            'position' => $profile_pic,
        ];

        // Get sorting parameter from request
        $sortBy = $request->query('sort_by');

        // Fetch facilities owned by the department
        $admin_facilities = Facility_Reservation::join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
            ->join('user_accounts', 'facility_reservation.user_id', '=', 'user_accounts.user_id')
            ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
            ->whereIn('facility_reservation.status', [1, 2, 3, 5, 7, 12, 13])
            ->where('facilities.department_owner', '=', $cms['dept'])
            ->whereNull('facility_reservation.deleted_at')
            ->select(
                'facility_reservation.*',
                'facilities.*',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.user_id',
                'user_accounts.position',
                'reservation_status.status_id',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('facilities.department_owner', $cms['dept'])
            ->orderByRaw("FIELD(facility_reservation.status, 5, 3, 2) DESC")
            ->orderBy('facility_reservation.created_at', 'desc')
            ->get();

        // Fetch reservation requests (equipment and lab items) owned by the department
        $admin_requestsQuery = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->leftJoin('user_accounts as professor', 'user_reservation_requests.noted_by', '=', 'professor.user_id')
            ->whereIn('user_reservation_requests.status', [1, 2, 3, 5, 7, 12, 13])
            ->where('resources.department_owner', '=', $cms['dept'])
            ->whereNull('user_reservation_requests.deleted_at')
            ->select(
                'user_reservation_requests.*',
                'resources.resource_type',
                'resources.department_owner',
                'resources.resource_name',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.position',
                'user_accounts.user_id',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'professor.last_name as professor_last_name', // Professor's last name
                DB::raw("CONCAT(professor.last_name, ' ', professor.first_name) as professor_full_name") // Professor's full name
            )
            ->where('resources.department_owner', $cms['dept'])
            ->orderByRaw("FIELD(user_reservation_requests.status, 5, 3, 2) desc")
            ->orderBy('user_reservation_requests.created_at', 'desc');

        // Fetch all requests without separation or filtering
        $all_requests = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->whereIn('user_reservation_requests.status', [1, 2, 3, 5, 7, 12, 13])
            ->where('resources.department_owner', '=', $cms['dept'])
            ->whereNull('user_reservation_requests.deleted_at')
            ->where(function ($query) {
                $query->where('resources.resource_type', '!=', 3)
                    ->orWhereNotNull('user_reservation_requests.noted_by'); // Applies only when resource_type is 3
            })
            ->select(
                'user_reservation_requests.*',
                'resources.resource_type',
                'resources.department_owner',
                'resources.resource_name',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.user_id',
                'user_accounts.position',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('resources.department_owner', $cms['dept'])
            ->orderByRaw("FIELD(user_reservation_requests.status, 5, 3, 2) DESC")
            ->orderBy('user_reservation_requests.created_at', 'desc')
            ->get();

        // Sorting
        if (in_array($sortBy, ['resource_name_asc', 'resource_name_desc', 'created_at', 'created_at_desc'])) {
            switch ($sortBy) {
                case 'resource_name_asc':
                    $admin_requestsQuery->orderBy('resources.resource_name');
                    break;
                case 'resource_name_desc':
                    $admin_requestsQuery->orderByDesc('resources.resource_name');
                    break;
                case 'created_at':
                    $admin_requestsQuery->orderBy('user_reservation_requests.created_at');
                    break;
                case 'created_at_desc':
                    $admin_requestsQuery->orderByDesc('user_reservation_requests.created_at');
                    break;
            }
        }

        // Apply Filters
        $statusFilters = [
            'Returned' => 6,
            'Cancelled' => 8,
            'Pending' => 2,
            'Approved' => 3,
            'Rejected' => 4,
            'On-Going' => 5,
            'Missing' => 7,
            'Late' => 9
        ];

        foreach ($statusFilters as $filter => $status) {
            if (
                $request->input('filter') === $filter
            ) {
                $admin_requestsQuery->where('user_reservation_requests.status', '=', $status);
            }
        }

        if (
            $request->input('filter') === 'Today_pickup'
        ) {
            $admin_requestsQuery->whereDate('user_reservation_requests.pickup_datetime', '=', now()->format('Y-m-d'));
        } elseif ($request->input('filter') === 'Today_return') {
            $admin_requestsQuery->whereDate('user_reservation_requests.return_datetime', '=', now()->format('Y-m-d'));
        }

        // Get the filtered and sorted requests
        $admin_requests = $admin_requestsQuery->get();


        // Separate equipment and laboratory items
        $admin_equipment = $admin_requests->filter(function ($item) {
            return $item->resource_type == 1;
        });

        $admin_laboratory = $admin_requests->filter(function ($item) {
            return $item->resource_type == 3 && $item->noted_by !== null;
        });

        // Fetch images for reserved items
        $imagePaths = DB::table('resources')
            ->whereIn('resource_id', $admin_requests->pluck('resource_id'))
            ->pluck('image', 'resource_id')
            ->map(function ($imagePath) {
                return 'storage/' . $imagePath;
            });

        // Format date and image data for requests
        $this->formatDataWithImage($admin_requests, $imagePaths);

        // Format date and image data for facilities
        $this->formatDataWithImage($admin_facilities, $imagePaths);

        // Format date and image data for all requests
        $this->formatDataWithImage(
            $all_requests,
            $imagePaths
        );

        $combinedRequests = collect()
            ->merge($all_requests)
            ->merge($admin_equipment)
            ->merge($admin_laboratory)
            ->merge($admin_facilities)
            ->unique('status_state');


        // ADDED: Faculty Tab
        $admin_requests = $admin_requestsQuery->get();

        $noted_requests = $admin_requests->filter(function ($request) {
            return $request->noted_by === null && $request->position !== 'Faculty';
        });

        return view('login.masteradmin.master_reservation_requests', compact(
            'userData',
            'cms',
            'sortBy',
            'admin_equipment',
            'admin_laboratory',
            'admin_facilities',
            'all_requests',
            'combinedRequests',
            'noted_requests'
        ));
    }

    // ADDED! - Faculty Tab
    public function faculty_overview(Request $request, $transactionId)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
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

        // Refined professor query to ensure accuracy
        $professor = user_reservation_requests::join('user_accounts', 'user_reservation_requests.professor', '=', 'user_accounts.user_id')
            ->where('user_reservation_requests.transaction_id', $transactionId) // Ensure it's the correct transaction
            ->where('user_reservation_requests.user_id', $transaction->user_id) // Ensure the professor matches the user request
            ->select('user_accounts.first_name', 'user_accounts.last_name', 'user_accounts.user_id') // Select specific professor details
            ->first();

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

        return view('login.masteradmin.faculty_overview', compact(
            'userData',
            'cms',
            'transaction',
            'department_owner',
            'feedbackExists',
            'professor'
        ));
    }

    public function department_archived_requests(Request $request)
    {
        // Authenticate the user
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        // Extract user data
        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        // User data array for view
        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        // Get sorting parameter from request
        $sortBy = $request->query('sort_by');

        // Fetch facilities owned by the department
        $admin_facilities = Facility_Reservation::join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
            ->join('user_accounts', 'facility_reservation.user_id', '=', 'user_accounts.user_id')
            ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
            ->whereIn('facility_reservation.status', [4, 6, 8, 9, 14, 15])
            ->where('facilities.department_owner', '=', $cms['dept'])
            ->select(
                'facility_reservation.*',
                'facilities.*',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.user_id',
                'user_accounts.position',
                'reservation_status.status_id',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('facilities.department_owner', $cms['dept'])
            ->orderBy('facility_reservation.created_at', 'desc')
            ->get();

        // Fetch reservation requests (equipment and lab items) owned by the department
        $admin_requestsQuery = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->whereIn('user_reservation_requests.status', [4, 6, 8, 9, 14, 15])
            ->where('resources.department_owner', '=', $cms['dept'])
            ->select(
                'user_reservation_requests.*',
                'resources.resource_type',
                'resources.department_owner',
                'resources.resource_name',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.user_id',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('resources.department_owner', $cms['dept'])
            ->orderBy('user_reservation_requests.created_at', 'desc');

        // Fetch all requests without separation or filtering
        $all_requests = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->whereIn('user_reservation_requests.status', [4, 6, 8, 9, 14, 15])
            ->where('resources.department_owner', '=', $cms['dept'])
            ->select(
                'user_reservation_requests.*',
                'resources.resource_type',
                'resources.department_owner',
                'resources.resource_name',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.user_id',
                'user_accounts.position',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('resources.department_owner', $cms['dept'])
            ->orderBy('user_reservation_requests.created_at', 'desc')
            ->get();

        // Apply sorting if specified
        if (in_array($sortBy, ['resource_name_asc', 'resource_name_desc', 'created_at', 'created_at_desc'])) {
            switch ($sortBy) {
                case 'resource_name_asc':
                    $admin_requestsQuery->orderBy('resources.resource_name');
                    break;
                case 'resource_name_desc':
                    $admin_requestsQuery->orderByDesc('resources.resource_name');
                    break;
                case 'created_at':
                    $admin_requestsQuery->orderBy('user_reservation_requests.created_at');
                    break;
                case 'created_at_desc':
                    $admin_requestsQuery->orderByDesc('user_reservation_requests.created_at');
                    break;
            }
        }

        // Apply filters
        $statusFilters = [
            'Returned' => 6,
            'Cancelled' => 8,
            'Pending' => 2,
            'Approved' => 3,
            'Rejected' => 4,
            'On-Going' => 5,
            'Missing' => 7,
            'Late' => 9
        ];

        foreach ($statusFilters as $filter => $status) {
            if ($request->input('filter') === $filter) {
                $admin_requestsQuery->where('user_reservation_requests.status', '=', $status);
            }
        }

        if ($request->input('filter') === 'Today_pickup') {
            $admin_requestsQuery->whereDate('user_reservation_requests.pickup_datetime', '=', now()->format('Y-m-d'));
        } elseif ($request->input('filter') === 'Today_return') {
            $admin_requestsQuery->whereDate('user_reservation_requests.return_datetime', '=', now()->format('Y-m-d'));
        }

        // Get the filtered and sorted requests
        $admin_requests = $admin_requestsQuery->get();


        // Separate equipment and laboratory items
        $admin_equipment = $admin_requests->filter(function ($item) {
            return $item->resource_type == 1;
        });

        $admin_laboratory = $admin_requests->filter(function ($item) {
            return $item->resource_type == 3;
        });

        // Fetch images for reserved items
        $imagePaths = DB::table('resources')
            ->whereIn('resource_id', $admin_requests->pluck('resource_id'))
            ->pluck('image', 'resource_id')
            ->map(function ($imagePath) {
                return 'storage/' . $imagePath;
            });

        // Format date and image data for requests
        $this->formatDataWithImage($admin_requests, $imagePaths);

        // Format date and image data for facilities
        $this->formatDataWithImage($admin_facilities, $imagePaths);

        // Format date and image data for all requests
        $this->formatDataWithImage(
            $all_requests,
            $imagePaths
        );


        // Return view with data
        return view('login.masteradmin.master_archived_requests', compact(
            'userData',
            'cms',
            'sortBy',
            'admin_equipment',
            'admin_laboratory',
            'admin_facilities',
            'all_requests'
        ));
    }

    public function fetchNewRequests()
    {
        $cms = $this->cms();

        // Fetch new data from the database
        $all_requests = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->whereIn('user_reservation_requests.status', [1, 2, 3, 5, 7, 12, 13])
            ->where('resources.department_owner', '=', $cms['dept'])
            ->where(function ($query) {
                $query->where('resources.resource_type', '!=', 3)
                    ->orWhereNotNull('user_reservation_requests.noted_by'); // Applies only when resource_type is 3
            })
            ->select(
                'user_reservation_requests.*',
                'resources.resource_type',
                'resources.department_owner',
                'resources.resource_name',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.user_id',
                'user_accounts.position',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->orderByRaw("FIELD(user_reservation_requests.status, 5, 3, 2) desc")
            ->orderBy('user_reservation_requests.created_at', 'desc')
            ->get();

        // Format the data as necessary, including dates
        $formatted_requests = $all_requests->map(function ($request) {
            return [
                'transaction_id' => $request->transaction_id,
                'first_name' => ucwords(strtolower($request->first_name)),
                'last_name' => ucwords(strtolower($request->last_name)),
                'position' => $request->position,
                'resource_name' => $request->resource_name,
                'pickup_date' => \Carbon\Carbon::parse($request->pickup_datetime)->format('M j, Y'),
                'pickup_time' => \Carbon\Carbon::parse($request->pickup_datetime)->format('h:i A'),
                'return_date' => \Carbon\Carbon::parse($request->return_datetime)->format('M j, Y'),
                'return_time' => \Carbon\Carbon::parse($request->return_datetime)->format('h:i A'),
                'created_at_date' => \Carbon\Carbon::parse($request->created_at)->format('M j, Y'),
                'created_at_time' => \Carbon\Carbon::parse($request->created_at)->format('h:i A'),
                'status_color' => $request->status_color,
                'status_state' => $request->status_state,
                'details_url' => route('ma.overview', ['transaction_id' => $request->transaction_id]),
            ];
        });

        return response()->json(['all_requests' => $formatted_requests]);
    }

    public function department_resources(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        // Get the active tab and availability filter
        $activeTab = $request->input('tab', 'equipment'); // Default to 'all' if not provided

        // Initialize queries for different tab contents
        $resourcesQuery = DB::table('resources')
            ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('reservation_status', 'resources.status', '=', 'reservation_status.status_id')
            ->select(
                'resources.*',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'resource_type.resource_type as type'
            )
            ->where('resources.department_owner', $cms['dept'])
            ->whereNull('resources.deleted_at')
            ->orderBy('resources.created_at', 'desc');

        $facilitiesQuery = DB::table('facilities')
            ->join('reservation_status', 'facilities.status', '=', 'reservation_status.status_id')
            ->select(
                'facilities.facility_name',
                'facilities.facilities_id',
                'facilities.image',
                'facilities.is_available',
                'facilities.policy',
                'facilities.status as facility_status',
                'facilities.created_at as facility_created_at',
                'facilities.edited_at as facility_edited_at',
                'facilities.edited_by as facility_edited_by',
                'facilities.added_by as facility_added_by',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('facilities.department_owner', $cms['dept'])
            ->whereNull('facilities.deleted_at')
            ->orderBy('facilities.created_at', 'desc');

        $paginated_resources = $resourcesQuery->paginate(20);
        $paginated_facility = $facilitiesQuery->paginate(20);


        // Apply filters based on tab
        if ($activeTab !== 'equipment') {
            $resourcesQuery->where('resource_type.resource_type', $activeTab);
        }

        // Retrieve filtered data
        $resources = $resourcesQuery->get();
        $facilities = $facilitiesQuery->get();

        // For Replacement
        $itemsForReplacement = DB::table('resources')
            ->where('department_owner', $cms['dept'])
            ->where('status', 7)
            ->count();

        // Current Availability
        $currentEquipmentCount = DB::table('resources')
            ->where('department_owner', $cms['dept'])
            ->where('resource_type', 1)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->count();

        $currentLabsCount = DB::table('resources')
            ->where('department_owner', $cms['dept'])
            ->where('resource_type', 3)
            ->whereIn('status', [1])
            ->whereNull('deleted_at')
            ->count();

        $currentFacilitiesCount = DB::table('facilities')
            ->where('department_owner', $cms['dept'])
            ->whereIn('status', [1])
            ->whereNull('deleted_at')
            ->count();

        // Currently Borrowed
        $borrowedEquipmentCount = DB::table('resources')
            ->where('department_owner', $cms['dept'])
            ->where('resource_type', 1)
            ->whereIn('status', [3, 5, 13])
            ->count();

        $borrowedLabsCount = DB::table('resources')
            ->where('department_owner', $cms['dept'])
            ->where('resource_type', 3)
            ->whereIn('status', [3, 5, 13])
            ->count();

        $borrowedFacilitiesCount = DB::table('facilities')
            ->where('department_owner', $cms['dept'])
            ->whereIn('status', [3, 5, 13])
            ->count();

        //Available Resources
        $availableResourcesCount = DB::table('resources')
            ->where('department_owner', $cms['dept'])
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->count();

        //Available Facilities
        $availableFacilitiesCount = DB::table('facilities')
            ->where('department_owner', $cms['dept'])
            ->where('is_available', 1)
            ->whereNull('deleted_at')
            ->count();

        //SUM 
        $totalAvailableItems = $availableResourcesCount;

        $totalAvailable = $resources->where('status_state', 7)->count();
        $totalItemsReserved = $resources->where('status', 13)->count();

        $combinedRequests = collect()
            ->merge($resources)
            ->merge($facilities)
            ->unique('status_state');

        return view('login.masteradmin.master_manage_resources', compact(
            'userData',
            'cms',
            'resources',
            'facilities',
            'activeTab',
            'itemsForReplacement',
            'totalAvailableItems',
            'borrowedEquipmentCount',
            'borrowedLabsCount',
            'borrowedFacilitiesCount',
            'currentEquipmentCount',
            'currentLabsCount',
            'currentFacilitiesCount',
            'totalAvailable',
            'totalItemsReserved',
            'paginated_resources',
            'paginated_facility',
            'combinedRequests'
        ));
    }

    public function add_resource()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $resource_type = DB::table('resource_type')
            ->whereIn('category_id', [1, 3])
            ->select('category_id', 'resource_type')
            ->get();

        $cms = $this->cms();

        // Fetch all Policies for the Dropdown
        $policies = DB::table('policies')
            ->where('department_owner', $cms['dept'])
            ->get();

        return view('login.masteradmin.master_add', compact(
            'userData',
            'cms',
            'resource_type',
            'policies'
        ));
    }

    // AKA Edit Resource
    public function view_resource(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        if (!$request->has('resource_id')) {
            return redirect()->route('ma.manage.resources')->with('error', 'Invalid Access. Resource ID is Missing.');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $resource_id = $request->input('resource_id');
        $resource = resources::join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('reservation_status', 'resources.status', '=', 'reservation_status.status_id') // Join with reservation_status
            ->where('resources.resource_id', $resource_id)
            ->select(
                'resources.*',
                'resource_type.resource_type as type',
                'resource_type.category_id as type_id',
                'reservation_status.status_state',
                'reservation_status.status_id'
            )
            ->first();


        // For Dropdown 
        $resource_types = DB::table('resource_type')
            ->whereIn('category_id', [1, 3])
            ->select(
                'category_id',
                'resource_type'
            )->get();

        // For Resource Status
        $statusIds = [1, 7, 10, 12, 13];
        $status = Reservation_Status::whereIn('status_id', $statusIds)->get(['status_id', 'status_state']);

        // For the Current Policy of the Resource
        $policy = DB::table('resources')
            ->join('policies', 'resources.policy_id', '=', 'policies.policy_id')
            ->select('policies.policy_id', 'policies.policy_name', 'policies.policy_content', 'policies.inclusions')
            ->where('resources.resource_id', $resource_id)
            ->first();

        // Fetch all Policies for the Dropdown
        $policies = DB::table('policies')
            ->where('department_owner', $cms['dept'])
            ->get();


        // RATINGS
        $rating = $resource->rating;

        $totalReviews = $resource->feedbacks()->count();

        // Fetch the user reviews
        $reviews = DB::table('user_feedback')
            ->where('resource_id', $resource_id)
            ->get();

        // Transform reviews to add formatted date and profile picture
        $reviews->transform(function ($review) {
            // Format review date
            $review->formatted_date = (new DateTime($review->created_at))->format('F d, Y');

            // Fetch user profile pic
            $user = DB::table('user_accounts')
                ->where('user_id', $review->username)
                ->first();

            if ($user) {
                $review->profile_pic = $user->profile_pic;
            } else {
                $review->profile_pic = 'default_image.png';
            }

            return $review;
        });

        return view('login.masteradmin.master_view_resource', compact(
            'userData',
            'cms',
            'resource',
            'resource_types',
            'policy',
            'policies',
            'status',
            'reviews',
            'rating',
            'totalReviews'
        ));
    }

    public function add_facility()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        // For Resource Status
        $statusIds = [1, 7, 12, 13];
        $status = Reservation_Status::whereIn('status_id', $statusIds)->get(['status_id', 'status_state']);

        // Fetch all Policies for the Dropdown
        $policies = DB::table('policies')
            ->where('department_owner', $cms['dept'])
            ->get();

        return view('login.masteradmin.master_add_facility', compact(
            'cms',
            'userData',
            'status',
            'policies'
        ));
    }

    public function edit_facility(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        if (!$request->has('facilities_id')) {
            return redirect()->route('ma.manage.resources')->with('error', 'Invalid Access. Facility ID is Missing.');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $facility_id = $request->input('facilities_id');
        $facility = DB::table('facilities')
            ->where('facilities_id', $facility_id)
            ->select(
                'facilities.*'
            )
            ->first();

        // For Resource Status
        $statusIds = [1, 7, 12, 13];
        $status = Reservation_Status::whereIn('status_id', $statusIds)->get(['status_id', 'status_state']);

        // For the Current Policy of the Resource
        $policy = DB::table('facilities')
            ->join('policies', 'facilities.policy', '=', 'policies.policy_id')
            ->select('policies.policy_id', 'policies.policy_name', 'policies.policy_content', 'policies.inclusions')
            ->where('facilities.facilities_id', $facility_id)
            ->first();

        // Fetch all Policies for the Dropdown
        $policies = DB::table('policies')
            ->where('department_owner', $cms['dept'])
            ->get();

        return view('login.masteradmin.master_edit_facility', compact(
            'cms',
            'userData',
            'facility',
            'policy',
            'policies',
            'status'
        ));
    }

    public function request_overview(Request $request, $transactionId)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $query = user_reservation_requests::join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('resource_type', 'user_reservation_requests.resource_type', '=', 'resource_type.category_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->leftJoin('user_accounts as approver', 'user_reservation_requests.approved_by', '=', 'approver.user_id') // Join for Approved by
            ->leftJoin('user_accounts as returner', 'user_reservation_requests.returned_to', '=', 'returner.user_id') // Join for Returned to
            ->leftJoin('user_accounts as noted', 'user_reservation_requests.professor', '=', 'noted.user_id') // Join for Noted by
            ->leftJoin('policies', 'resources.policy_id', '=', 'policies.policy_id')
            ->where('user_reservation_requests.transaction_id', $transactionId)
            ->select(
                'user_reservation_requests.*',
                'resource_type.resource_type',
                'resources.department_owner as dept',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'policies.*',
                'resources.resource_name as name',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'approver.first_name as approved_by_first_name',  // Fetch Approved by first name
                'approver.last_name as approved_by_last_name',    // Fetch Approved by last name
                'returner.first_name as returned_to_first_name',  // Fetch Returned to first name
                'returner.last_name as returned_to_last_name',    // Fetch Returned to last name
                'noted.first_name as noted_by_first_name',        // Fetch Noted by first name
                'noted.last_name as noted_by_last_name'           // Fetch Noted by last name
            )
            ->first();


        if ($query) {
            $query->pickup_datetime = \Carbon\Carbon::parse($query->pickup_datetime)->format('F j, Y g:i A');
            $query->return_datetime = \Carbon\Carbon::parse($query->return_datetime)->format('F j, Y g:i A');
            $query->created_at = \Carbon\Carbon::parse($query->created_at)->format('F j, Y g:i A');
        } else {
            return redirect()->route('ma.requests')->with('error', 'No Matching Requests Found');
        }

        return view('login.masteradmin.master_request_overview', compact(
            'userData',
            'cms',
            'query'
        ));
    }

    public function request_facility_overview($id)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1) {
            return redirect()->route('login');
        }
        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $facilityId = $id;

        $reservation = Facility_Reservation::join('facilities', 'facility_reservation.facility_name', '=', 'facilities.facilities_id')
            ->join('reservation_status', 'facility_reservation.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'facility_reservation.user_id', '=', 'user_accounts.user_id')
            ->leftJoin('user_accounts as approver', 'facility_reservation.approved_by', '=', 'approver.user_id')
            ->leftJoin('policies', 'facilities.policy', '=', 'policies.policy_id')
            ->select(
                'facility_reservation.*',
                'facility_reservation.remarks',
                'facilities.department_owner as owner',
                'facilities.facility_name as name',
                'facilities.facilities_id',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.user_id',
                'approver.first_name as approved_by_first_name',
                'approver.last_name as approved_by_last_name',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'policies.*'
            )
            ->where('facility_reservation.id', $facilityId)
            ->first();

        if ($reservation) {
            // Date Formats
            $reservation->start_datetime = \Carbon\Carbon::parse($reservation->start_datetime)->format('F j, Y g:i A');
            $reservation->end_datetime = \Carbon\Carbon::parse($reservation->end_datetime)->format('F j, Y g:i A');
            $reservation->created_at = \Carbon\Carbon::parse($reservation->created_at)->format('F j, Y g:i A');
        } else {
            return redirect()->route('ma.requests')->with('error', 'No Matching Requests Found');
        }

        return view('login.masteradmin.master_facility_overview', compact(
            'userData',
            'cms',
            'reservation',
        ));
    }

    public function policy()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }
        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $policy = DB::table('policies')
            ->select('policy_name', 'policy_content', 'policy_id', 'inclusions')
            ->where('department_owner', $cms['dept_label'])
            ->get();

        return view('login.masteradmin.master_policy', compact(
            'userData',
            'cms',
            'policy'
        ));
    }

    public function reservation_calendar(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $position = $user->position;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'position' => $position,
            'profile_pic' => $profile_pic
        ];

        $cms = $this->cms();

        $reserved_items = DB::table('user_reservation_requests')
            ->join('resources', 'user_reservation_requests.resource_id', '=', 'resources.resource_id')
            ->join('resource_type', 'user_reservation_requests.resource_type', '=', 'resource_type.category_id')
            ->join('departments', 'resources.department_owner', '=', 'departments.department_name')
            ->join('reservation_status', 'user_reservation_requests.status', '=', 'reservation_status.status_id')
            ->join('user_accounts', 'user_reservation_requests.user_id', '=', 'user_accounts.user_id')
            ->where('departments.department_name', $cms['dept'])
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
                        'user_accounts.first_name',
                        'user_accounts.last_name',
                        DB::raw("'Facility' as type")
                    )
            )
            ->get();

        return view('login.masteradmin.master_calendar', compact(
            'userData',
            'cms',
            'reserved_items'
        ));
    }

    public function account()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;
        $email = $user->email;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic,
            'email' => $email
        ];

        $cms = $this->cms();

        return view('login.masteradmin.master_account', compact('userData', 'cms'));
    }

    public function admin_about()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $department = session('dept_name');
        $aboutContent = cms_about::where('department', $department)->first();

        return view('login.masteradmin.master_about', compact('userData', 'cms', 'aboutContent'));
    }


    // Post Functions
    public function reqDecision(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'transaction_id' => 'required|exists:user_reservation_requests,transaction_id',
            'resource_id' => 'required|exists:resources,resource_id',
            'action' => 'required|in:pending,accept,reject,claimed,cancelled,returned,replacement,late', // Include all possible actions
            'remarks' => 'nullable|string', // Allow remarks to be empty
        ]);

        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (
            !$user || $user->user_type == 1
        ) {
            return redirect()->route('login');
        }

        $transactionId = $request->input('transaction_id');
        $action = $request->input('action');
        $resource_id = $request->input('resource_id');
        $remarks = $request->input('remarks');

        // Resource Table Query
        $resource = resources::where('resource_id', $resource_id)->first();

        // Call Notification Controller
        $notificationController = new Notification_Controller();

        if (!$resource) {
            return redirect()->back()->with('error', 'Resource not found.');
        }

        // Check current status
        $currentStatus = $resource->status;

        // Update the Status
        $status = '';
        $updateData = ['status' => $status];

        switch ($action) {
            case 'pending':
                $status = '2';

                $updateData['approved_by'] = null;

                // Update Resource Status
                $resource->update(['status' => 1]);
                break;
            case 'accept':
                // Fetch the reservation details
                $reservation = DB::table('user_reservation_requests')->where('transaction_id', $transactionId)->first();

                if (!$reservation) {
                    return redirect()->back()->with('error', 'Reservation not found.');
                }

                // Extract pickup and return datetime from the reservation
                $pickupDatetime = $reservation->pickup_datetime ?? null;
                $returnDatetime = $reservation->return_datetime ?? null;

                if (!$pickupDatetime || !$returnDatetime) {
                    return redirect()->back()->with('error', 'Invalid pickup or return datetime.');
                }

                // Check for conflicting reservations of the same resource with status 3 or 5
                $conflictingReservations = DB::table('user_reservation_requests')
                    ->where('resource_id', $reservation->resource_id)
                    ->whereIn('status', [3, 5]) // Approved or Ongoing
                    ->where(function ($query) use ($pickupDatetime, $returnDatetime) {
                        // Check if the reservation conflicts with any other reservation on the same resource
                        $query->whereBetween('pickup_datetime', [$pickupDatetime, $returnDatetime])
                            ->orWhereBetween('return_datetime', [$pickupDatetime, $returnDatetime])
                            ->orWhere(function ($innerQuery) use ($pickupDatetime, $returnDatetime) {
                                // Check if the reservation fully encompasses another one
                                $innerQuery->where('pickup_datetime', '<=', $pickupDatetime)
                                    ->where('return_datetime', '>=', $returnDatetime);
                            });
                    })
                    ->exists();

                if ($conflictingReservations) {
                    return redirect()->back()->with('error', 'This reservation conflicts with another approved or ongoing reservation.');
                }

                $status = '3';

                // For Audit
                $updateData['approved_by'] = $user->user_id;

                // For Analytics
                approve_reject_analytics::create([
                    'request_id' => $transactionId,
                    'status' => $status,
                    'changed_at' => now(),
                ]);

                // Notify User
                $notificationController->updateReservationStatus($transactionId, 3);

                break;
            case 'reject':
                $status = '4';

                // Update the status and remarks
                user_reservation_requests::where('transaction_id', $transactionId)->update([
                    'remarks' => $remarks,
                ]);

                // For Analytics
                approve_reject_analytics::create([
                    'request_id' => $transactionId,
                    'status' => $status,
                    'changed_at' => now(),
                ]);
                break;
            case 'claimed':

                $nonUpdatableStatuses = [13, 12, 7];

                // Prevent approval if the resource's current status is in the non-updatable statuses
                if (in_array($currentStatus, $nonUpdatableStatuses)) {
                    $statusLabels = DB::table('reservation_status')
                        ->whereIn('status_id', $nonUpdatableStatuses)
                        ->pluck('status_state', 'status_id')
                        ->toArray();

                    $currentStatusLabel = $statusLabels[$currentStatus] ?? 'Unknown Status';
                    return redirect()->back()->with('error', 'Resource cannot be approved because it is already ' . $currentStatusLabel . '.');
                }

                $status = '5';

                // For Audit
                $updateData['released_by'] = $user->user_id;

                $updateData['returned_to'] = null;

                // Update Resource Status
                $resource->update(['status' => 13]);

                // Notify The User
                $notificationController->updateReservationStatus($transactionId, 5);
                break;
            case 'cancelled':
                $status = '8';

                // Update the status and save remarks
                user_reservation_requests::where('transaction_id', $transactionId)->update([
                    'remarks' => $remarks,
                ]);

                break;

            case 'returned':
                $status = '6';

                // For Audit
                $updateData['returned_to'] = $user->user_id;

                // Add the remarks
                $updateData['remarks'] = $remarks;

                // Update Resource Status (Available)
                $resource->update(['status' => 1]);

                // Notify The User
                $notificationController->updateReservationStatus($transactionId, 6);

                // Remarks if Any
                $decision = 'Returned Resource Transaction No. ' . $transactionId . ' (' . $resource->resource_name . ')';
                if (!empty($remarks)) {
                    $decision .= ' - Remarks: ' . $remarks;
                }

                // Add an audit entry for the remarks
                audit::create([
                    'action' => $decision,
                    'made_by' => $user->first_name . ' ' . $user->last_name,
                    'user_type' => $userType,
                    'user_id' => $user->user_id,
                    'action_type' => empty($remarks) ? ' No Remark Added' : 'Remark Added',
                    'department' => $user->dept_name,
                ]);
                break;

            case 'replacement':
                $status = '7';

                // Update Resource Status
                $resource->update(['status' => 7]);
                break;
            case 'late':
                $status = '9';

                // For Audit
                $updateData['returned_to'] = $user->user_id;

                // Update Resource Status
                $resource->update(['status' => 1]);

                // Feedback and Notify
                $reservation = DB::table('user_reservation_requests')->where('transaction_id', $transactionId)->first();
                if ($reservation) {
                    $notificationController->sendFeedbackEmailforLate($reservation, 'equipment'); // Late email notification
                }
                break;
            default:
                return redirect()->back()->with('error', 'Invalid Action.');
        }

        $updateData['status'] = $status;

        // Update the Status
        user_reservation_requests::where('transaction_id', $transactionId)->update($updateData);

        // Capitalize First Letter
        $action = ucfirst($action);

        // Register the action in the audit table
        audit::create([
            'action' => 'Resource Transaction No. ' . $transactionId . ' Status Update' . '(' . $resource->resource_name . ')',
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_type' => $userType,
            'user_id' => $user->user_id,
            'action_type' => $action,
            'department' => $user->dept_name,
        ]);

        $statusMessages = [
            '2' => 'Pending',
            '3' => 'Approved',
            '4' => 'Rejected',
            '5' => 'On-Going',
            '6' => 'Returned',
            '7' => 'For Replacement',
            '9' => 'Late',
            '8' => 'Cancelled'
        ];


        if (isset($statusMessages[$status])) {
            return redirect()->back()->with('success', 'Request ' . $transactionId . ' is ' . $statusMessages[$status]);
        } else {
            // Handle unrecognized status
            return redirect()->back()->with('error', 'Request ' . $transactionId . ' has an unknown status: ' . $status);
        }
    }

    public function facility_approval(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (
            !$user || $user->user_type == 1
        ) {
            return redirect()->route('login');
        }

        $transactionId = $request->input('transaction_id');
        $action = $request->input('action');
        $facility_id = $request->input('facility_id');

        // Resource Table Query
        $resource = Facility::where('facilities_id', $facility_id)->first();

        // Call Notification Controller
        $notificationController = new Notification_Controller();

        // Check current status
        $currentStatus = $resource->status;

        // Define non-updatable status IDs for approval
        $nonUpdatableStatuses = [12, 7];

        // Prevent approval if the resource's current status is in the non-updatable statuses
        if (in_array($currentStatus, $nonUpdatableStatuses) && $action === 'accept') {
            $statusLabels = DB::table('reservation_status')
                ->whereIn('status_id', $nonUpdatableStatuses)
                ->pluck('status_state', 'status_id')
                ->toArray();

            $currentStatusLabel = $statusLabels[$currentStatus] ?? 'Unknown Status';
            return redirect()->back()->with('error', 'Resource cannot be approved because it is ' . $currentStatusLabel . '.');
        }

        // Handle the case where the resource is not found
        if (!$resource) {
            return redirect()->back()->with('error', 'Resource not found.');
        }

        // Determine status based on action
        $status = '';
        switch ($action) {
            case 'accept':

                // Fetch the reservation details
                $reservation = DB::table('facility_reservation')->where('id', $transactionId)->first();

                if (!$reservation) {
                    return redirect()->back()->with('error', 'Reservation not found.');
                }

                // Extract pickup and return datetime from the reservation
                $start_datetime = $reservation->start_datetime ?? null;
                $end_datetime = $reservation->end_datetime ?? null;

                if (!$start_datetime || !$end_datetime) {
                    return redirect()->back()->with('error', 'Invalid Start or End datetime.');
                }

                // Check for conflicting reservations of the same resource with status 3 or 5
                $conflictingReservations = DB::table('facility_reservation')
                    ->where('facility_name', $reservation->id)
                    ->whereIn('status', [3, 5]) // Approved or Ongoing
                    ->where(function ($query) use ($start_datetime, $end_datetime) {
                        // Check if the reservation conflicts with any other reservation on the same resource
                        $query->whereBetween('start_datetime', [$start_datetime, $end_datetime])
                            ->orWhereBetween('end_datetime', [$start_datetime, $end_datetime])
                            ->orWhere(function ($innerQuery) use ($start_datetime, $end_datetime) {
                                // Check if the reservation fully encompasses another one
                                $innerQuery->where('start_datetime', '<=', $start_datetime)
                                    ->where('end_datetime', '>=', $end_datetime);
                            });
                    })
                    ->exists();

                if ($conflictingReservations) {
                    return redirect()->back()->with('error', 'This reservation conflicts with another approved or ongoing reservation.');
                }

                $updateData['status'] = '3';

                // For Audit
                $updateData['approved_by'] = $user->user_id;

                // Notify Users
                $notificationController->updateReservationStatus($transactionId, 3);

                // For Analytics
                approve_reject_analytics::create([
                    'request_id' => $transactionId,
                    'status' => $status,
                    'changed_at' => now(),
                ]);
                break;

            case 'reject':
                $status = '4'; // Rejected

                // Notify Users
                $notificationController->updateReservationStatus($transactionId, 4);

                // For Analytics
                approve_reject_analytics::create([
                    'request_id' => $transactionId,
                    'status' => $status,
                    'changed_at' => now(),
                ]);
                break;

            case 'on-going':

                $nonUpdatableStatuses = [13, 12, 7];

                // Prevent approval if the resource's current status is in the non-updatable statuses
                if (in_array($currentStatus, $nonUpdatableStatuses)) {
                    $statusLabels = DB::table('reservation_status')
                        ->whereIn('status_id', $nonUpdatableStatuses)
                        ->pluck('status_state', 'status_id')
                        ->toArray();

                    $currentStatusLabel = $statusLabels[$currentStatus] ?? 'Unknown Status';
                    return redirect()->back()->with('error', 'Facility Request cannot be approved because it is already ' . $currentStatusLabel . '.');
                }

                $updateData['status'] = 5;

                // Update Resource Status
                $resource->update(['status' => 13]);

                // Notify Users
                $notificationController->updateReservationStatus($transactionId, 5);
                break;

            case 'cancelled':
                $updateData['status'] = 8;

                // Notify Users
                $notificationController->updateReservationStatus($transactionId, 8);
                break;

            case 'pending':
                $updateData['status'] = '2'; // Pending

                $updateData['approved_by'] = null;

                $resource->is_available = 1;

                break;

            case 'completed':
                $updateData['status'] = 14;

                // Update Resource Status to Available (status 1)
                $resource->update(['status' => 1]);
                $resource->is_available = 1;

                // Send feedback email
                $reservation = DB::table('facility_reservation')->where('id', $transactionId)->first();
                if ($reservation) {
                    $notificationController->sendFacilityFeedbackEmail($reservation);
                }

                // Add remarks to the audit log
                $remarks = $request->input('remarks');

                DB::table('facility_reservation')->where('id', $transactionId)->update([
                    'remarks' => $remarks,
                ]);

                // Check if the update is successful
                $reservationUpdated = DB::table('facility_reservation')->where('id', $transactionId)->first();

                if (!empty($remarks)) {
                    audit::create([
                        'action' => 'Added remarks for Facility Transaction No. ' . $transactionId,
                        'made_by' => $user->first_name . ' ' . $user->last_name,
                        'user_type' => $userType,
                        'user_id' => $user->user_id,
                        'action_type' => 'Remark Added',
                        'department' => $user->dept_name,
                        'remarks' => $remarks,  // Store the remarks in the audit log if your schema allows it
                    ]);
                }
                break;

            case 'late':
                $status = '9';

                $resource->is_available = 1;

                // Update Resource Status
                $resource->update(['status' => 1]);

                // Feedback and Notify
                $reservation = DB::table('facility_reservation')->where('id', $transactionId)->first();
                if ($reservation) {
                    $notificationController->sendFacilityFeedbackEmail($reservation); // Send feedback email notification for Completed statuses
                }
                break;

            default:
                return redirect()->back()->with('error', 'Invalid Action.');
        }

        // Update Resource Availability
        switch ($status) {
            case '14':
                $resource->is_available = 1; // Available
                break;
            case '8': // Cancelled
                $resource->is_available = 1; // Available
                break;

            case '5':
                $resource->is_available = 0;
                break;
        }

        // Update the Reservation Status
        Facility_Reservation::where('id', $transactionId)->update($updateData);

        // Save the resource availability
        $resource->save();

        // Capitalize First Letter
        $action = ucfirst($action);

        // Register the action in the audit table
        audit::create([
            'action' => 'Facility Transaction No. ' . $transactionId . ' Status Update' . '(' . $resource->facility_name . ')',
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_type' => $userType,
            'user_id' => $user->user_id,
            'action_type' => $action,
            'datetime' => now()->format('Y-m-d H:i:s'),
            'department' => $user->dept_name,
        ]);

        // Prepare status messages
        $statusMessages = [
            '2' => 'Pending',
            '3' => 'Approved',
            '4' => 'Rejected',
            '5' => 'On-Going',
            '9' => 'Late',
            '8' => 'Cancelled',
            '14' => 'Completed'
        ];

        // Redirect with status message
        if (isset($statusMessages[$updateData['status']])) {
            return redirect()->back()->with('success', 'Request ' . $resource->facility_name . ' is ' . $statusMessages[$updateData['status']]);
        } else {
            return redirect()->back()->with('error', 'Request ' . $transactionId . ' has an unknown status: ' . $status);
        }
    }

    // Resources
    public function store_resource(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();
        $userId = $user->user_id;
        $last_name = $user->last_name;

        // Default Values
        $resource_id = rand(100, 99999);
        $rating = 0;

        // Retrieve the Department and Status (Default is Available)
        $reservationStatus = Reservation_Status::findOrFail(1);
        $userDept = $user->dept_name;
        $status = $reservationStatus->status_id;

        // Perform validation for all fields except the image
        $validate = $request->validate([
            'resource_name' => 'required|regex:/^[A-Za-z0-9_ \-]+$/',
            'resource_type' => 'required',
            'serial_number' => 'required|regex:/^[A-Za-z0-9_ \-]+$/',
            'policy_name' => 'required|exists:policies,policy_id',
            'inclusions' => 'max:2048'
        ]);

        // Validate image separately
        $imageValidation = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'image' => 'required|image|max:2048',
        ]);

        if ($imageValidation->fails()) {
            return redirect()->route('ma.add.resources')
                ->withInput()
                ->with('error', $imageValidation->errors()->first('image'));
        }

        // Proceed with image upload
        $image = $request->file('image');
        $sanitizedResourceName = $this->sanitizeFilename($validate['resource_name']);
        $sanitizedLastName = $this->sanitizeFilename($last_name);
        $sanitizedDept = $this->sanitizeFilename($cms['dept_label']);

        $timestamp = now()->timestamp;
        $fileName = $sanitizedResourceName . '_' . $sanitizedLastName . '_' . $timestamp . '.png';
        $path = $image->storeAs('public/' . $sanitizedDept . '/resources_image', $fileName);
        $path = str_replace('public/', '', $path);

        try {
            resources::create([
                'resource_id' => $resource_id,
                'resource_name' => $validate['resource_name'],
                'resource_type' => $validate['resource_type'],
                'serial_number' => $validate['serial_number'],
                'rating' => $rating,
                'status' => $status,
                'policy_id' => $validate['policy_name'],
                'department_owner' => $userDept,
                'image' => $path,
                'added_by' => $userId,
                'created_at' => now()
            ]);

            // Register the action in the audit table
            audit::create([
                'action' => 'Added Resource ' . $validate['resource_name'] . ' (' . $resource_id . ')',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Resource Addition',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.manage.resources')->with('added', 'Resource Added!');
        } catch (\Exception $e) {
            return redirect()->route('ma.add.resources')->withInput()->with('error', $e->getMessage() . ' Max Size of 2MB');
        }
    }

    public function edit_resource(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'resource_id' => 'required|integer',
            'resource_name' => 'required|max:45|regex:/^[A-Za-z0-9_ ]+$/',
            'resource_type' => 'required',
            'serial_number' => [
                'required',
                'regex:/^[A-Za-z0-9_ \-]+$/',
                Rule::unique('resources', 'serial_number')->ignore($request->input('resource_id'), 'resource_id'),
            ],
            'resource_status' => 'required',
            'policy_name' => 'required|exists:policies,policy_id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:5048',
            'inclusions' => 'max:2048'
        ]);

        try {
            $user = Auth::user();
            $userType = $user->userType->user_type;

            if (!$user || $user->user_type == 1 || $user->user_type == 2) {
                return redirect()->route('login');
            }

            $cms = $this->cms();

            $userId = $user->user_id;
            $last_name = $user->last_name;
            $edited_at = now();
            $resource = resources::findOrFail($request['resource_id']);

            $restrict_change = user_reservation_requests::where('resource_id', $resource->resource_id)
                ->whereIn('status', [3, 5])
                ->exists();

            if ($restrict_change) {
                // Redirect back with input and an error message
                return back()->withInput()->with('error', 'Resource status cannot be changed due to existing reservation requests for this resource.');
            }

            // Validate the request data
            $validate = $validator->validate();

            // Update resource fields if present
            if (isset($validate['resource_name'])) {
                $resource->resource_name = $validate['resource_name'];
            }
            if (isset($validate['serial_number'])) {
                $resource->serial_number = $validate['serial_number'];
            }
            if (isset($validate['resource_type'])) {
                $resource->resource_type = $validate['resource_type'];
            }
            if (isset($validate['resource_status'])) {
                $resource->status = $validate['resource_status'];
            }

            // Policy Section
            $currentPolicyId = DB::table('resources')
                ->where('resource_id', $validate['resource_id'])
                ->value('policy_id');

            // Check if the selected policy_id is different from the current one
            if ($currentPolicyId != $validate['policy_name']) {
                // Update the policy_id in the resources table
                DB::table('resources')
                    ->where('resource_id', $validate['resource_id'])
                    ->update(['policy_id' => $validate['policy_name']]);
            }


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $timestamp = now()->timestamp;

                // Sanitize inputs
                $sanitizedResourceName = $this->sanitizeFilename($validate['resource_name']);
                $sanitizedLastName = $this->sanitizeFilename($last_name);
                $sanitizedDeptLabel = $this->sanitizeFilename($cms['dept_label']);

                // Get the file extension
                $extension = $image->getClientOriginalExtension();

                // Construct the new file name
                $fileName = $sanitizedResourceName . '_' . $sanitizedLastName . '_' . $timestamp . '.' . $extension;

                // Get the current image path from the resource
                $currentImagePath = $resource->image;

                // Delete the existing image if it exists
                if ($currentImagePath && Storage::exists('public/' . $currentImagePath)) {
                    Storage::delete('public/' . $currentImagePath);
                }


                // Store the new image
                $path = $image->storeAs('public/' . $sanitizedDeptLabel . '/resources_image', $fileName);

                // Adjust the stored path
                $path = str_replace('public/', '', $path);

                // Update the resource's image path and save
                $resource->image = $path;
                $resource->save();
            }

            // Save the resource and policy changes
            $resource->edited_at = $edited_at;
            $resource->edited_by = $userId;
            $resource->save();

            // Register the action in the audit table
            audit::create([
                'action' => 'Updated Resource ' . $validate['resource_name'] . ' (' . $resource->resource_id . ')',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Resource Update',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.manage.resources')->with('success', 'Resource Updated');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return redirect()->back()->withInput()->with('error', 'The Uploaded File is too Large.');
        }
    }

    public function delete_resource(Request $request)
    {
        try {
            $user = Auth::user();
            $userType = $user->userType->user_type;

            if (!$user || $user->user_type == 1 || $user->user_type == 2) {
                return redirect()->route('login');
            }

            $request->validate([
                'resource_id' => 'required|exists:resources,resource_id',
            ]);

            $resourceId = $request->input('resource_id');

            $resource = resources::findOrFail($resourceId);
            $imagePath = $resource->image;

            if ($resource->status == 13) {
                return
                    redirect()->back()->withInput()->with('error', 'Resource is Currently Reserved.');
            }

            // Soft delete the associated reservation requests
            user_reservation_requests::where('resource_id', $resourceId)
                ->whereIn('status', [2, 3, 5, 13])
                ->update(['deleted_at' => now(), 'status' => 15]);

            // Soft delete the resource record
            $resource->delete();

            // Check and delete the existing image if it exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Register the action in the audit table
            Audit::create([
                'action' => 'Deleted Resource ' . $resource->resource_name . ' (' . $resource->resource_id . ')',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Delete Resource',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.manage.resources')->with('success', 'Resource ' . $resourceId . ' Deleted Successfully.');
        } catch (\Exception $e) {
            return redirect()->route('ma.manage.resources')->with('error', $e->getMessage());
        }
    }

    public function download_resources()
    {
        $cms = $this->cms();

        // Fetch data from the resources query
        $resourcesQuery = DB::table('resources')
            ->join('resource_type', 'resources.resource_type', '=', 'resource_type.category_id')
            ->join('reservation_status', 'resources.status', '=', 'reservation_status.status_id')
            ->select(
                'resources.*',
                'reservation_status.status_state',
                'reservation_status.status_color',
                'resource_type.resource_type as type'
            )
            ->where('resources.department_owner', $cms['dept'])
            ->orderBy('resources.created_at', 'desc')
            ->get();

        // Fetch data from the facilities query
        $facilitiesQuery = DB::table('facilities')
            ->join('reservation_status', 'facilities.status', '=', 'reservation_status.status_id')
            ->select(
                'facilities.facility_name',
                'facilities.facilities_id',
                'facilities.image',
                'facilities.department_owner',
                'facilities.location',
                'facilities.is_available',
                'facilities.policy',
                'facilities.status as facility_status',
                'facilities.created_at as facility_created_at',
                'facilities.edited_at as facility_edited_at',
                'facilities.edited_by as facility_edited_by',
                'facilities.added_by as facility_added_by',
                'reservation_status.status_state',
                'reservation_status.status_color'
            )
            ->where('facilities.department_owner', $cms['dept'])
            ->orderBy('facilities.created_at', 'desc')
            ->get();

        // Create the CSV
        $csvData = '';
        // Define the header for the CSV
        $csvHeader = [
            'Type',
            'ID',
            'Serial Number/Location',
            'Resource Name',
            'Resource Type',
            'Status State',
            'Rating',
            'Department Owner',
            'Policy ID',
            'Created At',
        ];
        $csvData .= implode(',', $csvHeader) . "\n";

        // Add resources data
        foreach ($resourcesQuery as $resource) {
            $csvData .= implode(',', [
                'Resource',
                $resource->resource_id,
                $resource->serial_number,
                $resource->resource_name,
                $resource->type,
                $resource->status_state,
                $resource->rating,
                $resource->department_owner,
                $resource->policy_id,
                $resource->created_at,
            ]) . "\n";
        }

        // Add facilities data
        foreach ($facilitiesQuery as $facility) {
            $csvData .= implode(',', [
                'Facility', // Indicating type
                $facility->facilities_id,
                $facility->location,
                $facility->facility_name,
                'Facility', // Empty for resource type
                $facility->status_state,
                '', // Empty for rating
                $facility->department_owner,
                $facility->policy, // Empty for policy ID
                $facility->facility_created_at,
            ]) . "\n";
        }

        // Return CSV as a download
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="resources_and_facilities.csv"');

        return redirect()->route('ma.manage.resources')->with('success', $cms['dept'] . ' Resources Downloaded!');
    }


    // Facilities
    public function add_facilityPost(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        $userId = $user->user_id;
        $last_name = $user->last_name;
        $userDept = $user->dept_name;

        // Perform validation for all fields except the image
        $validate = $request->validate([
            'facility_name' => 'required|regex:/^[A-Za-z0-9_ ]+$/',
            'location' => 'required|regex:/^[A-Za-z0-9_, ]+$/',
            'resource_status' => 'required',
            'policy_name' => 'required|exists:policies,policy_id',
            'inclusions' => 'max:2048'
        ]);

        // Check for image file validation separately
        $image = $request->file('image');
        if ($image) {
            // Image validation
            $imageValidator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'image' => 'required|image|max:2048',
            ]);

            if ($imageValidator->fails()) {
                // Redirect back with image validation error
                return redirect()->route('ma.add.facility')
                    ->withInput()
                    ->with('error', $imageValidator->errors()->first('image'));
            }
        } else {
            // No image file provided
            return redirect()->route('ma.add.facility')
                ->withInput()
                ->with('error', 'No image file was uploaded.');
        }

        try {
            // Image Upload
            $sanitizedResourceName = $this->sanitizeFilename($validate['facility_name']);
            $sanitizedLastName = $this->sanitizeFilename($last_name);
            $sanitizedDept = $this->sanitizeFilename($cms['dept_label']);

            $timestamp = now()->timestamp;
            $fileName = $sanitizedResourceName . '_' . $sanitizedLastName . '_' . $timestamp . '.png';

            $path = $image->storeAs('public/' . $sanitizedDept . '/facilities_image', $fileName);
            $path = str_replace('public/', '', $path);

            Facility::create([
                'facility_name' => $validate['facility_name'],
                'status' => $validate['resource_status'],
                'location' => $validate['location'],
                'policy' => $validate['policy_name'],
                'department_owner' => $userDept,
                'image' => $path,
                'added_by' => $userId,
                'created_at' => now()
            ]);

            // Register the action in the audit table
            audit::create([
                'action' => 'Add Facility ' . $validate['facility_name'],
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Facility Addition',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.manage.resources')->with('added', 'Facility Added!');
        } catch (\Exception $e) {
            return redirect()->route('ma.add.facility')->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit_facilityPost(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        try {
            $userId = $user->user_id;
            $last_name = $user->last_name;
            $edited_at = now();
            $facility = Facility::findOrFail($request['facilities_id']);

            // Validation rules with 'policy_name' made nullable
            $validator = Validator::make($request->all(), [
                'facility_name' => 'required|max:45|regex:/^[A-Za-z0-9_ ]+$/',
                'location' => 'required',
                'resource_status' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:5048',
                'inclusions' => 'max:2048'
            ]);

            $restrict_change = Facility_Reservation::where('id', $facility->facility_name)
                ->whereIn('status', [3, 5])
                ->exists();

            if ($restrict_change) {
                // Redirect back with input and an error message
                return back()->withInput()->with('error', 'Facility status cannot be changed due to existing reservation requests for this resource.');
            }

            // Validate the request data
            $validate = $validator->validate();

            // Update facility fields if they are present in the request
            if (isset($validate['facility_name'])) {
                $facility->facility_name = $validate['facility_name'];
            }

            if (isset($validate['location'])) {
                $facility->location = $validate['location'];
            }

            if (isset($validate['resource_status'])) {
                $facility->status = $validate['resource_status'];
                if ($validate['resource_status'] == 12) {
                    $facility->is_available = 0;
                } elseif ($validate['resource_status'] == 1) {
                    $facility->is_available = 1;
                } elseif ($validate['resource_status'] == 13) {
                    $facility->is_available = 0;
                } elseif ($validate['resource_status'] == 7) {
                    $facility->is_available = 0;
                }
            }

            // Policy Section
            $currentPolicyId = $facility->policy;

            // Check if the selected policy_id is different or if it's null
            if ($currentPolicyId != $request->policy_name) {
                $facility->policy = $request->policy_name ?: 'N/A';
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $timestamp = now()->timestamp;

                // Sanitize inputs
                $sanitizedResourceName = $this->sanitizeFilename($validate['facility_name']);
                $sanitizedLastName = $this->sanitizeFilename($last_name);
                $sanitizedDeptLabel = $this->sanitizeFilename($cms['dept_label']);

                // Get the file extension
                $extension = $image->getClientOriginalExtension();

                // Construct the new file name
                $fileName = $sanitizedResourceName . '_' . $sanitizedLastName . '_' . $timestamp . '.' . $extension;

                // Get the current image path from the facility
                $currentImagePath = $facility->image;

                // Delete the existing image if it exists
                if ($currentImagePath && Storage::exists('public/' . $currentImagePath)) {
                    Storage::delete('public/' . $currentImagePath);
                }

                // Store the new image
                $path = $image->storeAs('public/' . $sanitizedDeptLabel . '/facilities_image', $fileName);

                // Adjust the stored path
                $path = str_replace('public/', '', $path);

                // Update the facility's image path and save
                $facility->image = $path;
            }

            // Save the facility changes
            $facility->edited_at = $edited_at;
            $facility->edited_by = $userId;
            $facility->save();

            // Register the action in the audit table
            audit::create([
                'action' => 'Update Facility ' . $validate['facility_name'] . '(' . $request['facilities_id'] . ')',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Facility Update',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.manage.resources')->with('success', 'Facility Updated');
        } catch (\Exception $e) {
            return redirect()->route('ma.manage.resources')->with('error', $e->getMessage());
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return redirect()->back()->withInput()->with('error', 'The Uploaded File is too Large.');
        }
    }

    public function delete_facility(Request $request)
    {
        try {
            $user = Auth::user();
            $userType = $user->userType->user_type;

            if (!$user || $user->user_type == 1 || $user->user_type == 2) {
                return redirect()->route('login');
            }

            // Validate request
            $request->validate([
                'facilities_id' => 'required|exists:facilities,facilities_id',
            ]);

            $resourceId = $request->input('facilities_id');

            // Fetch the facility record to get the image path
            $facility = Facility::findOrFail($resourceId);
            $imagePath = $facility->image;

            if ($facility->status == 13) {
                return
                    redirect()->back()->withInput()->with('error', 'Facility is Currently Reserved.');
            }

            // Soft delete associated reservations
            Facility_Reservation::where('facility_name', $resourceId)
                ->whereIn('status', [2, 3, 5, 13])
                ->update(['status' => 15, 'deleted_at' => now()]);

            // Soft delete the facility record
            $facility->delete();

            // Check and delete the existing image if it exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Register the action in the audit table
            audit::create([
                'action' => 'Deleted Facility ' . $facility->facility_name . ' (' . $resourceId . ')',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Delete Facility',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.manage.resources')->with('success', 'Facility ' . $resourceId . ' Deleted Successfully.');
        } catch (\Exception $e) {
            return redirect()->route('ma.manage.resources')->with('error', $e->getMessage());
        }
    }

    // General Policies (Dashboard Display)
    public function add_gen_policy(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;

        $request->validate([
            'policy_name' => 'required|string|max:75',
            'policy_content' => 'required',
        ]);

        $create = general_policies::create([
            'dept_owner' => Session::get('dept_name'),
            'policy_name' => $request->policy_name,
            'policy_content' => $request->policy_content,
            'added_by' => $userId
        ]);

        // Register the action in the audit table
        audit::create([
            'action' => 'Added General Policy ' . $request['policy_name'],
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_id' => $user->user_id,
            'user_type' => $userType,
            'action_type' => 'Added General Policy',
            'department' => $user->dept_name,
        ]);

        if ($create) {
            return redirect()->route('ma.dashboard')->with('success', $request->policy_name . ' Added Successfully.');
        } else {
            return redirect()->route('ma.dashboard')->with('error', 'An error occurred while adding the policy. Please try again.');
        }
    }

    public function edit_gen_policy(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;

        $request->validate([
            'policyId' => 'required|integer|exists:general_policies,id',
            'title' => 'required|string|max:75',
            'description' => 'required|string',
        ]);

        try {
            $updated = DB::table('general_policies')
                ->where('id', $request->policyId)
                ->update([
                    'policy_name' => $request->title,
                    'policy_content' => $request->description,
                    'edited_by' => $userId,
                ]);

            // Register the action in the audit table
            audit::create([
                'action' => 'Updated General Policy ' . $request['title'],
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Update General Policy',
                'department' => $user->dept_name,
            ]);

            if ($updated) {
                return redirect()->route('ma.dashboard')->with('success', $request->title . ' Updated Successfully.');
            } else {
                return redirect()->route('ma.dashboard')->with('error', 'Error Updating ' . $request->title . '.');
            }
        } catch (QueryException $e) {
            return redirect()->route('ma.dashboard')->with('error', 'An error occurred while updating the policy. Please try again.');
        }
    }

    public function delete_gen_policy(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;


        $request->validate([
            'policyId' => 'required|integer|exists:general_policies,id',
        ]);

        $policy = general_policies::find($request->policyId);

        if ($policy) {
            $policy->delete();

            // Register the action in the audit table
            audit::create([
                'action' => 'Deleted General Policy ' . $policy->policy_name,
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Deleted General Policy',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.dashboard')->with('success', 'Policy Deleted Successfully.');
        } else {
            return redirect()->route('ma.dashboard')->with('error', 'Error Deleting Policy.');
        }
    }

    // Policies
    public function add_policy(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }
        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        try {

            $policy_id = rand(100, 99999);
            $userDept = $user->dept_name;

            $inclusions = $request->input('inclusions');

            if (empty($inclusions)) {
                $inclusions = 'N/A';
            }

            $validate = $request->validate([
                'policy_name' => 'required|max:45',
                'policy_content' => 'required',
            ]);

            Policy::create([
                'policy_id' => $policy_id,
                'policy_name' => $validate['policy_name'],
                'policy_content' => $validate['policy_content'],
                'inclusions' => $inclusions,
                'department_owner' => $userDept
            ]);

            // Register the action in the audit table
            audit::create([
                'action' => 'Added Policy ' . $validate['policy_name'],
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Add Policy',
                'department' => $user->dept_name,
            ]);

            return redirect()->route('ma.policy')->with('success', 'Policy Added!');
        } catch (\Exception $e) {
            return redirect()->route('ma.policy')->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit_policy(Request $request)
    {
        try {
            $user = Auth::user();
            $userType = $user->userType->user_type;

            if (!$user || $userType == 1) {
                return redirect()->route('login');
            }

            $cms = $this->cms();

            $policy = DB::table('policies')->where('policy_id', $request['policy_id'])->first();

            if (!$policy) {
                return redirect()->route('admin.policy')->with('error', 'Policy not found.');
            }

            $inclusions = $request->input('policyInclusions');

            if (empty($inclusions)) {
                $inclusions = 'N/A';
            }

            // Validate
            $request->validate([
                'policyTitle' => 'required|string|max:75',
                'policyContent' => 'required|string',
            ]);

            // Update
            $updated = DB::table('policies')
                ->where('policy_id', $request->input('policy_id'))
                ->update([
                    'policy_name' => $request->input('policyTitle'),
                    'policy_content' => $request->input('policyContent'),
                    'inclusions' => $inclusions,
                ]);

            // Audit
            if ($updated) {
                // Register the action in the audit table
                audit::create([
                    'action' => 'Updated Policy ' . $request['policyTitle'],
                    'made_by' => $user->first_name . ' ' . $user->last_name,
                    'user_id' => $user->user_id,
                    'user_type' => $userType,
                    'action_type' => 'Update Policy',
                    'department' => $user->dept_name,
                ]);

                return redirect()->route('ma.policy')->with('success', 'Policy updated successfully.');
            } else {
                return redirect()->route('ma.policy')->with('info', 'No changes were made.');
            }
        } catch (\Exception $e) {
            return redirect()->route('ma.policy')->with('error', $e->getMessage());
        }
    }

    public function delete_policy(Request $request, $id)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $policy = DB::table('policies')->where('policy_id', $id)->first();
        $policyId = $policy->policy_id; // Extract the ID from the object
        $policyName = DB::table('policies')->where('policy_id', $policyId)->value('policy_name');

        if (!$policy) {
            return redirect()->back()->with('error', 'Policy not found.');
        }

        DB::table('policies')->where('policy_id', $id)->delete();

        // Register the action in the audit table
        audit::create([
            'action' => 'Deleted Policy ' . $policyName,
            'made_by' => $user->first_name . ' ' . $user->last_name,
            'user_id' => $user->user_id,
            'user_type' => $userType,
            'action_type' => 'Delete Policy',
            'department' => $user->dept_name,
        ]);

        return redirect()->route('ma.policy')->with('success', 'Policy ' . $policyName . ' Deleted Successfully.');
    }

    public function download_policy()
    {
        $cms = $this->cms();

        // Fetch data from the resources query
        $policy = DB::table('policies')
            ->select('policies.*')
            ->where('department_owner', $cms['dept_label'])
            ->get();

        // Create the CSV
        $csvData = '';
        // Define the header for the CSV
        $csvHeader = [
            'Policy ID',
            'Policy Name',
            'Content',
            'Inclusions',
            'Department Owner',
            'Created Datetime',
            'Updated Datetime'
        ];
        $csvData .= implode(',', $csvHeader) . "\n";

        // Add resources data
        foreach ($policy as $policies) {
            $csvData .= implode(',', [
                // Wrap each field in double quotes and escape any quotes in the content
                '"' . str_replace('"', '""', $policies->policy_id) . '"',
                '"' . str_replace('"', '""', $policies->policy_name) . '"',
                '"' . str_replace('"', '""', $policies->policy_content) . '"',
                '"' . str_replace('"', '""', $policies->inclusions) . '"',
                '"' . str_replace('"', '""', $policies->department_owner) . '"',
                '"' . str_replace('"', '""', $policies->created_at) . '"',
                '"' . str_replace('"', '""', $policies->updated_at) . '"',
            ]) . "\n";
        }

        // Return CSV as a download
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="policies.csv"');

        return redirect()->route('ma.policy')->with('success', $cms['dept'] . ' Policies Downloaded!');
    }

    // Personal Account Management
    public function change_password(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        // Check if user is authenticated and has the correct user type
        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Check if all required fields are filled
        if (!$request->filled(['old_pass', 'new_pass', 'confirm_pass'])) {
            return redirect()->route('ma.account')->withErrors(['general' => 'All Fields are Required']);
        }

        // Check if new password and confirmation match
        if ($request->new_pass !== $request->confirm_pass) {
            return redirect()->route('ma.account')->withErrors(['general' => 'New Password and Confirm Password Do Not Match']);
        }

        // Validate password strength
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/', $request->new_pass)) {
            return redirect()->route('ma.account')->withErrors(['general' => 'New Password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.']);
        }

        // Verify that the old password matches the current password
        if (!Hash::check($request->old_pass, $user->password)) {
            return redirect()->route('ma.account')->with('info', 'Old Password Does Not Match');
        }

        try {
            DB::beginTransaction();

            // Update the Password
            DB::table('user_accounts')
                ->where('user_id', $user->user_id)
                ->update(['password' => Hash::make($request->new_pass)]);

            // Register the action in the audit table
            audit::create([
                'action' => 'Changed Password',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Account Credential Update',
                'department' => $user->dept_name,
            ]);

            DB::commit();
            return redirect()->route('ma.account')->with('success', 'Password changed Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Redirected with Error Message
            return redirect()->route('ma.account')->withErrors(['general' => 'An error occurred while changing the password.']);
        }
    }

    public function image_upload(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $cms = $this->cms();

        // Check if reset_image flag is present in the request
        if ($request->has('reset_image')) {
            return $this->resetImage($request);
        }

        // Continue with normal image upload process if reset_image is not set
        if (!$request->hasFile('emblem')) {
            return redirect()->route('ma.account')->with('error', 'No Image was Uploaded (File Size must be Less than 2MB.');
        }

        try {
            // Validate the image upload
            $request->validate([
                'emblem' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maximum file size of 2MB
            ]);

            // Image Upload
            $image = $request->file('emblem');

            if ($image) {
                $userId = $this->sanitizeFilename($request->user()->user_id);
                $lastName = $this->sanitizeFilename($request->user()->last_name);
                $deptLabel = $this->sanitizeFilename($cms['dept_label']);

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
                $path = $image->storeAs('public/' . $deptLabel . '/admin_profiles', $fileName);

                // Convert the storage path to public path
                $path = str_replace('public/', 'storage/', $path);

                // Update the image path in the database
                DB::table('user_accounts')
                    ->where('user_id', $userId)
                    ->update(['profile_pic' => $path]);

                // Register the action in the audit table
                audit::create([
                    'action' => 'Changed Image',
                    'made_by' => $user->first_name . ' ' . $user->last_name,
                    'user_id' => $user->user_id,
                    'user_type' => $userType,
                    'action_type' => 'Account Credential Update',
                    'department' => $user->dept_name,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Image uploaded successfully.']);
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
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        try {
            // Path to the default image
            $defaultImagePath = 'storage/assets/default_image.png';

            // Update the user's profile picture to the default image path
            DB::table('user_accounts')
                ->where('user_id', $user->user_id)
                ->update(['profile_pic' => $defaultImagePath]);

            // Log the action in the audit table
            audit::create([
                'action' => 'Reset Image to Default',
                'made_by' => $user->first_name . ' ' . $user->last_name,
                'user_id' => $user->user_id,
                'user_type' => $userType,
                'action_type' => 'Account Credential Update',
                'department' => $user->dept_name,
            ]);

            return response()->json(['success' => true, 'message' => 'Image reset to default.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
        }
    }


    // Master Features Only
    public function ma_cms()
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $department = session('dept_name');
        $cms = $this->cms();

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        // Fetch current duration values
        $currentDurations = cms_reservation_duration::where('department', $department)
            ->pluck('duration', 'cms_resource_type')
            ->toArray();

        // Fetch current accent color
        $currentAccentColor = $cms['accent_color'] ?? '#E9F3FD';

        $aboutContent = cms_about::where('department', $department)->first();

        return view('login.masteradmin.master_cms', compact(
            'userData',
            'cms',
            'currentDurations',
            'currentAccentColor',
            'aboutContent'
        ));
    }

    public function account_management()
    {
        // Fetch user data
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;
        $email = $user->email;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic,
            'email' => $email
        ];

        $cms = $this->cms();

        // Fetch all user accounts excluding the current user
        $accounts = DB::table('user_accounts')
            ->join('user_type', 'user_accounts.user_type', '=', 'user_type.type_id')
            ->where('user_accounts.user_id', '<>', $user->user_id)
            ->where('user_accounts.dept_name', '=', $cms['dept_label']) // Exclude the current user
            ->select(
                'user_accounts.user_id',
                'user_accounts.first_name',
                'user_accounts.last_name',
                'user_accounts.email',
                'user_accounts.position',
                'user_accounts.status',
                'user_accounts.timestamp',
                'user_accounts.dept_name',
                'user_type.user_type as user_type_name'
            )
            ->get();

        // Retrieve List of Existing Departments
        $departments = departments::pluck('department_name');

        // Format account data
        foreach ($accounts as $account) {
            $account->status = $account->status == 1 ? 'Activated' : 'Deactivated';
            $account->position = ucfirst($account->position);
            $account->timestamp = $account->timestamp ? Carbon::parse($account->timestamp)->format('F j, Y g:i A') : 'N/A';
        }

        // Separate accounts into students and faculty
        $student = $accounts->filter(function ($account) {
            return $account->position === 'Student';
        });

        $faculty = $accounts->filter(function ($account) {
            return $account->position === 'Faculty';
        });

        $combinedRequests = collect()
            ->merge($student)
            ->merge($faculty)
            ->unique('status');

        // Return the view with all required data
        return view('login.masteradmin.master_manage_accounts', compact(
            'userData',
            'student',
            'faculty',
            'cms',
            'departments',
            'combinedRequests'
        ));
    }

    public function audit(Request $request)
    {
        $user = Auth::user();
        $position = $user->position;

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;
        $department = $user->dept_name;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $cms = $this->cms();

        $search = $request->input('search', '');

        $audit = DB::table('audits')
            ->join('departments', 'audits.department', '=', 'departments.department_name')
            ->where('audits.department', $user->dept_name)
            ->where(function ($query) use ($search) {
                $query->where('audits.action', 'like', "%{$search}%")
                    ->orWhere('audits.id', 'like', "%{$search}%")
                    ->orWhere('audits.made_by', 'like', "%{$search}%")
                    ->orWhere('audits.department', 'like', "%{$search}%")
                    ->orWhere('audits.user_id', 'like', "%{$search}%")
                    ->orWhere('audits.action_type', 'like', "%{$search}%")
                    ->orWhere(DB::raw("DATE_FORMAT(audits.datetime, '%M %d, %Y %h:%i %p')"), 'like', "%{$search}%");
            })
            ->orderBy('audits.datetime', 'desc')
            ->select('audits.*')
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
                return is_object($item) && property_exists($item, 'action_type'); // Only include objects with 'action_type' property
            })
            ->unique('action_type')
            ->sortBy('action_type') // Sort by 'action_type' in ascending order
            ->values();

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 200;
        $items = $combinedRequests->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $combinedRequestsPaginated = new LengthAwarePaginator($items, $combinedRequests->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $audit_archived = DB::table('audits_archive')
            ->join('departments', 'audits_archive.department', '=', 'departments.department_name')
            ->where('audits_archive.department', $user->dept_name)
            ->where(function ($query) use ($search) {
                $query->where('audits_archive.action', 'like', "%{$search}%")
                    ->orWhere('audits_archive.id', 'like', "%{$search}%")
                    ->orWhere('audits_archive.department', 'like', "%{$search}%")
                    ->orWhere('audits_archive.made_by', 'like', "%{$search}%")
                    ->orWhere('audits_archive.user_id', 'like', "%{$search}%")
                    ->orWhere('audits_archive.action_type', 'like', "%{$search}%")
                    ->orWhere(DB::raw("DATE_FORMAT(audits_archive.datetime, '%M %d, %Y %h:%i %p')"), 'like', "%{$search}%");
            })
            ->select('audits_archive.*')
            ->orderBy('audits_archive.datetime', 'desc')
            ->paginate(500)
            ->appends(['search' => $search]);


        foreach ($audit_archived as $entry) {
            $entry->datetime = Carbon::parse($entry->datetime)->format('F d, Y h:i A');
        }

        return view('login.masteradmin.master_audit', compact('userData', 'cms', 'audit', 'search', 'combinedRequestsPaginated', 'audit_archived'));
    }

    public function audits_archived(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;
        $department = $user->dept_name;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $cms = $this->cms();

        $search = $request->input('search', '');

        $audit_archived = DB::table('audits_archive')
            ->join('departments', 'audits_archive.department', '=', 'departments.department_name')
            ->where('audits_archive.department', $user->dept_name)
            ->where(function ($query) use ($search) {
                $query->where('audits_archive.action', 'like', "%{$search}%")
                    ->orWhere('audits_archive.id', 'like', "%{$search}%")
                    ->orWhere('audits_archive.department', 'like', "%{$search}%")
                    ->orWhere('audits_archive.made_by', 'like', "%{$search}%")
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

        return view('login.masteradmin.master_archived_audits', compact('userData', 'cms', 'audit_archived', 'search'));
    }

    public function feedback_archived(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $userId = $user->user_id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $profile_pic = $user->profile_pic;
        $department = $user->dept_name;

        $userData = [
            'user_id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'profile_pic' => $profile_pic
        ];

        $cms = $this->cms();

        $search = $request->input('search', '');

        $feedback_archived = DB::table('archive_feedbacks')
            ->join('resources', 'archive_feedbacks.resource_id', '=', 'resources.resource_id')
            ->join('departments', 'resources.department_owner', '=', 'departments.department_name')
            ->join('user_accounts', 'archive_feedbacks.username', '=', 'user_accounts.user_id')
            ->where('resources.department_owner', $cms['dept'])
            ->where(function ($query) use ($search) {
                $query->where('archive_feedbacks.feedback', 'like', "%{$search}%")
                    ->orWhere('user_accounts.last_name', 'like', "%{$search}%")
                    ->orWhere('user_accounts.first_name', 'like', "%{$search}%")
                    ->orWhere('archive_feedbacks.username', 'like', "%{$search}%")
                    ->orWhere('archive_feedbacks.feedback_id', 'like', "%{$search}%")
                    ->orWhere('archive_feedbacks.rating', 'like', "%{$search}%")
                    ->orWhere(DB::raw("DATE_FORMAT(archive_feedbacks.created_at, '%M %d, %Y %h:%i %p')"), 'like', "%{$search}%");
            })
            ->select(
                'user_accounts.first_name',
                'user_accounts.last_name',
                'resources.resource_name',
                'archive_feedbacks.*',
            )
            ->orderBy('archive_feedbacks.created_at', 'desc')
            ->paginate(20)
            ->appends(['search' => $search]);


        foreach ($feedback_archived as $entry) {
            $entry->created_at = Carbon::parse($entry->created_at)->format('F d, Y h:i A');
        }

        return view('login.masteradmin.master_archived_feedbacks', compact(
            'userData',
            'cms',
            'feedback_archived',
            'search'
        ));
    }


    // Logic
    public function cms_general(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        $departmentName = session('dept_name');

        // Fetch the department by name to get its ID
        $department = departments::where('department_name', $departmentName)->first();

        if (!$department) {
            return redirect()->route('login')->with('error', 'Department not found.');
        }

        $departmentId = $department->department_id;

        // Fetch CMS settings for the department using the ID
        $cms = cms::where('dept_id', $departmentId)->first();

        if (!$user || $user->user_type == 1 || $user->user_type == 2) {
            return redirect()->route('login');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            // Add detailed error message for debugging
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Max of 2MB(Accepts Only: JPEG, JPG, PNG, GIF)');
        }

        try {
            DB::transaction(function () use ($request, $user, $cms, $departmentName) {
                // Save to cms table
                if ($request->has('accent_color')) {
                    $cms->color = $request->input('accent_color');
                }

                if ($request->file('bg_image')) {
                    $bgImagePath = $this->cms_imageupload($request->file('bg_image'), 'background', $cms->bg_image ?? 'assets/default_bg.png');
                    $cms->bg_image = $bgImagePath;
                }

                $cms->edited_by = $user->user_id;
                $cms->edited_at = now();
                $cms->save();

                $resourceTypes = [
                    'Equipment' => 'equipment_max_duration',
                    'Laboratory' => 'laboratory_max_duration',
                    'Facility' => 'facility_max_duration',
                ];

                foreach ($resourceTypes as $resourceType => $inputName) {
                    if ($request->has($inputName)) {
                        $newDuration = $request->input($inputName);

                        // Fetch current duration
                        $currentDuration = DB::table('cms_reservation_duration')
                            ->where('department', $departmentName)
                            ->where('cms_resource_type', $resourceType)
                            ->value('duration');

                        // Update only if duration has changed
                        if ($currentDuration != $newDuration) {
                            DB::table('cms_reservation_duration')->updateOrInsert(
                                [
                                    'department' => $departmentName,
                                    'cms_resource_type' => $resourceType,
                                ],
                                [
                                    'duration' => $newDuration,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]
                            );
                        }
                    }
                }
            });

            return redirect()->route('ma.cms')->with('success', 'Settings Updated Successfully.');
        } catch (\Exception $e) {
            return redirect()->route('ma.cms')->with('error', 'An error occurred. Please try again.');
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return redirect()->route('ma.cms')->with('error', 'Max of 2MB(Accepts Only: JPEG, JPG, PNG, GIF)');
        }
    }

    protected function cms_imageupload($image, $type, $defaultPath)
    {
        try {
            $userId = Auth::user()->user_id;
            $lastName = Auth::user()->last_name;

            $department = session('dept_name');
            $sanitizedDept = $this->sanitizeFilename($department);

            $timestamp = now()->timestamp;
            $fileName = $userId . '_' . $lastName . '_' . $timestamp . '.' . $image->getClientOriginalExtension();

            // Determine the storage path based on image type
            $directory = $type == 'background' ? $sanitizedDept . '/' . $type : $type;
            $path = $directory . '/' . $fileName;

            // Construct the full path to the existing image
            $existingPath = $type == 'background'
                ? 'public/' . $sanitizedDept . '/' . $type . '/' . basename($defaultPath)
                : 'public/' . $type . '/' . basename($defaultPath);

            // Full path in storage directory
            $fullExistingPath = str_replace('public/', '', $existingPath);

            // Delete the existing image if it exists
            if ($defaultPath && Storage::disk('public')->exists($fullExistingPath)) {
                Storage::disk('public')->delete($fullExistingPath);
            }

            // Store the new image in the storage directory
            $image->storeAs($directory, $fileName, 'public');

            return $path;
        } catch (\Exception $e) {
            return $defaultPath;
        }
    }

    public function cms_about(Request $request)
    {
        $user = Auth::user();
        $departmentName = session('dept_name');

        // Validate the input data
        $validatedData = $request->validate([
            'header' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            cms_about::updateOrCreate(
                ['department' => $departmentName],
                [
                    'header' => $validatedData['header'],
                    'content' => $validatedData['content'],
                ]
            );

            return redirect()->route('ma.cms')->with('success', 'About Us Header Updated Successfully!');
        } catch (\Exception $e) {
            return redirect()->route('ma.cms')->with('error', $e->getMessage());
        }
    }

    public function update_account(Request $request, $userId)
    {
        $user = Auth::user();
        $userType = $user->userType->user_type;

        if (!$user || $user->user_type == 1) {
            return redirect()->route('login');
        }

        $request->validate([
            'user_id' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'position' => 'required|string',
            'status' => 'required|integer',
            'user_type' => 'required|integer',
        ]);

        $changes = [];

        // Update Account
        $account = user_accounts::find($userId);

        $statusLabel = [
            0 => 'Inactive',
            1 => 'Active'
        ];

        if ($account) {
            if ($account->user_id != $request->user_id) {
                $changes[] = 'User: ' . $account->user_id . ' User ID: ' . $account->user_id . ' to ' . $request->user_id;
            }
            if ($account->first_name != $request->first_name) {
                $changes[] = 'User: ' . $account->user_id . ' First Name: ' . $account->first_name . ' to ' . $request->first_name;
            }
            if ($account->last_name != $request->last_name) {
                $changes[] = 'User: ' . $account->user_id . ' Last Name: ' . $account->last_name . ' to ' . $request->last_name;
            }
            if ($account->position != ucfirst($request->position)) {
                $changes[] = 'User: ' . $account->user_id . ' Position: ' . $account->position . ' to ' . ucfirst($request->position);
            }
            if ($account->status != $request->status) {
                $changes[] = 'User: ' . $account->user_id . ' Status: ' . $statusLabel[$account->status] . ' to ' . $statusLabel[$request->status];
            }
            if ($account->user_type != (int)$request->user_type) {
                $changes[] = 'User: ' . $account->user_id . ' User Type: ' . $account->user_type . ' to ' . (int)$request->user_type;
            }
            if ($account->dept_name != $request->department) {
                $changes[] = 'User: ' . $account->user_id . ' Department: ' . $account->dept_name . ' to ' . $request->department;
            }

            $account->user_id = $request->user_id;
            $account->first_name = $request->first_name;
            $account->last_name = $request->last_name;
            $account->position = ucfirst($request->position);
            $account->status = $request->status;
            $account->user_type = (int)$request->user_type;
            $account->dept_name = $request->department;

            $account->save();

            // Register the action in the audit table
            if (!empty($changes)) {
                audit::create([
                    'action' => implode(', ', $changes),
                    'made_by' => $user->first_name . ' ' . $user->last_name,
                    'user_id' => $user->user_id,
                    'user_type' => $userType,
                    'action_type' => 'Update User Credential',
                    'department' => $user->dept_name,
                ]);
            }

            return redirect()->back()->withInput()->with('success', 'User (' . $account->user_id . ') Credential Updated!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Oops');
        }
    }

    // Archive Records
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
            return response()->json(['success' => false, 'message' => 'An Error Occurred while Archiving the Record.' . $e]);
        }
    }

    public function mass_archive(Request $request)
    {
        $user = Auth::user();
        $departmentName = session('dept_name');

        $threshold = Carbon::now()->subDays(7); // Get date 7 days ago

        // Fetch the records to be archived and deleted
        $audits = audit::where('datetime', '<', $threshold)->where('department', $departmentName)->get();

        if ($audits->isEmpty()) {
            return redirect()->route('ma.audit')->with('error', 'No records found older than 7 days.');
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
        return redirect()->route('ma.audit')->with('success', $message);
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

    public function archive_feedback(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Find the feedback by ID
            $feedback = Feedback::findOrFail($id);

            // Archive logic here (transfer to archive_feedbacks table)
            DB::table('archive_feedbacks')->insert([
                'feedback_id' => $feedback->feedback_id,
                'username' => $feedback->username,
                'feedback' => $feedback->feedback,
                'resource_id' => $feedback->resource_id,
                'rating' => $feedback->rating,
                'created_at' => $feedback->created_at,
                'updated_at' => $feedback->updated_at
            ]);

            // Delete the feedback from the original table
            $feedback->delete();

            // Recalculate the average rating of the remaining feedbacks for the resource
            $resource = resources::find($feedback->resource_id);
            $averageRating = $resource->feedbacks()->avg('rating');

            // If there are no feedbacks left, set the rating to 0 or null
            $resource->rating = $averageRating ?? 0;
            $resource->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Feedback archived and rating updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'Failed to archive feedback']);
        }
    }

    public function restore_feedback(Request $request, $id)
    {
        try {
            $feedback = archive_feedback::findOrFail($id);

            feedback::create([
                'feedback_id' => $feedback->feedback_id,
                'username' => $feedback->username,
                'feedback' => $feedback->feedback,
                'resource_id' => $feedback->resource_id,
                'rating' => $feedback->rating,
                'created_at' => $feedback->created_at,
                'updated_at' => $feedback->updated_at
            ]);

            $resource = resources::find($feedback->resource_id);
            $averageRating = $resource->feedbacks()->avg('rating');
            $resource->rating = $averageRating;
            $resource->save();

            $feedback->delete();

            return response()->json(['success' => true, 'message' => 'Feedback Archived Successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to restore feedback: ' . $e->getMessage()); // Log the error message
            return response()->json(['success' => false, 'message' => 'Failed to Archive Feedback']);
        }
    }
}
