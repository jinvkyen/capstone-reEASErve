<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Overview</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">

    <!-- Tailwind CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.deptadmin.dept_admin_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 text-4xl font-bold">User Request</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Content -->
            <div class="flex justify-center mt-2">
                <div class="bg-custom-color mx-4 p-4 mt-4 rounded-lg shadow-lg w-full" style="background-color: var(--custom-color);">

                    <!-- Header -->
                    <div class="border-b-8 border-white text-xl lg:text-2xl mb-6">
                        <div class="flex mb-3 lg:flex-row lg:justify-between  justify-end">
                            <!-- Left Section (Transaction Details) -->
                            <div class="flex flex-col lg:justify-start w-full lg:w-auto sm:w-full lg:text-left">

                                <!-- name -->
                                <h3 class="font-semibold sm:text-lg lg:text-3xl text-left">{{ $reservation->name }}</h3>

                                <!-- transaction id -->
                                <h5 class="text-sm font-medium">Reservation ID: <span class="font-normal"
                                        style="text-decoration:underline;"> {{$reservation->id}}</span></h5>

                                <!-- serial number -->
                                <h5 class="text-sm font-medium">Facility ID: <span class="font-normal"
                                        style="text-decoration:underline;"> {{$reservation->facilities_id}} </span></h5>
                            </div>

                            <!-- Right Section (Buttons) -->
                            <div class="flex items-center space-x-4 w-full lg:w-auto sm:w-full lg:justify-end md:justify-end sm:justify-end">

                                <div class="relative group mb-3 items-center">
                                    <!-- Status -->
                                    <h5 class="lg:text-lg sm:text-sm font-medium  justify-end">
                                        <span class="bg-opacity-20 px-3 py-1 rounded-full">
                                            <div class="text-xs font-medium ">Status:</div> <!-- Status label -->
                                            <span class="{{ $reservation->status_color }}"> {{ $reservation->status_state }} </span> <!-- Status state -->
                                        </span>
                                    </h5>
                                </div>

                                <div class="flex flex-wrap justify-center sm:justify-end items-center gap-2">
                                    <form action="{{ route('admin.approve.facility') }}" method="POST"
                                        class="flex flex-wrap justify-center gap-2">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ request('status') }}" />
                                        <input type="hidden" name="transaction_id" value="{{ request('id') }}" />
                                        <input type="hidden" name="facility_id" value="{{ $reservation->facilities_id}}" />

                                        @if ($reservation->status == '2')
                                        <div class="flex space-x-2 justify-evenly">
                                            <!-- Approve Button -->
                                            <div class="flex flex-col relative items-center group">
                                                <button type="submit" name="action" value="accept"
                                                    class="flex items-center bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 transition">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <div
                                                    class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                    Approve
                                                </div>
                                            </div>
                                            <!-- Reject Button -->
                                            <div class="flex flex-col relative items-center group">
                                                <button type="submit" name="action" value="reject"
                                                    class="flex items-center bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                                <div
                                                    class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                    Reject
                                                </div>
                                            </div>
                                        </div>
                                        @elseif ($reservation->status == '3')
                                        <div class="flex space-x-2">
                                            <!-- Occupied Button -->
                                            <div class="flex flex-col relative items-center group">
                                                <button type="submit" name="action" value="on-going"
                                                    class="flex items-center bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 transition">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <div
                                                    class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                    Occupied
                                                </div>
                                            </div>
                                            <!-- Cancel Button -->
                                            <div class="flex flex-col relative items-center group">
                                                <button type="submit" name="action" value="cancelled"
                                                    class="flex items-center bg-gray-500 text-white px-2 py-1 rounded-md hover:bg-gray-600 transition">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                                <div
                                                    class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                    Cancel
                                                </div>
                                            </div>
                                            <!-- Revert Button -->
                                            <div class="flex flex-col relative items-center group">
                                                <button type="submit" name="action" value="pending"
                                                    class="flex items-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 transition">
                                                    <i class="bi bi-arrow-return-left"></i>
                                                </button>
                                                <div
                                                    class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                    Revert
                                                </div>
                                            </div>
                                        </div>
                                        @elseif ($reservation->status == '5')
                                        <!-- completed -->
                                        <div class="flex flex-col relative items-center group">
                                            <button type="button" name="action" onclick="toggleModal()"
                                                class="flex items-center bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 transition">
                                                <i class="bi bi-door-open"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                Completed
                                            </div>
                                        </div>

                                        <!-- Available Button  -->
                                        @elseif ($reservation->status == '9' || $reservation->status == '12')
                                        <div class="flex flex-col relative items-center group">
                                            <button type="submit" name="action" value="completed" class="flex items-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 transition">
                                                <i class="bi bi-door-open"></i>
                                            </button>
                                            <div class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                Available
                                            </div>
                                        </div>
                                        @endif
                                    </form>
                                    </form>
                                    <!-- Modal Background (Backdrop) -->
                                    <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

                                    <!-- Modal Structure -->
                                    <div id="remarksModal"
                                        class="fixed m-0 inset-0 flex items-center justify-center hidden z-50">
                                        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                                            <h3 class="text-lg font-bold mb-4">Add Remarks</h3>
                                            <form action="{{ route('admin.approve.facility') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="completed" />
                                                <input type="hidden" name="transaction_id" value="{{ $reservation->id }}" />
                                                <input type="hidden" name="facility_id"
                                                    value="{{ $reservation->facilities_id }}" />

                                                <div class="mb-4">
                                                    <label for="remarks" class="block text-gray-700">Remarks</label>
                                                    <textarea id="remarks" name="remarks"
                                                        class="w-full p-2 border border-gray-300 rounded-md"
                                                        rows="4"></textarea>
                                                </div>

                                                <div class="flex justify-end">
                                                    <button type="button" onclick="toggleModal()"
                                                        class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                        class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                                        Mark as Completed
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="mt-2">
                        <h5 class="lg:text-xl md:text-lg sm:text-base font-semibold">Date</h5>
                    </div>

                    <div class="flex flex-wrap mt-2 gap-3">
                        <div class="bg-white lg:p-4 rounded-md shadow-md flex-1 md:p-2 sm:p-2">
                            <p class="font-semibold text-base lg:text-lg sm:text-sm text-gray-600 text-balance">
                                Requested Date:
                            </p>
                            <p class="sm:text-xs lg:text-base break-words lg:font-normal text-gray-500">
                                {{ $reservation->start_datetime }}
                            </p>
                        </div>
                        <div class="bg-white lg:p-4 rounded-md shadow-md flex-1 md:p-2 sm:p-2">
                            <p class="font-semibold text-base lg:text-lg sm:text-sm text-gray-600">
                                End Date:
                            </p>
                            <p class="sm:text-xs lg:text-base lg:font-normal text-gray-500">
                                {{ $reservation->end_datetime }}
                            </p>
                        </div>
                    </div>

                    <!-- Reservation Details -->
                    <div class="mt-4">
                        <h5 class="lg:text-xl md:text-lg sm:text-base font-semibold">Reservation Details</h5>
                    </div>

                    <!-- Main Container -->
                    <div class="flex justify-center mt-2 font-mont">
                        <div class="bg-white p-3 rounded-lg shadow-md w-full">
                            <!-- User Information -->
                            <div class="border-2 border-gray-200 mb-3">
                                <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">User ID:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $reservation->user_id }}</div>
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Students Name:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $reservation->last_name }} {{ $reservation->first_name }}</div>
                                </div>

                                <!-- Requested Duration and Department Owner in the Same Row -->
                                <div class="flex flex-col sm:flex md:flex md:flex-row ">
                                    <!-- Requested Duration -->
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Requested Duration:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">
                                        @php
                                        $startDateTime = \Carbon\Carbon::parse($reservation->start_datetime)->format('g:i A');
                                        $endDateTime = \Carbon\Carbon::parse($reservation->end_datetime)->format('g:i A');

                                        $totalMinutes = \Carbon\Carbon::parse($reservation->start_datetime)->diffInMinutes($reservation->end_datetime);

                                        $days = floor($totalMinutes / 1440); // 1440 minutes in a day
                                        $remainingMinutes = $totalMinutes % 1440;
                                        $hours = floor($remainingMinutes / 60);
                                        $minutes = $remainingMinutes % 60;

                                        $formattedDuration = $days > 0
                                        ? "{$days} day(s)" . ($hours > 0 ? ", {$hours} hour(s)" : '') . ($minutes > 0 ? " and {$minutes} mins" : '')
                                        : ($hours > 0 ? "{$hours} hour(s)" . ($minutes > 0 ? " and {$minutes} mins" : '') : "{$minutes} mins");

                                        echo $formattedDuration;
                                        @endphp
                                    </div>

                                    <!-- Department Owner -->
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Department Owner:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $reservation->owner }}</div>
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div class="border-2 border-gray-200 ">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Purpose:</div>
                                </div>
                                <div class="px-4 py-2">{{ $reservation->purpose }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Details -->
                    <div class="mt-4">
                        <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Approval Details</h5>
                    </div>

                    <div class="flex justify-center mt-2 font-mont">
                        <div class="bg-white p-6 rounded-lg shadow-md w-full">
                            <!-- Approved By and Returned To -->
                            <div class="border-2 border-gray-200 mb-3">
                                <div class="flex flex-col sm:flex md:flex md:flex-row">
                                    <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Approved By:</div>
                                    <div class="px-4 py-2 lg:w-3/4 sm:w-4/5">{{ $reservation->approved_by_first_name ? $reservation->approved_by_first_name . ' ' . $reservation->approved_by_last_name : 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="border-2 border-gray-200">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Remarks:</div>
                                </div>
                                <div class="px-4 py-2">{{ $reservation->remarks ? $reservation->remarks : 'No Remarks Added by the Admin.' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Policy -->
                    <div class="mt-4">
                        <h5 class="lg:text-xl md:text-lg sm:text-base font-semibold">Policy</h5>
                    </div>

                    <div class="bg-white p-6 rounded-md shadow-md mt-2 items-center">
                        @if($reservation->policy_name || $reservation->policy_content || $reservation->inclusions)
                        @if($reservation->policy_name)
                        <h2 class="text-lg font-bold text-left">{{ $reservation->policy_name }}</h2>
                        @endif

                        @if($reservation->policy_content)
                        <p class="text-sm lg:text-base md:text-sm mt-4 text-justify">{{ $reservation->policy_content }}
                        </p>
                        @endif

                        @if($reservation->inclusions)
                        <p class="text-sm lg:text-base md:text-sm mt-2 text-justify"><strong>Inclusions:
                            </strong>{{ $reservation->inclusions }}</p>
                        @endif
                        @else
                        <p class="sm:text-sm lg:text-base md:text-sm text-center lg:font-semibold text-gray-300">No
                            Policy for this Resource</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Success -->
    @if(Session::has('success'))
    <script>
        swal("Success", "{{ Session::get('success') }}", 'success', {
            button: true,
            button: "OK"
        });
    </script>
    @endif

    <!-- Error -->
    @if(Session::has('error'))
    <script>
        swal("Invalid", "{{ Session::get('error') }}", 'error', {
            button: true,
            button: "OK"
        });
    </script>
    @endif

    <script>
        function toggleModal() {
            const modal = document.getElementById('remarksModal');
            const backdrop = document.getElementById('modalBackdrop');

            // Toggle hidden class to show/hide modal and background
            modal.classList.toggle('hidden');
            backdrop.classList.toggle('hidden');
        }
    </script>

</body>

</html>