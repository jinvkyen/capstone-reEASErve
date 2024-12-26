<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- .ico logo -->
    <link rel="icon" href="/assets/logo.ico" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.users.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">My Reservations</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- My Reservations -->
            <div class="min-w-screen-full bg-custom-color mx-4 p-4 mt-4 rounded-lg shadow-lg sm:text-sm" style="background-color: var(--custom-color);">
                <div class="top-0 bg-100 h-10" style="background-color: var(--custom-color);">
                    <h1 class="lg:text-3xl sm:text-2xl font-semibold">On-Going</h1>
                </div>
                <div class="max-w-screen-lg sm:flex-row sm:justify-start sm:items-start p-10 mt-2 lg:p-6 sm:p-2 whitespace-nowrap lg:overflow-x-auto sm:overflow-x-auto rounded-lg" style="background-color: var(--custom-color);">
                    <div class="flex">
                        @forelse ($card_reservations->filter(function($item) {
                        return in_array($item->status, [2, 3, 5]);
                        }) as $item)
                        <div class="w-30 lg:w-80 flex-shri nk-0 inline-block">
                            @php
                            $route = $item->type === 'facility' ? route('facility.overview', ['id' => $item->id]) : route('overview', ['transaction_id' => $item->transaction_id]);
                            @endphp
                            <a href="{{ $route }}" class="text-black no-underline hover:no-underline">
                                @include('login.users.reservation_details_card', ['item' => $item])
                            </a>
                        </div>
                        @empty
                        <div class="flex w-full justify-center">
                            <h4 class="text-lg bg-gray-500 text-white px-2 rounded">No Current Reservations</h4>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- History -->
            <div id="allResource" class="overflow-x-auto min-w-screen-full bg-custom-color mx-4 p-4 mt-4 rounded-lg shadow-lg sm:text-sm" style="background-color: var(--custom-color);">
                <h1 class="lg:text-3xl sm:text-2xl font-semibold">History</h1>
                <!-- Filter and Search -->
                <div class="mt-2 lg:flex lg:flex-row justify-between font-mont py-4">
                    <!-- Filter Tabs -->
                    <div class="flex lg:flex-row sm:flex-col lg:justify-between mb-3 sm:mb-0 flex-wrap lg:space-x-3 lg:space-y-0 sm:space-y-2">
                        <button id="equipmentBtn" class="equip-filter-tab h-10 bg-gray-100 px-4 py-2 mx-1 rounded-md font-mont active" data-filter="equipment">Equipment and Laboratory</button>
                        <button id="facilityBtn" class="facility-filter-tab h-10 bg-gray-100 px-4 py-2 mx-1 rounded-md font-mont" data-filter="facility">Facility</button>
                    </div>
                    <!-- Search -->
                    <div class="flex sm:justify-center justify-end mb-3 sm:mt-0">
                        <input type="text" id="searchInput" placeholder="Search" class="w-full sm:placeholder-gray-200 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 font-mont text-sm sm:text-base">
                    </div>
                </div>

                <!-- Dropdown Filter by Status -->
                <div class="flex flex-row lg:justify-end md:justify-end sm:justify-center m-0 py-2">
                    <label for="statusFilter" class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5"><i class="fas fa-filter text-gray-500 text-lg"></i> Status: </label>
                    <select id="statusFilter" class="mt-1 flex-row w-18 border-gray-300 rounded-md p-1 font-mont">
                        <option value="" selected>All</option>
                        @foreach($combinedRequestsPaginated->unique('status_state') as $status)
                            @if ($status->status_state !== 'Pending' && $status->status_state !=='Approved')
                            <option value="{{ $status->status_state }}">{{ $status->status_state }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>



                <!-- Start of Tables -->
                <!-- Equipment/Laboratory Table -->
                <div class="bg-white" id="allResource">
                    <div class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont hidden" id="equipmentLabTable">
                        <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll" id="reserved-items-container">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Transaction ID</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Resource Name</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Resource Name</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Pickup Date
                                            <button id="pickupDateFilterEquipment" onclick="toggleSort('equipment', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Pickup Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody id="equipmentTableBody">
                                    @forelse($reserved_items->whereIn('status', [4, 6, 7, 8, 9, 14,15]) as $request)
                                    <tr class="text-sm {{ $request->resource_type }}" data-type="{{ $request->resource_type }}" data-pickup="{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('Y-m-d\TH:i') }}" data-return="{{ \Carbon\Carbon::parse($request->return_datetime)->format('Y-m-d\TH:i') }}">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $request->transaction_id }}</td>
                                        <td class="px-6 py-4 bg-[#fafafa]">{{ $request->resource_name }}</td>
                                        <td class="px-6 py-4 bg-[#fafafa]">{{ $request->resource_type}}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($request->return_datetime)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->return_datetime)->format('h:i A') }}</div>
                                        </td>
                                        <td class="status-cell px-6 py-4 text-left">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $request->status_color }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $request->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $request->status_state ?? 'UNDEFINED' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 no-underline text-center">
                                            <a href="{{ route('overview', ['transaction_id' => $request->transaction_id]) }}" class="text-blue-600 hover:underline">
                                                <i class="fs-5 bi bi-eye-fill"></i>
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
                <!-- Equipment/Lab Pagination -->
                <div class="flex mt-4" id="equipLabPagination">
                    {{ $reserved_items->links() }}
                </div>

                <!-- Facility Table -->
                <div class="bg-white">
                    <div class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont hidden" id="facilityTable">
                        <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll" id="facility-items-container">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Transaction ID</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Facility Name</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Dept. Owner</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Start Date
                                            <button id="pickupDateFilterFacility" onclick="toggleSort('facility', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Return Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody id="facilityTableBody">
                                    @forelse($reserved_facility->whereIn('status', [4, 6, 7, 8, 9, 14]) as $reservation)
                                    <tr class="text-sm" data-start="{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('Y-m-d\TH:i') }}" data-end="{{ \Carbon\Carbon::parse($reservation->end_datetime)->format('Y-m-d\TH:i') }}">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $reservation->id }}</td>
                                        <td class="px-6 py-4 bg-[#fafafa]">{{ $reservation->facility_name }}</td>
                                        <td class="px-6 py-4 bg-[#fafafa]">{{ $reservation->department_owner }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($reservation->end_datetime)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($reservation->end_datetime)->format('h:i A') }}</div>
                                        </td>
                                        <td class="status-cell px-6 py-4 text-left">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $reservation->status_color }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $reservation->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $reservation->status_state ?? 'UNDEFINED' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 no-underline text-center">
                                            <a href="{{ route('facility.overview', ['id' => $reservation->id]) }}" class="text-blue-600 hover:underline">
                                                <i class="fs-5 bi bi-eye-fill"></i>
                                            </a>
                                        </td>
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
                <div class="flex mt-4" id="facilityPagination">
                    {{ $reserved_facility->links() }}
                </div>
                <!-- End of Tables -->
            </div>
        </div>
    </div>
    </div>
