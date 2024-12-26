<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedbacks</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS and jQuery (for modal functionality) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.masteradmin.master_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full min-w-screen-lg my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-xl font-bold">Archived Feedbacks</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">

                </div>
            </div>

            <div class="bg-custom-color mx-4 mt-4 p-4 rounded-lg shadow-lg" style="background-color: var(--custom-color);">
                <!-- Top Section -->
                <div class="flex flex-col lg:flex-row justify-between font-mont space-y-4 mb-2 lg:space-y-0">
                    <!-- Search and Button Container -->
                    <div class="flex flex-col lg:flex-row lg:space-x-4 items-center w-full">
                        <!-- Search -->
                        <div class="flex-grow">
                            <input type="text" id="searchInput" placeholder="Search" class="font-mont px-2 py-2 w-full md:w-72 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Dropdown Filter by Status -->
                <div class="flex flex-row lg:justify-end md:justify-end sm:justify-center m-0 py-2">
                    <label for="statusFilter" class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5">
                        <i class="fas fa-filter text-gray-400"></i>
                    </label>
                    <select id="statusFilter" class="leading-tight mt-1 flex-row w-18 border-gray-300 rounded-md p-1 font-mont overflow-y-auto">
                        <option value="" selected>All</option>
                        @foreach($feedback_archived->unique('resource_name') as $status)
                        <option value="{{ $status->resource_name }}">
                            {{ $status->resource_name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- Audit Table -->
                <div id="auditTableContainer" class="overflow-x-auto">
                    <table id="auditTable" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 text-center">
                            <tr>
                                <th scope="col" class="px-2 py-2 text-center">ID</th>
                                <th scope="col" class="px-4 py-2 text-left">Username</th>
                                <th scope="col" class="px-4 py-2 text-left">Resource Name</th>
                                <th scope="col" class="px-4 py-2 text-center">Rating</th>
                                <th scope="col" class="px-4 py-2 text-left">Feedback</th>
                                <th scope="col" class="px-6 py-3 text-nowrap">
                                    Date and Time
                                    <button id="pickupDateFilterLaboratory" onclick="toggleSort('laboratory', 'pickup')" class="ml-1">
                                        <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedback_archived as $entry)
                            <tr class="bg-white border-b hover:bg-gray-100 text-sm">
                                <td class="text-center px-2 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $entry->feedback_id }}</td>
                                <td class="text-left px-4 py-2">
                                    <div>{{ $entry->last_name }} {{ $entry->first_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $entry->username }}</div>
                                </td>
                                <td class="status-cell text-left px-4 py-2">
                                    <div class="status-label">{{ $entry->resource_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $entry->resource_id }}</div>
                                </td>
                                <td class="text-center px-4 py-2">{{ $entry->rating }}</td>
                                <td class="text-left px-4 py-2 whitespace-nowrap text-sm text-gray-500 group relative">
                                    <div class="truncate max-w-xs">
                                        {{ $entry->feedback }}
                                    </div>
                                    <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-y-auto z-10">
                                        {{ $entry->feedback }}
                                    </div>
                                </td>
                                <td class="text-center px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ \Carbon\Carbon::parse($entry->created_at)->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($entry->created_at)->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-2 no-underline text-center flex justify-center items-center align-middle">
                                    <div class="relative group">
                                        <button type="button" class="restoreModalTrigger flex items-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 transition"
                                            data-id="{{ $entry->feedback_id }}">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                        <div class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-white text-xs mb-2 rounded px-2 py-2 whitespace-nowrap shadow-lg pointer-events-none">
                                            Restore Feedback
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                    No records found.
                                </td>
                            </tr>
                            @endforelse
                            <tr class="no-records" style="display: none">
                                <td colspan="7" class="text-center text-gray-500 font-medium px-6 py-4">
                                    No records found.
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <!-- Pagination Links -->
                    <div class="flex justify-between mt-4">
                        <div class="flex space-x-2 text-center align-middle">
                            {{ $feedback_archived->appends(['search' => $search])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
<!-- Tab Filters -->
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
            const cellIndex = column === "pickup" ? 5 : 5;
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

<!-- Search Script -->
<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
    const searchText = this.value.trim().toLowerCase();
    const route = "{{ route('ma.feedbacks.archived') }}";

    fetch(route + "?search=" + encodeURIComponent(searchText)) // Ensure the text is encoded
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableContent = doc.querySelector("#auditTable tbody").innerHTML; // Only get the tbody

            // Update the table body with new content
            document.querySelector("#auditTable tbody").innerHTML = newTableContent;

            // Handle visibility of "No records found" message
            const noRecordsRow = document.querySelector('.no-records');
            if (newTableContent.trim() === '') {
                noRecordsRow.style.display = ''; // Show "No records found"
            } else {
                noRecordsRow.style.display = 'none'; // Hide if there are records
            }
        })
        .catch(error => console.error('Error fetching data:', error));
});

