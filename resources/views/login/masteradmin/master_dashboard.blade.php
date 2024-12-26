<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Chart.js -->
    <script src="{{ asset('js/chart.js') }}"></script>

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.masteradmin.master_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h2 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Dashboard</h2>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>


            <div class="flex flex-col p-4 mx-4 rounded-lg shadow-lg mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Pending -->
                    <div class="p-2.5 text-center flex flex-col justify-center items-center h-[100px] rounded-md hover:shadow-lg transition-shadow duration-300" style="background-color: #faf089">
                        <div class="text-yellow-800 font-semibold text-sm lg:text-lg">PENDING <br>REQUESTS</div>
                        <div class="text-yellow-900 font-bold text-lg lg:text-3xl">{{ $pending }}</div>
                    </div>

                    <!-- Approved -->
                    <div class="p-2.5 text-center flex flex-col justify-center items-center h-[100px] rounded-md hover:shadow-lg transition-shadow duration-300" style="background-color: #95d16dd3">
                        <div class="text-green-800 font-semibold text-sm lg:text-lg">APPROVED REQUESTS</div>
                        <div class="text-green-900 font-bold text-lg lg:text-3xl">{{ $approved }}</div>
                    </div>

                    <!-- On-Going -->
                    <div class="p-2.5 text-center flex flex-col justify-center items-center h-[100px] rounded-md hover:shadow-lg transition-shadow duration-300" style="background-color: #4dbae2c1">
                        <div class="text-blue-800 font-semibold text-sm lg:text-lg">ON-GOING REQUESTS</div>
                        <div class="text-blue-900 font-bold text-lg lg:text-3xl">{{ $ongoing }}</div>
                    </div>

                    <!-- For Replacement -->
                    <div class="p-2.5 text-center flex flex-col justify-center items-center h-[100px] rounded-md hover:shadow-lg transition-shadow duration-300" style="background-color: #ea8282c9">
                        <div class="text-red-800 font-semibold text-xs lg:text-lg">RESOURCES FOR REPLACEMENT</div>
                        <div class="text-red-900 font-bold text-lg lg:text-3xl">{{ $replace }}</div>
                    </div>
                </div>

                <!-- Resource Type -->
                <h2 class="my-5 font-semibold text-lg lg:text-3xl text-gray-800 font-mont text-center">Resource and Borrowing Data Reports</h2>
                <div class="flex flex-col item-start justify-start">
                    <!-- Dropdown Filter by Duration -->
                    <div class="flex flex-row lg:justify-end md:justify-end sm:justify-center m-0">
                        <label for="statusFilter" class="flex-col text-sm font-medium text-gray-700 px-2 mt-2.5"><i class="fas fa-filter text-gray-500 text-lg"></i> Range: </label>
                        <select id="statusFilter" class="flex-row w-18 border-gray-300 rounded-md p-1 font-mont">
                            <option value="7">1 WEEK</option>
                            <option value="30" selected>1 MONTH</option>
                            <option value="90">3 MONTHS</option>
                            <option value="180">6 MONTHS</option>
                            <option value="365">1 YEAR</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-start w-full mt-8">
                    <div class="w-full space-y-8">
                        <div class="bg-white p-4 rounded-xl shadow-md">
                            <canvas class="w-full h-[200px] sm:h-[300px]" id="resourceType"></canvas>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-md">
                            <canvas class="w-full h-[200px] sm:h-[300px]" id="dailyRequests"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start of Facilities Availability -->
            <div class="flex flex-col p-4 rounded-lg shadow-lg mx-4 mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="flex justify-between py-2">
                    <h1 class="text-lg lg:text-3xl font-semibold w-full">Facilities Availability</h1>
                </div>
                <!-- Facility List -->
                <div id="facility-list" class="space-y-4 bg-white p-3 sm:text-sm w-full">
                    @if ($facilities->isEmpty())
                    <p class="text-gray-500 text-center text-lg font-bold">No Facilities Available</p>
                    @else
                    @foreach ($facilities as $facility)
                    @php
                    $isUnderMaintenance = $facility->facility_status == 12; // Use aliased facility_status
                    $isActuallyAvailable = !$isUnderMaintenance;
                    $currentReservation = $facility->reservations->whereIn('status', [3, 5])->first();
                    @endphp

                    <!-- Facility Item -->
                    <div class="facility-item flex items-center justify-between lg:text-base sm:text-xs" data-is-available="{{ $isActuallyAvailable ? '1' : '0' }}" data-end-datetime="{{ optional($currentReservation)->end_datetime }}">
                        <div class="flex items-center space-x-2 xs:text-sm">
                            <span class="{{ $isActuallyAvailable ? 'bg-green-700' : 'bg-red-700' }} w-5 h-5 rounded-full mb-2"></span>
                            <div class="text-justify xs:text-sm">
                                <h2 class="font-semibold mb-0 sm:text-sm lg:text-base text-left">{{ $facility->facility_name }}</h2>
                                <p class="text-gray-600 mt-0 sm:text-sm lg:text-base text-left">Location: {{ $facility->location }}</p>
                            </div>
                        </div>

                        <p class="font-semibold {{ $isActuallyAvailable ? 'text-green-700' : 'text-gray-500' }} lg:text-base text-right sm:text-xs break-words">
                            @if ($isActuallyAvailable)
                            Available
                            @else
                            @if ($isUnderMaintenance)
                            <span class="text-red-700">Under Maintenance</span>
                            @endif
                            @endif
                        </p>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            <!-- End of Facilities Availability -->

            <!-- Policies -->
            <div class="flex flex-col p-2 rounded-lg shadow-lg mx-4 mt-8 font-mont" style="background-color: var(--custom-color);">
                <h2 class="lg:text-3xl text-lg font-semibold pl-3 mt-4 mb-2">General Policy</h2>
                <div class="bg-100 p-3 text-justify" style="background-color: var(--custom-color);">
                    <!-- Policy -->
                    @if($policies->isEmpty())
                    <div class="mt-3 mx-3 bg-white p-6 flex items-center justify-center">
                        <h4 class="text-gray-500">Policies Added Here are Shown to the Users' Dashboard</h4>
                    </div>
                    @else
                    <!-- Policy List -->
                    @foreach($policies as $policy)
                    <div class=" bg-white p-4 lg:p-6 flex flex-col lg:flex-row mb-2 relative">
                        <div class="flex absolute top-0 right-0 mt-3 space-x-2">
                            <!-- Button for Editing -->
                            <button class="fa-solid fa-pen-to-square lg:text-2xl sm:text-sm focus:outline-none mx-2 text-blue-600" data-toggle="modal" data-target="#editModal" data-policy-id="{{ $policy->id }}" data-policy-title="{{ $policy->policy_name }}" data-policy-description="{{ $policy->policy_content }}">
                            </button>
                            <!-- Button for Deleting -->
                            <button class="fa-solid fa-trash-can lg:text-2xl sm:text-sm focus:outline-none mx-2 mr-3 text-red-500" data-policy-id="{{ $policy->id }}" onclick="confirmDelete('{{ $policy->id }}')">
                            </button>
                        </div>
                        <div class="w-full lg:pl-0">
                            <h5 class="font-semibold sm:text-sm lg:text-xl break-words mt-0">{{ $policy->policy_name }}</h5>
                            <p class="sm:text-xs lg:text-base break-words">{{ $policy->policy_content }}</p>
                        </div>
                    </div>
                    @endforeach
                    @endif


                    <div class="mt-3 mx-3 rounded-lg p-2 lg:p-3 flex flex-col lg:flex-row mb-4 relative">
                        <button class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 font-mont sm:text-xs lg:text-base" onclick="openAddModal()">
                            Add General Policy
                        </button>
                    </div>

                    <!-- Edit Policy Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit General Policy</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body font-mont">
                                    <form id="editPolicyForm" action="{{ route('ma.edit.gen.policy') }}" method="POST">
                                        @csrf
                                        <input type="hidden" id="policyId" name="policyId">
                                        <div class="form-group">
                                            <input id="policyTitle" name="title" class="w-full px-3 py-2 mb-2 border border-gray-300 rounded-lg font-mont" type="text" placeholder="Policy Title">
                                            <textarea id="policyDescription" name="description" class="w-full p-3 border border-gray-300 rounded-lg font-mont" rows="3" placeholder="Policy Content"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Policy Modal -->
                    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title sm:text-xs lg:text-base" id="editModalLabel">Add General Policy</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="addPolicyForm" action="{{ route('ma.add.gen.policy') }}" method="POST">
                                        @csrf
                                        <input type="hidden" id="policyId" name="policyId">
                                        <div class="form-group">
                                            <input id="policy_name" name="policy_name" class="w-full px-3 py-2 mb-2 border border-gray-300 rounded-lg font-mont" type="text" placeholder="Policy Title">
                                            <textarea id="policy_content" name="policy_content" class="w-full p-3 border border-gray-300 rounded-lg font-mont" rows="3" placeholder="Policy Content"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Policy Form -->
                    <form id="deletePolicyForm" action="{{ route('admin.delete.gen.policy') }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="deletePolicyId" name="policyId">
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>

