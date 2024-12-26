<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }

        .active-tab {
            color: #3b82f6;
            border-bottom: 2px solid #3b82f6;
        }
    </style>
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.masteradmin.master_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Account Management</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <div class="bg-custom-color mx-4 p-4 mt-4 rounded-lg shadow-lg sm:text-sm" style="background-color: var(--custom-color);">

                <!-- Tabs and Search -->
                <div class="mt-2 lg:flex lg:flex-row justify-between font-mont py-4">
                    <!-- Tabs -->
                    <nav class="flex justify-around sm:justify-start mb-3 sm:mb-0 flex-nowrap lg:space-x-3">
                        <span class="tab-link w-full sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('all')">
                            All
                        </span>
                        <span class="tab-link w-full sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('students')">
                            Students
                        </span>
                        <span class="tab-link w-full sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('faculty')">
                            Faculty
                        </span>
                    </nav>

                    <!-- Search -->
                    <div class="flex sm:justify-center justify-end mb-3 sm:mt-0">
                        <input type="text" id="search" placeholder="Search" class="w-full sm:placeholder-gray-200 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 font-mont text-sm sm:text-base">
                    </div>
                </div>

                <!-- Dropdown Filter by Status -->
                <div
                    class="flex flex-row lg:justify-end md:justify-end sm:justify-center m-0 py-2 px-2">
                    <label
                        for="statusFilter"
                        class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5"><i class="fas fa-filter text-gray-400"></i> Status:
                    </label>
                    <select
                        id="statusFilter"
                        class="mt-1 flex-row w-18 border-gray-300 rounded-md p-1 font-mont">
                        <option value="" selected>All</option>
                        @foreach($combinedRequests->unique('status') as $status)
                        <option value="{{ $status->status }}">
                            {{ $status->status }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tab Content for All -->
                <div class="bg-white">
                    <div id="all" class="tab-pane mb-2 font-mont" style="background-color: var(--custom-color);">
                        <div class="overflow-x-auto">
                            <table id="allTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-black uppercase bg-gray-100">
                                    <tr class="text-gray-700">
                                        <th class="px-4 py-2 font-large text-center">ID</th>
                                        <th class="px-4 py-2 font-large text-center">User</th>
                                        <th class="px-4 py-2 font-large text-center">Email</th>
                                        <th class="px-4 py-2 font-large text-center">Role</th>
                                        <th class="px-4 py-2 font-large text-center">Status</th>
                                        <th class="px-4 py-2 font-large text-center text-nowrap">Date Created
                                            <button id="pickupDateFilterAll" onclick="toggleSort('all', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th class="px-4 py-2 font-large text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->merge($faculty) as $account)
                                    <tr class="{{ $account->status == 'Activated' ? 'bg-green-100' : 'bg-red-100' }} text-sm bg-white">
                                        <td class="px-4 py-2 border-b text-center text-wrap text-gray-900 font-medium">{{ $account->user_id }}</td>
                                        <td class="px-4 py-2 border-b text-wrap">{{ $account->first_name }} {{ $account->last_name }}</td>
                                        <td class="px-4 py-2 border-b truncate max-w-[200px] group">{{ $account->email }}
                                            <!-- Tooltip for full content on hover -->
                                            <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-x-auto z-10">
                                                {{ $account->email }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border-b text-center text-wrap">{{ ucfirst($account->position) }}</td>
                                        <td class="status-cell px-4 py-2 border-b text-center">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $account->status == 'Activated' ? 'text-green-500' : 'text-red-500' }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $account->status == 'Activated' ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $account->status == 'Activated' ? 'Activated' : 'Deactivated' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($account->timestamp)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($account->timestamp)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-4 py-2 border-b text-center text-wrap">
                                            <button class="font-mont px-2 py-1 text-white bg-yellow-500 rounded-lg hover:bg-yellow-700" onclick='openEditModal(<?= json_encode($account) ?>)'>Edit</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <td colspan="8" class="text-center text-gray-500 font-medium px-6 py-4">No records found.</td>
                                    @endforelse
                                    <tr class="no-records" style="display: none">
                                        <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Content for Students -->
                    <div id="students" class="tab-pane mb-2 font-mont" style="background-color: var(--custom-color);">
                        <div class="overflow-x-auto">
                            <table id="studentsTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-black uppercase bg-gray-100">
                                    <tr class="text-gray-700">
                                        <th class="px-4 py-2 font-large text-center">ID</th>
                                        <th class="px-4 py-2 font-large text-center">User</th>
                                        <th class="px-4 py-2 font-large text-center">Email</th>
                                        <th class="px-4 py-2 font-large text-center">Role</th>
                                        <th class="px-4 py-2 font-large text-center">Status</th>
                                        <th class="px-4 py-2 font-large text-center text-nowrap">Date Created
                                            <button id="pickupDateFilterStudents" onclick="toggleSort('students', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th class="px-4 py-2 font-large text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student as $account)
                                    <tr class="{{ $account->status == 'Activated' ? 'bg-green-100' : 'bg-red-100' }} text-sm bg-white">
                                        <td class="px-4 py-2 border-b text-center font-medium text-gray-900">{{ $account->user_id }}</td>
                                        <td class="px-4 py-2 border-b">{{ $account->first_name }} {{ $account->last_name }}</td>
                                        <td class="px-4 py-2 border-b truncate max-w-[200px] group">{{ $account->email }}
                                            <!-- Tooltip for full content on hover -->
                                            <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-x-auto z-10">
                                                {{ $account->email }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border-b text-center">{{ ucfirst($account->position) }}</td>
                                        <td class="status-cell px-4 py-2 border-b text-center">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $account->status == 'Activated' ? 'text-green-500' : 'text-red-500' }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $account->status == 'Activated' ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $account->status == 'Activated' ? 'Activated' : 'Deactivated' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 border-b whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($account->timestamp)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($account->timestamp)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-4 py-2 border-b text-center">
                                            <button class="font-mont px-2 py-1 text-white bg-yellow-500 rounded-lg hover:bg-yellow-700" onclick='openEditModal(<?= json_encode($account) ?>)'>Edit</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <td colspan="8" class="text-center text-gray-500 font-medium px-6 py-4">No records found.</td>
                                    @endforelse
                                    <tr class="no-records" style="display: none">
                                        <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Content for Faculty -->
                    <div id="faculty" class="tab-pane mb-2 font-mont" style="background-color: var(--custom-color);">
                        <div class="overflow-x-auto">
                            <table id="facultyTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-black uppercase bg-gray-100">
                                    <tr class="text-gray-700">
                                        <th class="px-4 py-2 font-large text-center">ID</th>
                                        <th class="px-4 py-2 font-large text-center">User</th>
                                        <th class="px-4 py-2 font-large text-center">Email</th>
                                        <th class="px-4 py-2 font-large text-center">Role</th>
                                        <th class="px-4 py-2 font-large text-center">Status</th>
                                        <th class="px-4 py-2 font-large text-center text-nowrap">Date Created
                                            <button id="pickupDateFilterFaculty" onclick="toggleSort('faculty', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th class="px-4 py-2 font-large text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($faculty as $account)
                                    <tr class="{{ $account->status == 'Activated' ? 'bg-green-100' : 'bg-red-100' }} text-sm bg-white">
                                        <td class="px-4 py-2 border-b text-center font-medium text-gray-900">{{ $account->user_id }}</td>
                                        <td class="px-4 py-2 border-b">{{ $account->first_name }} {{ $account->last_name }}</td>
                                        <td class="px-4 py-2 border-b truncate max-w-[200px] group">{{ $account->email }}
                                            <!-- Tooltip for full content on hover -->
                                            <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-x-auto z-10">
                                                {{ $account->email }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border-b text-center">{{ ucfirst($account->position) }}</td>
                                        <td class="status-cell px-4 py-2 border-b text-center">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $account->status == 'Activated' ? 'text-green-500' : 'text-red-500' }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $account->status == 'Activated' ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $account->status == 'Activated' ? 'Activated' : 'Deactivated' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 border-b whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($account->timestamp)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($account->timestamp)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-4 py-2 border-b text-center">
                                            <button class="font-mont px-2 py-1 text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 font-mont" onclick='openEditModal(<?= json_encode($account) ?>)'>Edit</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <td colspan="8" class="text-center text-gray-500 font-medium px-6 py-4">No records found.</td>
                                    @endforelse
                                    <tr class="no-records" style="display: none">
                                        <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div id="editModal" class="fixed inset-0 items-center justify-center z-50 hidden font-mont">
                <div class="absolute inset-0 bg-black opacity-50"></div>
                <div class="max-h-screen bg-white rounded-lg shadow-lg p-8 z-10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Edit Account</h2>
                        <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
                    </div>
                    <form id="editForm" action="{{ route('ma.update.account', ['userId' => 'USER_ID']) }}" method="POST">
                        @csrf
                        <input type="hidden" id="editUserId" name="user_id">
                        <div class="mb-4">
                            <label for="editStudentNumber" class="block text-gray-700">Student Number</label>
                            <input type="text" id="editStudentNumber" name="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont">
                        </div>
                        <div class="flex mb-4 space-x-4">
                            <div class="w-1/2">
                                <label for="editFirstName" class="block text-gray-700">First Name</label>
                                <input type="text" id="editFirstName" name="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont">
                            </div>
                            <div class="w-1/2">
                                <label for="editLastName" class="block text-gray-700">Last Name</label>
                                <input type="text" id="editLastName" name="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="editEmail" class="block text-gray-700">Email</label>
                            <input type="email" id="editEmail" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="editRole" class="block text-gray-700">Role</label>
                            <select id="editRole" name="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont">
                                <option value="student">Student</option>
                                <option value="faculty">Faculty</option>
                            </select>
                        </div>
                        <div class="mb-4 flex justify-between">
                            <div class="w-full mr-1">
                                <label for="editStatus" class="block text-gray-700">Status</label>
                                <select id="editStatus" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont">
                                    <option value="1">Activated</option>
                                    <option value="0">Deactivated</option>
                                </select>
                            </div>
                            <div class="w-full">
                                <label for="editUserType" class="block text-gray-700">User Type</label>
                                <select id="editUserType" name="user_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont">
                                    <option value="1">User</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Master Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="editDepartment" class="block text-gray-700">Department</label>
                            <select id="editDepartment" name="department" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 font-mont">
                                @foreach ($departments as $department)
                                @if ($department != 'All')
                                <option value="{{ $department }}" {{ old('departments') == $department ? 'selected' : '' }}>{{ $department }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg mr-2 font-mont">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-mont">Save</button>
                        </div>
                    </form>

                </div>
            </div>


            <script>
                function showTab(tabId) {
                    const panes = document.querySelectorAll('.tab-pane');
                    panes.forEach(pane => pane.style.display = 'none');

                    const links = document.querySelectorAll('.tab-link');
                    links.forEach(link => {
                        link.classList.remove('border-blue-500', 'text-blue-500');
                        link.classList.add('border-transparent', 'text-gray-700');
                    });

                    const activePane = document.getElementById(tabId);
                    if (activePane) {
                        activePane.style.display = 'block';
                    }

                    const activeLink = document.querySelector(`[onclick="showTab('${tabId}')"]`);
                    if (activeLink) {
                        activeLink.classList.add('border-blue-500', 'text-blue-500');
                        activeLink.classList.remove('border-transparent', 'text-gray-700');
                    }
                }

                document.addEventListener('DOMContentLoaded', () => showTab('all'));

                // Search functionality
                document.getElementById('search').addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });

                // Open and close modal
                function openEditModal(account) {
                    console.log("Account data received:", account); // Debugging line to check account data

                    // Assuming route is predefined and userId is to be replaced dynamically
                    const baseUrl = "{{ route('ma.update.account', ['userId' => 'USER_ID']) }}";
                    const url = baseUrl.replace('USER_ID', account.user_id);
                    document.getElementById('editForm').action = url;

                    // Populate form fields
                    document.getElementById('editUserId').value = account.user_id;
                    document.getElementById('editStudentNumber').value = account.user_id; // Assuming student number is stored in user_id
                    document.getElementById('editFirstName').value = account.first_name;
                    document.getElementById('editLastName').value = account.last_name;
                    document.getElementById('editEmail').value = account.email;
                    document.getElementById('editRole').value = account.position.toLowerCase();
                    document.getElementById('editStatus').value = account.status === 'Activated' ? 1 : 0;
                    document.getElementById('editUserType').value = account.user_type_ ?? 1;
                    document.getElementById('editDepartment').value = account.dept_name;

                    // Use user_type_name for the select field
                    const userTypeSelect = document.getElementById('editUserType');
                    for (let i = 0; i < userTypeSelect.options.length; i++) {
                        if (userTypeSelect.options[i].text === account.user_type_name) {
                            userTypeSelect.selectedIndex = i;
                            break;
                        }
                    }

                    document.getElementById('editModal').style.display = 'flex';
                }


                function closeEditModal() {
                    document.getElementById('editModal').style.display = 'none';
                }
            </script>

            <!-- Status Updated Successfully -->
            @if(Session::has('success'))
            <script>
                swal("Request Status Changed", "{{Session::get('success')}}", 'info', {
                    button: true,
                    button: "OK"
                })
            </script>
            @endif

            @if(Session::has('error'))
            <script>
                swal("Oops...", "{{Session::get('error')}}", 'error', {
                    button: true,
                    button: "OK"
                })
            </script>
            @endif

            <!-- Dropdown filter -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Add event listener for the status filter dropdown
                    document
                        .getElementById("statusFilter")
                        .addEventListener("change", function() {
                            const selectedStatus =
                                this.options[this.selectedIndex].text.trim(); // Get selected status text

                            // Get the currently active tab
                            const activeTabPane =
                                document.querySelector(".tab-pane.active"); // Assuming only one active pane at a time
                            const rows = activeTabPane ?
                                activeTabPane.querySelectorAll("tbody tr") : []; // Get rows within the active tab

                            let foundMatch = false; // Track if there is at least one matching row

                            rows.forEach((row) => {
                                const statusCell = row.querySelector(".status-cell"); // Target the cell with status text
                                const statusText = statusCell ?
                                    statusCell.textContent.trim() :
                                    ""; // Get the text inside the status cell

                                // Show or hide row based on selected status
                                if (
                                    selectedStatus === "All" ||
                                    selectedStatus === statusText
                                ) {
                                    row.style.display = ""; // Show the row
                                    foundMatch = true; // Found at least one matching row
                                } else {
                                    row.style.display = "none"; // Hide the row
                                }
                            });

                            // Show "No records found" message if no rows match the selected status
                            const noRecordsRow = activeTabPane.querySelector(".no-records");
                            if (noRecordsRow) {
                                noRecordsRow.style.display = foundMatch ? "none" : ""; // Show or hide "No records found" row
                            }
                        });
                });

                function showTab(tabId) {
                    const panes = document.querySelectorAll(".tab-pane");
                    panes.forEach((pane) => {
                        pane.style.display = pane.id === tabId ? "block" : "none";
                        pane.classList.toggle("active", pane.id === tabId); // Mark the current tab as active
                    });

                    const links = document.querySelectorAll(".tab-link");
                    links.forEach((link) => {
                        link.classList.remove("border-blue-500", "text-blue-500");
                        link.classList.add("border-transparent", "text-gray-700");
                    });

                    const activeLink = document.querySelector(
                        `[onclick="showTab('${tabId}')"]`
                    );
                    if (activeLink) {
                        activeLink.classList.add("border-blue-500", "text-blue-500");
                        activeLink.classList.remove(
                            "border-transparent",
                            "text-gray-700"
                        );
                    }

                    // Trigger the status filter to update rows in the newly active tab
                    document
                        .getElementById("statusFilter")
                        .dispatchEvent(new Event("change"));
                }

                document.addEventListener("DOMContentLoaded", () =>
                    showTab("all")
                ); // Load "All" tab by default
            </script>

</body>
<!-- ToggleSorting -->
<script>
    function toggleSort(tab, column) {
        const tableId = `${tab}Table`;
        const table = document.getElementById(tableId);
        const rows = Array.from(table.rows).slice(1); // Assuming first row is the header
        const headerButton = `${column}DateFilter${tab.charAt(0).toUpperCase() + tab.slice(1)}`;
        const button = document.getElementById(headerButton);

        let isAscending = button.classList.contains('rotate-180');
        button.classList.toggle('rotate-180', !isAscending);

        rows.sort((rowA, rowB) => {
            const cellIndex = column === 'pickup' ? 5 : 6; // Adjust these indices as necessary
            const cellA = rowA.cells[cellIndex]?.textContent.trim();
            const cellB = rowB.cells[cellIndex]?.textContent.trim();

            const dateA = new Date(cellA);
            const dateB = new Date(cellB);

            // Check if the date parsing is valid
            if (isNaN(dateA) || isNaN(dateB)) {
                return 0; // In case of invalid dates, no reordering
            }

            return isAscending ? dateA - dateB : dateB - dateA;
        });

        rows.forEach(row => table.tBodies[0].appendChild(row)); // Reinsert rows in sorted order
    }
</script>

</html>