</body>

<!-- JQuery AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Search Script -->
<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        const searchText = this.value.trim().toLowerCase();

        fetch("{{ route('my_reservations') }}?search=" + searchText)
            .then(response => response.text())
            .then(html => {
                // Parse the returned HTML and replace the necessary parts
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update the reserved items table
                const newReservedItemsTable = doc.querySelector("#reserved-items-container").innerHTML;
                document.querySelector("#reserved-items-container").innerHTML = newReservedItemsTable;

                // Update the reserved facility table
                const newFacilityTable = doc.querySelector("#facility-items-container").innerHTML;
                document.querySelector("#facility-items-container").innerHTML = newFacilityTable;

                // Update paginations if needed
                const newEquipPagination = doc.querySelector("#equipLabPagination").innerHTML;
                document.querySelector("#equipLabPagination").innerHTML = newEquipPagination;

                const newFacilityPagination = doc.querySelector("#facilityPagination").innerHTML;
                document.querySelector("#facilityPagination").innerHTML = newFacilityPagination;
            });
    });
</script>

<!-- Filter Tabs -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const equipmentBtn = document.getElementById('equipmentBtn');
        const facilityBtn = document.getElementById('facilityBtn');
        const equipmentLabTable = document.getElementById('equipmentLabTable');
        const equipLabPagination = document.getElementById('equipLabPagination');
        const facilityTable = document.getElementById('facilityTable');
        const facilityPagination = document.getElementById('facilityPagination');

        function showEquipment() {
            equipmentLabTable.classList.remove('hidden');
            equipLabPagination.classList.remove('hidden');
            facilityTable.classList.add('hidden');
            facilityPagination.classList.add('hidden');

            // Update button styles
            document.querySelectorAll('.equip-filter-tab').forEach(btn => {
                btn.classList.add('bg-blue-500', 'text-white');
                btn.classList.remove('bg-gray-100', 'text-black');
            });
            document.querySelectorAll('.facility-filter-tab').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-100', 'text-black');
            });
        }

        function showFacility() {
            equipmentLabTable.classList.add('hidden');
            equipLabPagination.classList.add('hidden');
            facilityTable.classList.remove('hidden');
            facilityPagination.classList.remove('hidden');

            // Update button styles
            document.querySelectorAll('.equip-filter-tab').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-100', 'text-black');
            });
            document.querySelectorAll('.facility-filter-tab').forEach(btn => {
                btn.classList.add('bg-blue-500', 'text-white');
                btn.classList.remove('bg-gray-100', 'text-black');
            });
        }

        equipmentBtn.addEventListener('click', showEquipment);
        facilityBtn.addEventListener('click', showFacility);

        // Initial state
        showEquipment();
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle pagination clicks for Reserved Items (Equipment/Laboratory)
        $(document).on('click', '#reserved-items-pagination a', function(event) {
            event.preventDefault(); // Prevent default link behavior (page reload)

            var url = $(this).attr('href'); // Get the URL from the pagination link

            if (url) { // Check if URL is not null
                // Make AJAX request to fetch the new page of reserved items
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json', // Ensure we expect JSON response
                    success: function(response) {
                        // Update the Reserved Items HTML and Pagination
                        $('#reserved-items-container').html(response.reserved_items_html);
                        $('#reserved-items-pagination').html(response.reserved_items_pagination);
                    },
                    error: function(xhr) {
                        console.error("Error fetching reserved items:", xhr.responseText);
                    }
                });
            }
        });

        // Handle pagination clicks for Facility Reservations
        $(document).on('click', '#facility-pagination a', function(event) {
            event.preventDefault(); // Prevent default link behavior (page reload)

            var url = $(this).attr('href'); // Get the URL from the pagination link

            if (url) { // Check if URL is not null
                // Make AJAX request to fetch the new page of facility reservations
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json', // Ensure we expect JSON response
                    success: function(response) {
                        // Update the Facility Reservations HTML and Pagination
                        $('#facility-items-container').html(response.facility_html);
                        $('#facility-pagination').html(response.facility_pagination);
                    },
                    error: function(xhr) {
                        console.error("Error fetching facility reservations:", xhr.responseText);
                    }
                });
            }
        });
    });
