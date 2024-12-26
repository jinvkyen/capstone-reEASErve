<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Audit Trail</title>

    <!-- .ico logo -->
    <link
        rel="icon"
        href="{{ asset('storage/icon-logo/logo.ico') }}"
        type="image/x-icon" />

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />

    <!-- FontAwesome for Icons -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Bootstrap JS for Modal -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Bootstrap CSS -->
    <link
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        rel="stylesheet" />

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
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">
                    Audit Trail
                </h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>


            <!-- Search and Table Container -->
            <div
                id="audit"
                class="tab-pane mx-4 p-4 mt-6 font-mont rounded-lg shadow-lg sm:text-sm"
                style="background-color: var(--custom-color)">
                <div class="flex justify-between mb-4">
                    <!-- Search -->
                    <div
                        class="flex sm:justify-center justify-end mb-3 sm:mt-0">
                        <input type="text" id="searchInput" placeholder="Search" class="font-mont px-2 py-2 w-full md:w-72 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="flex flex-row mt-2 lg:mt-0 space-x-2">
                        <form
                            action="{{ route('ma.audit.mass.archive') }}"
                            method="POST"
                            id="delete7daysForm">
                            @csrf
                            <input
                                type="hidden"
                                name="submit_type"
                                value="form" />
                            <div
                                class="flex flex-col relative items-center group">
                                <button
                                    type="button"
                                    class="delete7days flex items-center bg-purple-500 text-white lg:px-3 lg:py-2 md:px-2 md:py-1 sm:px-2 sm:py-1 rounded-md hover:bg-purple-600 transition">
                                    <i class="bi bi-archive-fill"></i>
                                </button>
                                <div
                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-white text-xs mb-2 rounded px-2 py-2 whitespace-nowrap shadow-lg">
                                    Archive Records from <br />
                                    Past 7 Days
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Dropdown Filter by Action Type -->
                <div class="flex lg:sm:flex-row md:flex-col sm:flex-col lg:justify-end md:justify-end sm:justify-center m-0 py-2 px-2">
                    <label for="statusFilter" class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5">
                        <i class="fas fa-filter text-gray-400"></i> Action Type
                    </label>
                    <select id="statusFilter" class="leading-tight mt-1 flex-row w-18 border-gray-300 rounded-md p-1 font-mont overflow-y-auto">
                        <option value="" selected>All</option>
                        @foreach($combinedRequestsPaginated->unique('action_type') as $status)
                        <option value="{{ $status->action_type }}">
                            {{ $status->action_type }}
                        </option>
                        @endforeach
                    </select>

                    <label for="tableFilterDropdown" class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5">
                        <i class="fas fa-filter text-gray-400"></i> Table
                    </label>
                    <select id="tableFilterDropdown" class="mt-1 flex-row w-18 border-gray-300 rounded-md p-1 font-mont">
                        <option value="auditTableContainer">Active Audit</option>
                        <option value="archivedAuditTableContainer">Archived Audit</option>
                    </select>
                </div>


                <div class="lg:max-h-[29rem] sm:max-h-screen overflow-x-auto mt-4" id="auditTableContainer">
                    <!-- Active Audit Table -->
                    <table id="auditTable" class="min-w-full table-fixed">
                        <thead class="bg-gray-100 uppercase font-large text-xs top-0 sticky z-10">
                            <tr class="text-gray-900">
                                <th
                                    class="w-32 px-2 lg:px-4 py-2 bg-gray-100 z-10 text-center">
                                    ID
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words">
                                    Made By
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words whitespace-nowrap">
                                    Log Info
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words whitespace-nowrap">
                                    User ID
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words">
                                    Action Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-nowrap">
                                    Date Created
                                    <button id="pickupDateFilterLaboratory" onclick="toggleSort('laboratory', 'pickup')" class="ml-1">
                                        <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>

                                <th class="px-2 lg:px-4 py-2 lg:break-words text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($audit as $entry)
                            <tr class="table-row bg-white border-b hover:bg-gray-50">
                                <td class="w-32 px-2 lg:px-4 py-2 font-medium text-gray-900 whitespace-nowrap break-words text-center">
                                    {{ $entry->id }}
                                </td>
                                <td class="px-2 lg:px-4 py-2 whitespace-wrap text-sm text-gray-500">
                                    <div class="text-sm font-semibold text-gray-700">
                                        {{ $entry->made_by }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $entry->user_type }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-0 py-2 max-w-xs font-medium text-gray-900 whitespace-wrap group relative">
                                    <div class="truncate max-w-[200px]">
                                        {{ $entry->action }}
                                    </div>
                                    <!-- Tooltip for full content on hover -->
                                    <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-y-auto z-10">
                                        {{ $entry->action }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $entry->user_id }}
                                </td>
                                <td class="status-cell px-2 lg:px-4 py-2 font-medium text-gray-900 whitespace-nowrap group relative">
                                    <div class="truncate max-w-[200px]">
                                        <span class="status-label">{{ $entry->action_type }}</span>
                                    </div>
                                    <!-- Tooltip for full content on hover -->
                                    <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-y-auto z-10">
                                        {{ $entry->action_type }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    <div>
                                        {{ \Carbon\Carbon::parse($entry->datetime)->format('M j, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($entry->datetime)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-4 py-2 no-underline text-center">
                                    <i class="deleteModalTrigger bi bi-archive-fill fa-lg text-purple-500 cursor-pointer hover:text-purple-700" data-id="{{ $entry->id }}"></i>
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
                <!-- Pagination Links -->
                <div class="flex justify-center mt-4">
                    <div class="flex space-x-2">
                        {{ $audit->appends(['search' => $search])->links() }}
                    </div>
                </div>

                <div class="lg:max-h-[29rem] sm:max-h-screen overflow-x-auto" id="archivedAuditTableContainer" style="display: none;">
                    <!-- Archived Audit Table -->
                    <table id="archivedAuditTable" class="min-w-full table-fixed">
                        <thead class="bg-gray-100 uppercase font-large text-xs top-0 sticky z-10">
                            <tr class="text-gray-900">
                                <th
                                    class="w-32 px-2 lg:px-4 py-2 bg-gray-100 z-10 text-center">
                                    ID
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words">
                                    Made By
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words whitespace-nowrap">
                                    Log Info
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words whitespace-nowrap">
                                    User ID
                                </th>
                                <th
                                    class="px-2 lg:px-4 py-2 lg:break-words">
                                    Action Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-nowrap">
                                    Date and Time
                                    <button id="datetimeDateFilterAudit" onclick="toggleSort('archivedAudit', 'datetime')" class="ml-1">
                                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($audit_archived as $entry)
                            <tr class="table-row bg-white border-b hover:bg-gray-50">
                                <td class="w-32 px-2 lg:px-4 py-2 font-medium text-gray-900 whitespace-nowrap break-words text-center">
                                    {{ $entry->id }}
                                </td>
                                <td class="px-2 lg:px-4 py-2 whitespace-wrap text-sm text-gray-500">
                                    <div class="text-sm font-semibold text-gray-700">
                                        {{ $entry->made_by }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $entry->user_type }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-0 py-2 max-w-xs font-medium text-gray-900 whitespace-wrap group relative">
                                    <div class="truncate max-w-[200px]">
                                        {{ $entry->action }}
                                    </div>
                                    <!-- Tooltip for full content on hover -->
                                    <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-y-auto z-10">
                                        {{ $entry->action }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $entry->user_id }}
                                </td>
                                <td class="status-cell px-2 lg:px-4 py-2 font-medium text-gray-900 whitespace-nowrap group relative">
                                    <div class="truncate max-w-[200px]">
                                        <span class="status-label">{{ $entry->action_type }}</span>
                                    </div>
                                    <!-- Tooltip for full content on hover -->
                                    <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-y-auto z-10">
                                        {{ $entry->action_type }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    <div>
                                        {{ \Carbon\Carbon::parse($entry->datetime)->format('M j, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($entry->datetime)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 no-underline text-center">
                                    <div class="flex flex-row relative items-center group">
                                        <div class="flex flex-col relative items-center group">
                                            <button type="button" class="restoreModalTrigger flex items-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 transition" data-id="{{ $entry->id }}">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-white text-xs mb-2 rounded px-2 py-2 whitespace-nowrap shadow-lg">
                                                Restore Records
                                            </div>
                                        </div>
                                    </div>
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

                    <!-- Pagination Links -->
                    <div class="flex justify-between mt-4">
                        <div class="flex space-x-2 text-center align-middle">
                            {{ $audit_archived->appends(['search' => $search])->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Search Script -->
        <script>
            document.getElementById("searchInput").addEventListener("keyup", function() {
                const searchText = this.value.trim().toLowerCase();
                const selectedTableId = document.getElementById("tableFilterDropdown").value;

                const route = "{{ route('ma.audit') }}";


                fetch(route + "?search=" + searchText)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTableContent = doc.querySelector("#" + selectedTableId).innerHTML;

                        // Replace content only in the selected table container
                        document.getElementById(selectedTableId).innerHTML = newTableContent;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            });

            // Show/hide tables based on dropdown selection
            document.getElementById("tableFilterDropdown").addEventListener("change", function() {
                const selectedTableId = this.value;

                // Toggle visibility of table containers
                document.getElementById("auditTableContainer").style.display = selectedTableId === "auditTableContainer" ? "block" : "none";
                document.getElementById("archivedAuditTableContainer").style.display = selectedTableId === "archivedAuditTableContainer" ? "block" : "none";
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


        <!-- Arhcive Audit Record -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Delegate the click event to a parent element that exists when the page is loaded
                $(document).on("click", ".deleteModalTrigger", function() {
                    const id = $(this).data("id");

                    $.ajax({
                        url: '{{ route("ma.audit.archive", ["id" => "__id__"]) }}'.replace(
                            "__id__",
                            id
                        ),
                        type: "POST",
                        data: {
                            _method: "POST",
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);

                                // Remove the row containing the deleted audit entry
                                $(this).closest("tr").remove();
                            } else {
                                alert(response.message);
                            }
                        }.bind(this),
                        error: function(xhr, status, error) {
                            alert("An error occurred: " + error);
                        },
                    });
                });
            });
        </script>

        <!-- Confirmation -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document
                    .querySelector(".delete7days")
                    .addEventListener("click", function(event) {
                        event.preventDefault(); // Prevent the form from submitting immediately

                        const form = document.getElementById("delete7daysForm");

                        Swal.fire({
                            title: "Are you sure you want to archive all records older than 7 days?",
                            text: "This action cannot be undone!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#8db4d9",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            } else if (
                                result.dismiss === Swal.DismissReason.cancel
                            ) {
                                Swal.fire({
                                    title: "Cancelled",
                                    text: "No records were Archived.",
                                    icon: "error",
                                });
                            }
                        });
                    });
            });
        </script>

        <!-- Dropdown Filter -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const statusFilter = document.getElementById("statusFilter");
                const tableFilterDropdown = document.getElementById("tableFilterDropdown");

                // Ensure the event listeners are not being duplicated
                statusFilter.removeEventListener("change", applyStatusFilter);
                statusFilter.addEventListener("change", applyStatusFilter);

                tableFilterDropdown.removeEventListener("change", toggleTables);
                tableFilterDropdown.addEventListener("change", toggleTables);

                function toggleTables() {
                    const selectedTable = tableFilterDropdown.value;
                    const activeAuditContainer = document.getElementById("auditTableContainer");
                    const archivedAuditContainer = document.getElementById("archivedAuditTableContainer");

                    activeAuditContainer.style.display = selectedTable === "auditTableContainer" ? "block" : "none";
                    archivedAuditContainer.style.display = selectedTable === "archivedAuditTableContainer" ? "block" : "none";

                    // Reset filter when table is toggled
                    statusFilter.value = "";
                    applyStatusFilter();
                }

                function applyStatusFilter() {
                    const selectedActionType = statusFilter.value.trim();
                    const visibleTableId = tableFilterDropdown.value;
                    const visibleTableRows = document.querySelectorAll(`#${visibleTableId} tbody tr`);
                    let hasVisibleRows = false;

                    visibleTableRows.forEach((row) => {
                        if (row.classList.contains("no-records")) return;

                        const actionTypeCell = row.querySelector(".status-cell .status-label");
                        const statusText = actionTypeCell ? actionTypeCell.textContent.trim() : "";

                        // Match action type
                        const matchesFilter = selectedActionType === "" || statusText === selectedActionType;

                        row.style.display = matchesFilter ? "table-row" : "none";
                        if (matchesFilter) hasVisibleRows = true;
                    });

                    // Show or hide "No records found"
                    const noRecordsRow = document.querySelector(`#${visibleTableId} .no-records`);
                    if (noRecordsRow) {
                        noRecordsRow.style.display = hasVisibleRows ? "none" : "table-row";
                    }
                }

                // Initial table and filter setup
                applyStatusFilter();
            });
        </script>

        <!-- Archive Audit -->

        <!-- Restore Audit Record -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $(document).on("click", ".restoreModalTrigger", function() {
                    const id = $(this).data("id");
                    const action = $(this).hasClass("restoreModalTrigger") ? "restore" : "archive";
                    const route = action === "restore" ? "ma.audit.restore" : "ma.audit.archive";

                    $.ajax({
                        url: `{{ route('ma.audit.restore', ['id' => '__id__']) }}`.replace('__id__', id),
                        type: "POST",
                        data: {
                            _method: "POST",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                $(this).closest("tr").remove();
                            } else {
                                alert(response.message);
                            }
                        }.bind(this),
                        error: function(xhr, status, error) {
                            alert("An error occurred: " + error);
                        }
                    });
                });
            });
        </script>


        @if(Session::has('success'))
        <script>
            swal("Success!", "{{Session::get('success')}}", "success", {
                button: true,
                button: "OK",
            });
        </script>
        @endif @if(Session::has('error'))
        <script>
            swal("Oops...", "{{Session::get('error')}}", "error", {
                button: true,
                button: "OK",
            });
        </script>
        @endif
</body>

</html>