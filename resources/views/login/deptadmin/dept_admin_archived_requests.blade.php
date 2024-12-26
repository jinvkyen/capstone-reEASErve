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
        @include('login.deptadmin.dept_admin_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Completed Requests</h1>
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
                        <span class="tab-link w-full sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('equipment')">
                            Equipment
                        </span>
                        <span class="tab-link w-full sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('laboratory')">
                            Laboratory
                        </span>
                        <span class="tab-link w-full sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('facility')">
                            Facility
                        </span>
                    </nav>

                    <!-- Search -->
                    <div class="flex sm:justify-center justify-end mb-3 sm:mt-0">
                        <input type="text" id="searchInput" placeholder="Search" class="w-full sm:placeholder-gray-200 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 font-mont text-sm sm:text-base">
                    </div>
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
                                            Date Requested
                                            <button id="pickupDateFilterAll" onclick="toggleSort('all', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Date Created</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- All Request -->
                                    @forelse($all_requests as $request)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $request->transaction_id }}</td>
                                        <td class="px-6 py-4 text-gray-700"> {{ $request->first_name }} {{ $request->last_name }} <br>
                                            <small class="text-gray-400">{{ $request->position }}</small>
                                        </td>
                                        <td class="px-6 py-2 bg-[#fafafa]">{{ $request->resource_name }}</td>
                                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500 flex justify-center">
                                            <div>
                                                <span class="font-bold"> Pickup Date: </span> <br>
                                                <div>{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('M j, Y') }}</div>
                                                <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('h:i A') }}</div>
                                                <span class="font-bold"> Return Date: </span> <br>
                                                <div>{{ \Carbon\Carbon::parse($request->return_datetime)->format('M j, Y') }}</div>
                                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->return_datetime)->format('h:i A') }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->created_at)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-2 text-left">
                                            <span class="w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $request->status_color }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $request->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $request->status_state ?? 'UNDEFINED' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 no-underline text-center">
                                            <a href="{{ route('admin.overview', ['transaction_id' => $request->transaction_id]) }}" class="text-blue-600 hover:underline">
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

                    <!-- Tab Content -->
                    <div>
                        <!-- Equipment Tab -->
                        <div id="equipment" class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont" style="background-color: var(--custom-color);">
                            <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll">
                                <table id="equipmentTable" class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Transaction ID</th>
                                            <th scope="col" class="px-6 py-3">User</th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">Resource Name</th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">
                                                Date Requested
                                                <button id="pickupDateFilterEquipment" onclick="toggleSort('equipment', 'pickup')" class="ml-1">
                                                    <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">Date Created</th>
                                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                                            <th scope="col" class="px-6 py-3">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($admin_equipment as $request)
                                        <tr class="bg-white border-b hover:bg-gray-100">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $request->transaction_id }}</td>
                                            <td class="px-6 py-4 text-gray-700"> {{ $request->first_name }} {{ $request->last_name }} <br>
                                                <small class="text-gray-400">{{ $request->position }}</small>
                                            </td>
                                            <td class="px-6 py-4 bg-[#fafafa]">{{ $request->resource_name }}</td>
                                            <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500 flex justify-center">
                                                <div>
                                                    <span class="font-bold"> Pickup Date: </span> <br>
                                                    <div>{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('M j, Y') }}</div>
                                                    <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('h:i A') }}</div>
                                                    <span class="font-bold"> Return Date: </span> <br>
                                                    <div>{{ \Carbon\Carbon::parse($request->return_datetime)->format('M j, Y') }}</div>
                                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->return_datetime)->format('h:i A') }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y') }}</div>
                                                <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->created_at)->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                <span class="w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $request->status_color }}">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $request->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    {{ $request->status_state ?? 'UNDEFINED' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 no-underline text-center">
                                                <a href="{{ route('admin.overview', ['transaction_id' => $request->transaction_id]) }}" class="text-blue-600 hover:underline">
                                                    <i class="fs-5 bi bi-eye-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Laboratory Tab -->
                        <div id="laboratory" class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont" style="background-color: var(--custom-color); display: none;">
                            <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll">
                                <table id="laboratoryTable" class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Transaction ID</th>
                                            <th scope="col" class="px-6 py-3">User</th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">Resource Name</th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">
                                                Date Requested
                                                <button id="pickupDateFilterLaboratory" onclick="toggleSort('laboratory', 'pickup')" class="ml-1">
                                                    <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">Date Created</th>
                                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                                            <th scope="col" class="px-6 py-3">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($admin_laboratory as $request)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $request->transaction_id }}</td>
                                            <td class="px-6 py-4 text-gray-700"> {{ $request->first_name }} {{ $request->last_name }} <br>
                                                <small class="text-gray-400">{{ $request->position }}</small>
                                            </td>
                                            <td class="px-6 py-4 bg-[#fafafa]">{{ $request->resource_name }}</td>
                                            <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500 flex justify-center">
                                                <div>
                                                    <span class="font-bold"> Pickup Date: </span> <br>
                                                    <div>{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('M j, Y') }}</div>
                                                    <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->pickup_datetime)->format('h:i A') }}</div>
                                                    <span class="font-bold"> Return Date: </span> <br>
                                                    <div>{{ \Carbon\Carbon::parse($request->return_datetime)->format('M j, Y') }}</div>
                                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->return_datetime)->format('h:i A') }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y') }}</div>
                                                <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->created_at)->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                <span class="w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $request->status_color }}">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $request->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    {{ $request->status_state ?? 'UNDEFINED' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 no-underline text-center">
                                                <a href="{{ route('admin.overview', ['transaction_id' => $request->transaction_id]) }}" class="text-blue-600 hover:underline">
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

                        <!-- Facility Tab -->
                        <div id="facility" class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont" style="background-color: var(--custom-color); display: none;">
                            <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll">
                                <table id="facilityTable" class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Transaction ID</th>
                                            <th scope="col" class="px-6 py-3">User</th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">Facility Name</th>
                                            <th scope="col" class="px-6 py-3 text-nowrap">
                                                Date Requested
                                                <button id="pickupDateFilterFacility" onclick="toggleSort('facility', 'pickup')" class="ml-1">
                                                    <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </th>
                                            <th scope="col" class="px-6 py-3">Date Created</th>
                                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                                            <th scope="col" class="px-6 py-3">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($admin_facilities as $request)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $request->id }}</td>
                                            <td class="px-6 py-4 text-gray-700"> {{ $request->first_name }} {{ $request->last_name }} <br>
                                                <small class="text-gray-400">{{ $request->position }}</small>
                                            </td>
                                            <td class="px-6 py-4 text-nowrap bg-[#fafafa]">{{ $request->facility_name }}</td>
                                            <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500 flex justify-center">
                                                <div>
                                                    <span class="font-bold"> Start Date: </span> <br>
                                                    <div>{{ \Carbon\Carbon::parse($request->start_datetime)->format('M j, Y') }}</div>
                                                    <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->start_datetime)->format('h:i A') }}</div>
                                                    <span class="font-bold"> End Date: </span> <br>
                                                    <div>{{ \Carbon\Carbon::parse($request->end_datetime)->format('M j, Y') }}</div>
                                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->end_datetime)->format('h:i A') }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y') }}</div>
                                                <div class="text-xs text-gray-400 mb-2">{{ \Carbon\Carbon::parse($request->created_at)->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                <span class="w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $request->status_color }}">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $request->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    {{ $request->status_state ?? 'UNDEFINED' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center no-underline">
                                                <a href="{{ route('admin.facility.overview', ['id' => $request->id]) }}" class="text-blue-600 hover:underline">
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

            function toggleSort(tab, column) {
                const tableId = `${tab}Table`;
                const table = document.getElementById(tableId);
                const rows = Array.from(table.rows).slice(1);
                const headerButton = `${column}DateFilter${tab.charAt(0).toUpperCase() + tab.slice(1)}`;
                const button = document.getElementById(headerButton);

                let isAscending = button.classList.contains('rotate-180');
                button.classList.toggle('rotate-180', !isAscending);

                rows.sort((rowA, rowB) => {
                    const cellA = rowA.cells[column === 'pickup' ? 3 : 4].textContent.trim();
                    const cellB = rowB.cells[column === 'pickup' ? 3 : 4].textContent.trim();
                    const dateA = new Date(cellA);
                    const dateB = new Date(cellB);

                    return isAscending ? dateA - dateB : dateB - dateA;
                });

                rows.forEach(row => table.tBodies[0].appendChild(row));
            }
        </script>

        <!-- Search -->
        <script>
            const searchInput = document.getElementById("searchInput");
            searchInput.addEventListener("keyup", function() {
                const searchText = this.value.trim().toLowerCase();
                const rows = document.querySelectorAll("tbody tr");
                rows.forEach(row => {
                    let found = false;
                    row.querySelectorAll("td").forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchText)) {
                            found = true;
                        }
                    });
                    if (found) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        </script>
</body>

</html>