</script>

<!-- Start of Tables -->
<div class="bg-white" id="allResource">
    <!-- Equipment/Laboratory Table -->
    <div class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont hidden" id="equipmentLabTable">
        <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll" id="reserved-items-container">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-nowrap">Transaction ID</th>
                        <th scope="col" class="px-6 py-3 text-nowrap">Resource Name</th>
                        <th scope="col" class="px-6 py-3 text-nowrap">Resource Type</th>
                        <th scope="col" class="px-6 py-3 text-nowrap">
                            PICKUP DATE
                            <button id="pickupDateFilterEquipment" onclick="toggleSort('equipment', 'pickup')" class="ml-1">
                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-nowrap">RETURN DATE</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3">Details</th>
                    </tr>
                </thead>
                <tbody id="equipmentTableBody">
                    @forelse($reserved_items->whereIn('status', [4, 6, 8, 9, 14]) as $request)
                    <tr class="text-sm {{ $request->resource_type }}" data-type="{{ $request->resource_type }}" data-pickup="{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('Y-m-d\TH:i') }}" data-return="{{ \Carbon\Carbon::parse($request->return_datetime)->format('Y-m-d\TH:i') }}">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $request->transaction_id }}</td>
                        <td class="px-6 py-4 bg-[#fafafa]">{{ $request->resource_name }}</td>
                        <td class="px-6 py-4 bg-[#fafafa]">{{ $request->resource_type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ \Carbon\Carbon::parse($request->return_datetime)->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->return_datetime)->format('h:i A') }}</div>
                        </td>
                        <td class="status-cell px-6 py-4 text-left">
                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $request->status_color }}">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $request->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ $request->status_state ?? 'UNDEFINED' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 no-underline text-center">
                            <a href="{{ route('overview', ['transaction_id' => $request->transaction_id]) }}" class="text-blue-600 hover:underline">
                                <i class="fs-5 bi bi-eye-fill"></i>
                            </a>
                        </td>
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

    <!-- Equipment/Lab Pagination -->
    <div class="flex mt-4" id="equipLabPagination">
        {{ $reserved_items->links() }}
    </div>

    <!-- Facility Table -->
    <div class="bg-white">
        <div class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont hidden" id="facilityTable">
            <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll" id="facility-items-container">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-nowrap">Transaction ID</th>
                            <th scope="col" class="px-6 py-3 text-nowrap">Facility Name</th>
                            <th scope="col" class="px-6 py-3 text-nowrap">Dept. Owner</th>
                            <th scope="col" class="px-6 py-3 text-nowrap">
                                Start Date
                                <button id="pickupDateFilterFacility" onclick="toggleSort('facility', 'start')" class="ml-1">
                                    <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-nowrap">Return Date</th>
                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                            <th scope="col" class="px-6 py-3">Details</th>
                        </tr>
                    </thead>
                    <tbody id="facilityTableBody">
                        @forelse($reserved_facility->whereIn('status', [4, 6, 8, 9, 14]) as $reservation)
                        <tr class="text-sm" data-start="{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('Y-m-d\TH:i') }}" data-end="{{ \Carbon\Carbon::parse($reservation->end_datetime)->format('Y-m-d\TH:i') }}">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $reservation->id }}</td>
                            <td class="px-6 py-4 bg-[#fafafa]">{{ $reservation->facility_name }}</td>
                            <td class="px-6 py-4 bg-[#fafafa]">{{ $reservation->department_owner }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('M j, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ \Carbon\Carbon::parse($reservation->end_datetime)->format('M j, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($reservation->end_datetime)->format('h:i A') }}</div>
                            </td>
                            <td class="status-cell px-6 py-4 text-left">
                                <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $reservation->status_color }}">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $reservation->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    {{ $reservation->status_state ?? 'UNDEFINED' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 no-underline text-center">
                                <a href="{{ route('facility.overview', ['id' => $reservation->id]) }}" class="text-blue-600 hover:underline">
                                    <i class="fs-5 bi bi-eye-fill"></i>
                                </a>
                            </td>
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

        <!-- Facility Pagination -->
        <div class="flex mt-4" id="facilityPagination">
            {{ $reserved_facility->links() }}
        </div>
    </div>