</html>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Add General Policy Modal -->
<script>
    function openAddModal() {
        var addModal = new bootstrap.Modal(document.getElementById('addModal'), {
            keyboard: false
        });
        addModal.show();
    }
</script>



<!-- Populate Edit Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var policyId = button.data('policy-id'); // Extract info from data-* attributes
            var policyTitle = button.data('policy-title');
            var policyDescription = button.data('policy-description');

            var modal = $(this);
            modal.find('#policyId').val(policyId);
            modal.find('#policyTitle').val(policyTitle);
            modal.find('#policyDescription').val(policyDescription);
        });
    });
</script>

<!-- Delete Policy -->
<script>
    function confirmDelete(policyId) {
        // Show a confirmation dialog using SweetAlert2
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Set the policy ID in the hidden form
                document.getElementById('deletePolicyId').value = policyId;
                // Submit the form
                document.getElementById('deletePolicyForm').submit();
            }
        });
    }
</script>

<!-- Analytics -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.getElementById('statusFilter');
        const ctxResourceType = document.getElementById('resourceType').getContext('2d');
        const ctxDailyRequests = document.getElementById('dailyRequests').getContext('2d');

        // Initialize Resource Type Chart
        let myResourceTypeChart = new Chart(ctxResourceType, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: <?= json_encode($dataArray) ?>,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    },
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Reservations Per Category'
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                },
            },
        });

        // Initialize Daily Requests Chart
        let myDailyRequestsChart = new Chart(ctxDailyRequests, {
            type: 'line',
            data: {
                labels: <?= json_encode($reservationData['labels']) ?>, // Initial labels
                datasets: [{
                        label: 'Approved',
                        data: <?= json_encode($reservationData['approved']) ?>,
                        borderColor: 'rgba(54, 163, 38, 1)',
                        backgroundColor: 'rgba(54, 163, 38, 0.2)',
                    },
                    {
                        label: 'Rejected',
                        data: <?= json_encode($reservationData['rejected']) ?>,
                        borderColor: 'rgba(199, 50, 50, 1)',
                        backgroundColor: 'rgba(199, 50, 50, 0.2)',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    },
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Requests Chart'
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                },
            },
        });

        // Fetch and Update Charts on Dropdown Change
        dropdown.addEventListener('change', function() {
            const range = dropdown.value;

            fetch(`{{ route('ma.range') }}?range=${range}`)
                .then((response) => response.json())
                .then((data) => {
                    // Update Resource Type Chart
                    myResourceTypeChart.data.labels = data.resourceType.labels;
                    myResourceTypeChart.data.datasets = data.resourceType.datasets;
                    myResourceTypeChart.update();

                    // Update Daily Requests Chart
                    myDailyRequestsChart.data.labels = data.dailyRequests.labels;
                    myDailyRequestsChart.data.datasets[0].data = data.dailyRequests.approved;
                    myDailyRequestsChart.data.datasets[1].data = data.dailyRequests.rejected;
                    myDailyRequestsChart.update();
                })
                .catch((error) => {
                    console.error('Error fetching data:', error);
                });
        });

        dropdown.value = '30';
        dropdown.dispatchEvent(new Event('change'));

    });
</script>

@if (Session::has('success'))
<script>
    Swal.fire({
        title: 'Success',
        text: "{{ Session::get('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (Session::has('error'))
<script>
    Swal.fire({
        title: 'Oops..',
        text: "{{ Session::get('error') }}",
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif

<?php

use Illuminate\Support\Facades\Session; ?>
@if (Session::has('errors'))
<script>
    var errors = <?php echo json_encode(Session::get('errors')->all()); ?>;
    var errorMessages = errors.join('<br>');

    Swal.fire({
        title: 'Oops...',
        html: errorMessages,
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif