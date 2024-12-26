<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Requests {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.users.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Student Approval</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <div class="bg-custom-color mx-4 p-4 mt-4 rounded-lg shadow-lg sm:text-sm" style="background-color: var(--custom-color);">

                <!-- Tabs and Search -->
                <div class="mt-2 lg:flex lg:flex-row justify-between font-mont py-4">

                    <!-- Search -->
                    <div class="flex sm:justify-center justify-end mb-3 sm:mt-0 relative">
                        <input type="text" id="searchInput" placeholder="Search" class="w-full sm:placeholder-gray-200 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 font-mont text-sm sm:text-base">
                    </div>
                </div>

                <!-- Dropdown Filter by Status -->
                <div class="flex flex-row lg:justify-end md:justify-end sm:justify-center m-0 py-2">
                    <label for="statusFilter" class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5"><i class="fas fa-filter text-gray-500 text-lg"></i> Status: </label>
                    <select id="statusFilter" class="mt-1 flex-row w-18 border-gray-300 rounded-md p-1 font-mont">
                        <option value="" selected>All</option>
                        @foreach($requests->unique('noted_by') as $request)
                        <option value="{{ $request->noted_by ? 'Approved' : 'Pending' }}">{{ $request->noted_by ? 'Approved' : 'Pending' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tab Content -->
                <div class="bg-white">
                    <!-- All Tab -->
                    <div id="all" class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont" style="background-color: var(--custom-color);">
                        <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll">
                            <table id="allTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Transaction ID</th>
                                        <th scope="col" class="px-6 py-3">User</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Resource Name</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Pickup Date
                                            <button id="pickupDateFilterAll" onclick="toggleSort('all', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Return Date</th>
                                        <th scope="col" class="px-6 py-3 text-center">Approval Status</th>
                                        <th scope="col" class="px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $request)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $request->transaction_id ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-gray-700">
                                            {{ $request->requester_last_name }}, {{ $request->requester_first_name }} <br>
                                            <small class="text-gray-400">{{ $request->position }}</small>
                                        </td>
                                        <td class="px-6 py-2 bg-[#fafafa]">{{ $request->resource_name }}<br>
                                            <small class="text-gray-400">{{ $request->department_owner }}</small>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($request->return_datetime)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->return_datetime)->format('h:i A') }}</div>
                                        </td>
                                        <td class="status-cell px-6 py-2 text-left">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $request->noted_by ? 'text-green-500' : 'text-yellow-500' }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $request->noted_by ? 'green' : 'yellow' }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $request->noted_by ? 'Approved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 no-underline text-center">
                                            <a href="{{ route('faculty.overview', ['transaction_id' => $request->transaction_id]) }}" class="text-blue-600 hover:underline">
                                                <i class="fs-5 bi bi-eye-fill no-underline"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <td colspan="8" class="text-center text-gray-500 font-medium px-6 py-4">No records found.</td>
                                    @endforelse
                                    <tr class="no-records" style="display: none;">
                                        <td colspan="8" class="text-center text-gray-500 font-medium px-6 py-4">No records found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

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
            document.addEventListener('DOMContentLoaded', function() {
                // Add event listener for the status filter dropdown
                document.getElementById('statusFilter').addEventListener('change', function() {
                    const selectedStatus = this.options[this.selectedIndex].text.trim(); // Get selected status text

                    // Get the currently active tab
                    const activeTabPane = document.querySelector('.tab-pane.active'); // Assuming only one active pane at a time
                    const rows = activeTabPane ? activeTabPane.querySelectorAll('tbody tr') : []; // Get rows within the active tab

                    let foundMatch = false; // Track if there is at least one matching row

                    rows.forEach(row => {
                        const statusCell = row.querySelector('.status-cell'); // Target the cell with status text
                        const statusText = statusCell ? statusCell.textContent.trim() : ''; // Get the text inside the status cell

                        // Show or hide row based on selected status
                        if (selectedStatus === "All" || selectedStatus === statusText) {
                            row.style.display = ''; // Show the row
                            foundMatch = true; // Found at least one matching row
                        } else {
                            row.style.display = 'none'; // Hide the row
                        }
                    });

                    // Show "No records found" message if no rows match the selected status
                    const noRecordsRow = activeTabPane.querySelector('.no-records');
                    if (noRecordsRow) {
                        noRecordsRow.style.display = foundMatch ? 'none' : ''; // Show or hide "No records found" row
                    }
                });
            });


            function showTab(tabId) {
                const panes = document.querySelectorAll('.tab-pane');
                panes.forEach(pane => {
                    pane.style.display = pane.id === tabId ? 'block' : 'none';
                    pane.classList.toggle('active', pane.id === tabId); // Mark the current tab as active
                });

                const links = document.querySelectorAll('.tab-link');
                links.forEach(link => {
                    link.classList.remove('border-blue-500', 'text-blue-500');
                    link.classList.add('border-transparent', 'text-gray-700');
                });

                const activeLink = document.querySelector(`[onclick="showTab('${tabId}')"]`);
                if (activeLink) {
                    activeLink.classList.add('border-blue-500', 'text-blue-500');
                    activeLink.classList.remove('border-transparent', 'text-gray-700');
                }

                // Trigger the status filter to update rows in the newly active tab
                document.getElementById('statusFilter').dispatchEvent(new Event('change'));
            }

            document.addEventListener('DOMContentLoaded', () => showTab('all')); // Load "All" tab by default
        </script>

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
                    const cellIndex = column === 'pickup' ? 3 : 4; // Adjust these indices as necessary
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
</body>

</html>