</div>
<!-- End of Tables -->

<!-- JavaScript for Sorting Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const equipmentBtn = document.getElementById('equipmentBtn');
        const facilityBtn = document.getElementById('facilityBtn');
        const statusFilter = document.getElementById('statusFilter');

        // Function to switch active tab and show relevant table
        function switchTab(isFacility) {
            if (isFacility) {
                facilityBtn.classList.add('active');
                equipmentBtn.classList.remove('active');
                document.getElementById('facilityTableBody').style.display = '';
                document.getElementById('equipmentTableBody').style.display = 'none';
            } else {
                equipmentBtn.classList.add('active');
                facilityBtn.classList.remove('active');
                document.getElementById('equipmentTableBody').style.display = '';
                document.getElementById('facilityTableBody').style.display = 'none';
            }
            // Trigger the filter after switching tabs
            statusFilter.dispatchEvent(new Event('change'));
        }

        // Event listeners for tab buttons
        equipmentBtn.addEventListener('click', () => switchTab(false));
        facilityBtn.addEventListener('click', () => switchTab(true));

        // Event listener for the status filter dropdown
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value.trim();
            const isFacility = facilityBtn.classList.contains('active');
            const tableBodyId = isFacility ? 'facilityTableBody' : 'equipmentTableBody';
            const rows = document.querySelectorAll(`#${tableBodyId} tr`);

            let foundMatch = false;

            rows.forEach(row => {
                const statusCell = row.querySelector('.status-cell .status-label');
                const statusText = statusCell ? statusCell.textContent.trim() : '';

                // Show or hide row based on selected status
                if (selectedStatus === "" || statusText === selectedStatus) {
                    row.style.display = ''; // Show the row
                    foundMatch = true;
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });

            // Show "No records found" message if no rows match the selected status
            const noRecordsRow = document.querySelector(`#${tableBodyId} .no-records`);
            if (noRecordsRow) {
                noRecordsRow.style.display = foundMatch ? 'none' : ''; // Show or hide "No records found" row
            }
        });

        // Initial display of "All" filter
        statusFilter.dispatchEvent(new Event('change'));
    });




    function toggleSort(tab, column) {
        const tableBodyId = tab === 'equipment' ? 'tableBody' : 'facilityTableBody'; // Use the correct table body ID
        const tableBody = document.getElementById(tableBodyId);
        const rows = Array.from(tableBody.rows);
        const headerButtonId = column === 'pickup' ? 'pickupDateFilterEquipment' : 'pickupDateFilterFacility';
        const button = document.getElementById(headerButtonId);

        // Determine the current sorting order
        let isAscending = button.classList.contains('rotate-180');
        button.classList.toggle('rotate-180', !isAscending); // Toggle button rotation

        // Sort rows based on the specified column
        rows.sort((rowA, rowB) => {
            const cellA = column === 'pickup' ? rowA.cells[3].textContent.trim() : rowA.cells[4].textContent.trim();
            const cellB = column === 'pickup' ? rowB.cells[3].textContent.trim() : rowB.cells[4].textContent.trim();

            // Convert the cell content to Date objects
            const dateA = new Date(cellA);
            const dateB = new Date(cellB);

            // Return the comparison based on the order
            return isAscending ? dateA - dateB : dateB - dateA; // Sort in ascending or descending order
        });

        // Append sorted rows back to the table body
        rows.forEach(row => tableBody.appendChild(row));
    }

    // Add event listeners for your sorting buttons
    document.getElementById('pickupDateFilterEquipment').addEventListener('click', () => toggleSort('equipment', 'start'));
    document.getElementById('pickupDateFilterFacility').addEventListener('click', () => toggleSort('facility', 'start'));
</script>


</html>