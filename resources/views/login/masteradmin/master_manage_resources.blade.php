<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resources {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Sweet Alert CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.masteradmin.master_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full min-w-screen-lg my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-xl font-bold">Department Resource</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <div class="bg-custom-color mx-4 mt-4 p-4 rounded-lg shadow-lg" style="background-color: var(--custom-color);">

                <!-- dropdown -->
                <div class="lg:flex lg:flex-row justify-between font-mont p-2">
                    <div class="flex flex-col sm:flex-row justify-between space-x-4 items-center w-full mt-2">
                        <div class="flex flex-col relative group dropdown">
                            <button class="my-3 text-4xl w-12 h-12 rounded-lg bg-green-500 text-white hover:bg-green-600 focus:outline-none dropdown-button">+</button>
                            <div class="dropdown-menu ml-3 hidden w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('ma.add.resources') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline" style="text-decoration: none;">Add Resource</a>
                                    <a href="{{ route('ma.add.facility') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline" style="text-decoration: none;">Add Facility</a>
                                </div>
                            </div>
                        </div>

                        <!-- Info Icon and Search -->
                        <div class="flex sm:justify-center justify-end sm:mt-0 items-center space-x-2">
                            <i class="bi bi-info-circle text-gray-500 text-xl cursor-pointer" id="infoIcon"></i>
                            <input type="text" id="searchInput" placeholder="Search" class="w-full h-12 sm:placeholder-gray-200 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 font-mont text-sm sm:text-base">
                        </div>

                        <!-- Modal -->
                        <div id="infoModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                            <!-- Black overlay -->
                            <div class="fixed inset-0 bg-black opacity-50"></div>
                            <!-- Modal box -->
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="relative bg-white w-full max-w-lg p-6 rounded-lg shadow-xl">
                                    <!-- Modal header -->
                                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                                        <h3 class="text-2xl font-bold text-gray-800">Resources Details</h3>
                                        <button class="text-gray-600 hover:text-gray-900 text-2xl font-semibold focus:outline-none" id="closeModal">&times;</button>
                                    </div>
                                    <!-- Modal content -->
                                    <div class="space-y-6">
                                        <!-- Total Resources Table -->
                                        <div>
                                            <p class="text-lg font-semibold text-gray-700 mb-2">Available Resources</p>
                                            <table class="min-w-full table-auto border-collapse">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-gray-600">Type</th>
                                                        <th class="px-4 py-2 text-right text-gray-600">Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Equipment</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $currentEquipmentCount }} / {{ $resources->where('type', 'Equipment')->count() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Laboratories</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $currentLabsCount }} / {{ $resources->where('type', 'Laboratory')->count() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Facilities</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $currentFacilitiesCount }} / {{ $facilities->count() }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Currently Borrowed/Used Table -->
                                        <div>
                                            <p class="text-lg font-semibold text-gray-700 mb-2">Currently Borrowed/Used</p>
                                            <table class="min-w-full table-auto border-collapse">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-gray-600">Type</th>
                                                        <th class="px-4 py-2 text-right text-gray-600">Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Equipment</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $borrowedEquipmentCount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Laboratories</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $borrowedLabsCount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Facilities</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $borrowedFacilitiesCount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Items for Replacement & Total Available Table -->
                                        <div>
                                            <table class="min-w-full table-auto border-collapse">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-gray-600">Description</th>
                                                        <th class="px-4 py-2 text-right text-gray-600">Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Items for Replacement</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $itemsForReplacement }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Total Items Available</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $totalAvailableItems }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-t px-4 py-2 text-gray-600">Total Items Reserved</td>
                                                        <td class="border-t px-4 py-2 text-right">{{ $totalItemsReserved }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs and Search -->
                <div class="font-mont">
                    <!-- Tabs -->
                    <nav class="flex flex-nowrap justify-center lg:justify-start sm:justify-start mb-4 overflow-x-auto">
                        <span class="tab-link flex-shrink-0 sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('equipment')">
                            Equipment
                        </span>
                        <span class="tab-link flex-shrink-0 sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('laboratory')">
                            Laboratory
                        </span>
                        <span class="tab-link flex-shrink-0 sm:w-auto text-center p-2 font-semibold text-gray-700 no-underline focus:outline-none border-b-2 border-transparent cursor-pointer relative lg:text-lg sm:text-sm" onclick="showTab('facility')">
                            Facility
                        </span>
                    </nav>
                </div>

                <!-- Dropdown Filter by Status -->
                <div
                    class="flex flex-row lg:justify-end md:justify-end sm:justify-center m-0 py-2 px-2">
                    <label
                        for="statusFilter"
                        class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5"><i class="fas fa-filter text-gray-500 text-lg"></i> Status:
                    </label>
                    <select
                        id="statusFilter"
                        class="mt-1 flex-row w-18 border-gray-300 rounded-md p-1 font-mont">
                        <option value="" selected>All</option>
                        @foreach($combinedRequests->unique('status_state') as $status)
                        <option value="{{ $status->status_state }}">
                            {{ $status->status_state }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Tab Content -->
                <div>
                    <!-- Equipment Tab -->
                    <div id="equipment" class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont" style="background-color: var(--custom-color);">
                        <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll">
                            <table id="equipmentTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Resource ID</th>
                                        <th scope="col" class="px-6 py-3">Serial Number</th>
                                        <th scope="col" class="px-6 py-3">Resource Name</th>
                                        <th scope="col" class="px-6 py-3">Resource Type</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Date Created
                                            <button id="pickupDateFilterEquipment" onclick="toggleSort('equipment', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Rating</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resources as $item)
                                    @if ($item->type == 'Equipment')
                                    <tr class="bg-white border-b hover:bg-gray-100">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $item->resource_id }}</td>
                                        <td class="px-6 py-4">{{ $item->serial_number }}</td>
                                        <td class="px-6 py-4 bg-[#fafafa]">{{ $item->resource_name }}</td>
                                        <td class="px-6 py-4">{{ $item->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($item->created_at)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4">{{ $item->rating }}</td>
                                        <td class="status-cell px-6 py-4 text-left">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $item->status_color }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $item->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $item->status_state  ?? 'UNDEFINED' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 no-underline text-center">
                                            <a href="{{ route('ma.view.resources', ['resource_id' => $item->resource_id]) }}" class="text-blue-600 hover:underline">
                                                <i class="fs-5 bi bi-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr class="no-records" style="display: none">
                                        <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Equipment/Lab Pagination -->
                        <div class="flex mt-4" id="equipLabPagination">
                            {{ $paginated_resources->links() }}
                        </div>
                    </div>

                    <!-- Laboratory Tab -->
                    <div id="laboratory" class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont" style="background-color: var(--custom-color); display: none;">
                        <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll">
                            <table id="laboratoryTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Resource ID</th>
                                        <th scope="col" class="px-6 py-3">Serial Number</th>
                                        <th scope="col" class="px-6 py-3">Resource Name</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Resource Type</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Date Created
                                            <button id="pickupDateFilterLaboratory" onclick="toggleSort('laboratory', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3">Rating</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resources as $item)
                                    @if($item->type == 'Laboratory')
                                    <tr class="bg-white border-b hover:bg-gray-100">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $item->resource_id }}</td>
                                        <td class="px-6 py-4">{{ $item->serial_number }}</td>
                                        <td class="px-6 py-4 bg-[#fafafa]">{{ $item->resource_name }}</td>
                                        <td class="px-6 py-4">{{ $item->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($item->created_at)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4">{{ $item->rating }}</td>
                                        <td class="status-cell px-6 py-4 text-left">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $item->status_color }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $item->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $item->status_state ?? 'UNDEFINED' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 no-underline text-center">
                                            <a href="{{ route('ma.view.resources', ['resource_id' => $item->resource_id]) }}" class="text-blue-600 hover:underline">
                                                <i class="fs-5 bi bi-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr class="no-records" style="display: none">
                                        <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Equipment/Lab Pagination -->
                        <div class="flex mt-4" id="equipLabPagination">
                            {{ $paginated_resources->links() }}
                        </div>
                    </div>

                    <!-- Facility Tab -->
                    <div id="facility" class="lg:max-h-[26rem] sm:max-h-screen tab-pane mb-2 font-mont" style="background-color: var(--custom-color); display: none;">
                        <div class="lg:max-h-[26rem] sm:max-h-screen overflow-x-auto overflow-y-scroll">
                            <table id="facilityTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 top-0 sticky">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Facility ID</th>
                                        <th scope="col" class="px-6 py-3">Facility Name</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">Availability</th>
                                        <th scope="col" class="px-6 py-3">Policy</th>
                                        <th scope="col" class="px-6 py-3 text-nowrap">
                                            Created Date
                                            <button id="pickupDateFilterFacility" onclick="toggleSort('facility', 'pickup')" class="ml-1">
                                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($facilities as $facility)
                                    <tr class="bg-white border-b hover:bg-gray-100">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{$facility->facilities_id }}</td>
                                        <td class="px-6 py-4 bg-[#fafafa]">{{$facility->facility_name }}</td>
                                        <td class="px-6 py-4">{{ $facility->is_available ? 'Available' : 'Not Available' }}</td>
                                        <td class="px-6 py-4">{{$facility->policy ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($facility->facility_created_at)->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse ($facility->facility_created_at)->format('h:i A') }}</div>
                                        </td>
                                        <td class="status-cell px-6 py-4 text-left">
                                            <span class="status-label w-full inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 {{ $facility->status_color }}">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $facility->status_color }}" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ $facility->status_state ?? 'UNDEFINED' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 no-underline text-center">
                                            <a href="{{route('ma.view.facility',['facilities_id' => $facility->facilities_id])}}" class="text-blue-600 hover:underline">
                                                <i class="fs-5 bi bi-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="no-records" style="display: none">
                                        <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Facility Pagination -->
                        <div class="flex mt-4" id="equipLabPagination">
                            {{ $paginated_facility->links() }}
                        </div>
                    </div>
                </div>

                <!-- Download -->
                <form action="{{route('ma.download.resources')}}" method="POST">
                    @csrf
                    <button class="btn btn-primary">Download Resources</button>
                </form>
            </div>

            <!-- SWAL Success -->
            @if(Session::has('success'))
            <script>
                swal("Updated", "{{Session::get('success')}}", 'info', {
                    button: true,
                    button: "OK"
                })
            </script>
            @endif

            <!-- SWAL Success -->
            @if(Session::has('added'))
            <script>
                swal("Success", "{{Session::get('added')}}", 'success', {
                    button: true,
                    button: "OK"
                })
            </script>
            @endif

            <!-- SWAL Error -->
            @if(Session::has('error'))
            <script>
                swal("Invalid", "{{Session::get('error')}}", 'error', {
                    button: true,
                    button: "OK"
                })
            </script>
            @endif

            <script>
                $(document).ready(function() {
                    $('.dropdown-button').on('click', function() {
                        $(this).next('.dropdown-menu').toggle();
                    });

                    $(document).on('click', function(event) {
                        if (!$(event.target).closest('.dropdown').length) {
                            $('.dropdown-menu').hide();
                        }
                    });
                });

                document.addEventListener('DOMContentLoaded', () => {
                    showTab('{{ $activeTab }}');
                });

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

                document.addEventListener('DOMContentLoaded', () => showTab('equipment'));
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

            <!-- Modal Script -->
            <script>
                // Get elements
                const infoIcon = document.getElementById('infoIcon');
                const infoModal = document.getElementById('infoModal');
                const closeModal = document.getElementById('closeModal');

                // Show modal when icon is clicked
                infoIcon.addEventListener('click', function() {
                    infoModal.classList.remove('hidden');
                });

                // Close modal when close button is clicked
                closeModal.addEventListener('click', function() {
                    infoModal.classList.add('hidden');
                });

                // Close modal when clicking outside modal content
                window.addEventListener('click', function(e) {
                    if (e.target === infoModal) {
                        infoModal.classList.add('hidden');
                    }
                });
            </script>

<script>
                function toggleSort(tab, column) {
                    const tableId = `${tab}Table`;
                    const table = document.getElementById(tableId);
                    const rows = Array.from(table.rows).slice(1); // Assuming first row is the header
                    const headerButton = `${column}DateFilter${
              tab.charAt(0).toUpperCase() + tab.slice(1)
            }`;
                    const button = document.getElementById(headerButton);

                    let isAscending = button.classList.contains("rotate-180");
                    button.classList.toggle("rotate-180", !isAscending);

                    rows.sort((rowA, rowB) => {
                        const cellIndex = column === "pickup" ? 4 : 4;
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

                    rows.forEach((row) => table.tBodies[0].appendChild(row)); // Reinsert rows in sorted order
                }
            </script>

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
                    showTab("equipment")
                ); // Load "All" tab by default
            </script>

        </div>
    </div>
</body>

</html>