</script>

<!-- Toggle Sort -->
<script>
    function toggleSort(tab, column) {
        // Match table and button IDs based on the tab value
        const tableId = tab === 'archivedAudit' ? 'archivedAuditTable' : 'auditTable';
        const buttonId = column === 'pickup' ? 'pickupDateFilterLaboratory' : 'datetimeDateFilterAudit';

        const table = document.getElementById(tableId);
        const button = document.getElementById(buttonId);

        if (!table || !button) return; // Safety check if elements are not found

        const rows = Array.from(table.rows).slice(1); // Exclude header row
        let isAscending = button.classList.contains("rotate-180");
        button.classList.toggle("rotate-180", !isAscending);

        // Use column index 5 for both date columns
        const cellIndex = 5;

        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[cellIndex]?.textContent.trim();
            const cellB = rowB.cells[cellIndex]?.textContent.trim();

            const dateA = new Date(cellA);
            const dateB = new Date(cellB);

            if (isNaN(dateA) || isNaN(dateB)) return 0; // Skip rows if dates are invalid

            return isAscending ? dateA - dateB : dateB - dateA;
        });

        // Append sorted rows back to the table body
        rows.forEach(row => table.tBodies[0].appendChild(row));
    }
</script>

<!-- Dropdown filter script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');

        // Check if the filter dropdown exists
        if (!statusFilter) {
            console.error('Status filter dropdown not found');
            return;
        }

        // Add event listener for the status filter dropdown
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value; // Get selected status value

            // Get all rows in the table
            const rows = document.querySelectorAll('#auditTable tbody tr'); // Select all rows in the table body

            let foundMatch = false; // Track if there is at least one matching row

            rows.forEach(row => {
                const statusCell = row.querySelector('.status-cell'); // Target the cell with status text
                const statusText = statusCell ? statusCell.querySelector('.status-label').textContent.trim() : ''; // Get the text inside the status label

                // Debugging output
                console.log('Selected Status:', selectedStatus);
                console.log('Row Status:', statusText);

                // Show or hide row based on selected status
                if (selectedStatus === "" || selectedStatus === statusText) { // "" is for 'All'
                    row.style.display = ''; // Show the row
                    foundMatch = true; // Found at least one matching row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });

            // Show "No records found" message if no rows match the selected status
            const noRecordsRow = document.querySelector('.no-records'); // Select the no-records row
            if (noRecordsRow) {
                noRecordsRow.style.display = foundMatch ? 'none' : ''; // Show or hide "No records found" row
            }
        });
    });
</script>




<!-- Restore Feedback Record -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle delete button click
        $('.restoreModalTrigger').on('click', function() {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route("ma.feedback.restore", ["id" => "__id__"]) }}'.replace('__id__', id),
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $(this).closest('tr').remove();
                    } else {
                        alert(response.message);
                    }
                }.bind(this),
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        });
    });
</script>

@if(Session::has('success'))
<script>
    swal("Success!", "{{Session::get('success')}}", 'success', {
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

</html>