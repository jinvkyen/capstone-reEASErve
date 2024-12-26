<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                <h1 class="mt-5 lg:text-4xl sm:text-xl font-bold">Audits Archived</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">

                </div>
            </div>

            <div class="bg-custom-color mx-4 mt-4 p-4 rounded-lg shadow-lg" style="background-color: var(--custom-color);">
                <!-- Top Section -->
                <div class="flex flex-col lg:flex-row justify-between font-mont p-2 space-y-4 lg:space-y-0">
                    <!-- Search and Button Container -->
                    <div class="flex flex-col lg:flex-row lg:space-x-4 items-center w-full">
                        <!-- Search -->
                        <div class="flex-grow">
                            <input type="text" id="searchInput" placeholder="Search" class="font-mont px-2 py-2 w-full md:w-72 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Audit Table -->
                <div id="auditTableContainer" class="overflow-x-auto">
                    <table id="auditTable" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-2 py-2">ID</th>
                                <th scope="col" class="px-4 py-2">Log Info</th>
                                <th scope="col" class="px-4 py-2">Made By</th>
                                <th scope="col" class="px-4 py-2">User ID</th>
                                <th scope="col" class="px-4 py-2">Action Type</th>
                                <th scope="col" class="px-4 py-2 inline-flex items-center">
                                    Date and Time
                                    <button id="datetimeDateFilterAudit" onclick="toggleSort('auditTable', 'datetime')">
                                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($audit_archived as $entry)
                            <tr class="bg-white border-b hover:bg-gray-100 text-sm">
                                <td class="px-2 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $entry->id }}</td>
                                <td class="px-2 lg:px-0 py-2 max-w-xs font-medium text-gray-900 whitespace-wrap group relative">
                                    <div class="truncate max-w-[250px]">{{ $entry->action }}</div>
                                    <!-- Tooltip for full content on hover -->
                                    <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-48 overflow-y-auto z-10">
                                        {{ $entry->action }}
                                    </div>
                                </td>
                                <td class="px-4 py-2">{{ $entry->made_by }}</td>
                                <td class="px-4 py-2">{{ $entry->user_id }}</td>
                                <td class="px-4 py-2">{{ $entry->action_type }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ \Carbon\Carbon::parse($entry->datetime)->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($entry->datetime)->format('h:i A') }}</div>
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
                            @endforeach
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
</body>
<!-- Tab Filters -->
<script>
    function toggleSort(tableId, column) {
        const table = document.getElementById(tableId);
        const rows = Array.from(table.tBodies[0].rows);
        const headerButton = document.querySelector(`#${column}DateFilterAudit svg`);

        let isAscending = headerButton.classList.contains('rotate-180');
        headerButton.classList.toggle('rotate-180', !isAscending);

        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[5].textContent.trim();
            const cellB = rowB.cells[5].textContent.trim();
            const dateA = new Date(cellA);
            const dateB = new Date(cellB);

            return isAscending ? dateA - dateB : dateB - dateA;
        });

        rows.forEach(row => table.tBodies[0].appendChild(row));
    }
</script>

<!-- Search Script -->
<script>
    // Search Script
    document.getElementById("searchInput").addEventListener("keyup", function() {
        const searchText = this.value.trim().toLowerCase();

        fetch("{{ route('ma.audits.archived') }}?search=" + searchText)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector("#auditTableContainer").innerHTML;
                document.getElementById("auditTableContainer").innerHTML = newTable;
            })
            .catch(error => console.log('Error fetching data:', error));
    });

    // Sort by Date Function
    function toggleSort(tableId, column) {
        const table = document.getElementById(tableId);
        const rows = Array.from(table.tBodies[0].rows);
        const headerButton = document.querySelector(`#${column}DateFilterAudit svg`);

        let isAscending = headerButton.classList.contains('rotate-180');
        headerButton.classList.toggle('rotate-180', !isAscending);

        rows.sort((rowA, rowB) => {
            const dateA = new Date(rowA.cells[5].textContent.trim());
            const dateB = new Date(rowB.cells[5].textContent.trim());

            return isAscending ? dateA - dateB : dateB - dateA;
        });

        rows.forEach(row => table.tBodies[0].appendChild(row));
    }
</script>

<!-- Restore Audit Record -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle delete button click
        $('.restoreModalTrigger').on('click', function() {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route("ma.audit.restore", ["id" => "__id__"]) }}'.replace('__id__', id),
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

<!-- Confirmation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.delete7days').addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the form from submitting immediately

            const form = document.getElementById('delete7daysForm');

            Swal.fire({
                title: "Are you sure you want to archive all records older than 7 days?",
                showDenyButton: true,
                confirmButtonText: "Yes",
                denyButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                } else if (result.isDenied) {
                    Swal.fire("Cancelled", "", "info");
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