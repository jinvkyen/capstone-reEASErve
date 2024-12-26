<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Overview {{$cms['dept']}}</title>
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
                <h1 class="mt-5 lg:text-4xl sm:text-2xl text-nowrap font-bold">User Request</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Content -->
            <div class="flex justify-center mt-2">
                <div class="bg-custom-color p-6 rounded-lg mt-4 w-full mx-4 shadow-lg" style="background-color: var(--custom-color);">

                    <!-- Header -->
                    <div class="border-b-8 border-white text-xl lg:text-2xl mb-6">
                        <div class="flex mb-3 lg:flex-row lg:justify-between  justify-end">
                            <!-- Left Section (Transaction Details) -->
                            <div class="flex flex-col lg:justify-start w-full lg:w-auto sm:w-full lg:text-left">

                                <!-- name -->
                                <h3 class="font-semibold sm:text-lg lg:text-3xl text-left">{{ $query->name }}</h3>

                                <!-- transaction id -->
                                <h5 class="text-sm font-medium">Transaction ID: <span class="font-normal"
                                        style="text-decoration:underline;"> {{$query->transaction_id}}</span></h5>

                                <!-- serial number -->
                                <h5 class="text-sm font-medium">Resource Serial Number: <span class="font-normal"
                                        style="text-decoration:underline;"> {{$query->serial_number}} </span></h5>


                            </div>

                            <!-- Right Section (Buttons) -->
                            <div class="flex items-center space-x-4 w-full lg:w-auto sm:w-full lg:justify-end md:justify-end sm:justify-end">

                                <div class="relative group mb-3 items-center">
                                    <!-- Status -->
                                    <h5 class="lg:text-lg sm:text-sm font-medium  justify-end">
                                        <span class="bg-opacity-20 px-3 py-1 rounded-full">
                                            <div class="text-xs font-medium ">Status:</div> <!-- Status label -->
                                            <span class="{{ $query->status_color }}"> {{ $query->status_state }} </span> <!-- Status state -->
                                        </span>
                                    </h5>
                                </div>

                                <form action="{{ route('admin.decision') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $query->status }}">
                                    <input type="hidden" name="transaction_id" value="{{ $query->transaction_id }}">
                                    <input type="hidden" name="resource_id" value="{{ $query->resource_id }}">
                                    <input type="hidden" name="resource_type" value="{{ $query->resource_type }}">

                                    @if ($query->status == '2')
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
                                            <button type="button" onclick="showRejectedRemarksModal('{{ $query->transaction_id }}', '{{ $query->resource_id }}')"
                                                class="flex items-center bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-lg">
                                                Reject
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if ($query->status == '3')
                                    <div class="flex space-x-2">
                                        <!-- claimed -->
                                        <div class="flex flex-col relative items-center group">
                                            <button type="submit" name="action" value="claimed"
                                                class="flex items-center bg-blue-500 text-white mt-1 px-2 py-1 rounded-lg hover:bg-blue-600">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-md">
                                                Claimed
                                            </div>
                                        </div>
                                        <!-- Cancelled -->
                                        <div class="flex flex-col relative items-center group">
                                            <button type="button" onclick="showCancelledRemarksModal('{{ $query->transaction_id }}', '{{ $query->resource_id }}')"
                                                class="flex items-center bg-gray-500 text-white mt-1 px-2 py-1 rounded-lg hover:bg-gray-600">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-md">
                                                Cancel
                                            </div>
                                        </div>
                                        <!-- revert -->
                                        <div class="flex flex-col relative items-center group">
                                            <button type="submit" name="action" value="pending"
                                                class="flex items-center bg-yellow-500 text-white mt-1 px-2 py-1 rounded-lg hover:bg-yellow-600">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-md">
                                                Revert
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if ($query->status == '5')
                                    <div class="flex space-x-2">
                                        <!-- returned -->
                                        <div class="flex flex-col relative items-center group">
                                            <button type="button"
                                                class="flex items-center bg-green-500 text-white mt-1 px-2 py-1 rounded-lg hover:bg-green-600"
                                                onclick="showRemarksModal('{{ $query->transaction_id }}', '{{ $query->resource_id }}')">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-md">
                                                Returned
                                            </div>
                                        </div>
                                        <!-- replacement -->
                                        <div class="flex flex-col relative items-center group">
                                            <button type="submit" name="action" value="replacement"
                                                class="flex items-center bg-red-500 text-white mt-1 px-2 py-1 rounded-lg hover:bg-red-600">
                                                <i class="bi bi-arrow-left-right"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-md">
                                                For Replacement
                                            </div>
                                        </div>
                                        <!-- late -->
                                        <div class="flex flex-col relative items-center group">
                                            <button type="submit" name="action" value="late"
                                                class="flex items-center bg-yellow-500 text-white mt-1 px-2 py-1 rounded-lg hover:bg-yellow-600">
                                                <i class="bi bi-exclamation-lg"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-md">
                                                Late
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if ($query->status == '9')
                                    <!-- revert -->
                                    <div class="flex flex-col relative items-center group">
                                        <button type="submit" name="action" value="claimed"
                                            class="flex items-center bg-yellow-500 text-white mt-1 px-2 py-1 rounded-lg hover:bg-yellow-600">
                                            <i class="bi bi-arrow-return-left"></i>
                                        </button>
                                        <div
                                            class="absolute bottom-full hidden group-hover:block bg-white text-xs mb-2 rounded px-3 py-2 whitespace-nowrap shadow-md">
                                            Revert
                                        </div>
                                    </div>
                                    @endif
                                </form>

                                <!-- Modal for Adding Remarks -->
                                <div id="remarksModal"
                                    class="hidden fixed m-0 inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
                                    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                                        <h2 class="text-xl font-semibold mb-4">Add Remarks</h2>
                                        <form id="remarksForm" method="POST" action="{{ route('admin.decision') }}">
                                            @csrf
                                            <textarea id="remarks" name="remarks"
                                                class="w-full h-24 border rounded-md p-2 mb-4"
                                                placeholder="Enter your remarks here..."></textarea>
                                            <input type="hidden" name="transaction_id" id="transaction_id"
                                                value="{{ $query->transaction_id }}">
                                            <input type="hidden" name="resource_id" id="resource_id"
                                                value="{{ $query->resource_id }}">
                                            <input type="hidden" name="action" id="action" value="returned">
                                            <div class="flex justify-end space-x-2">
                                                <button type="button"
                                                    class="bg-gray-500 text-white px-4 py-2 rounded-md"
                                                    onclick="closeRemarksModal()">Cancel</button>
                                                <button type="submit"
                                                    class="bg-blue-500 text-white px-4 py-2 rounded-md">Mark as
                                                    Returned</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal for Adding Remarks when REJECTED -->
                                <div id="rejectedRemarksModal" class="hidden fixed m-0 inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
                                    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                                        <h2 class="text-xl font-semibold mb-4">Add Reason</h2>
                                        <form id="rejectedRemarksForm" method="POST" action="{{ route('admin.decision') }}">
                                            @csrf
                                            <textarea id="rejectedRemarks" name="remarks" class="w-full h-24 border rounded-md p-2 mb-4" placeholder="Enter your reason here..."></textarea>
                                            <input type="hidden" name="transaction_id" id="rejectedTransactionId" value="{{ $query->transaction_id }}">
                                            <input type="hidden" name="resource_id" id="rejectedResourceId" value="{{ $query->resource_id }}">
                                            <input type="hidden" name="action" value="reject">
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md" onclick="closeModal('rejectedRemarksModal')">Cancel</button>
                                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal for Adding Remarks when CANCELLED -->
                                <div id="cancelledRemarksModal"
                                    class="hidden fixed m-0 inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
                                    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                                        <h2 class="text-xl font-semibold mb-4">Add Reason</h2>
                                        <form id="cancelledRemarksForm" method="POST" action="{{ route('admin.decision') }}">
                                            @csrf
                                            <textarea id="cancelledRemarks" name="remarks" class="w-full h-24 border rounded-md p-2 mb-4"
                                                placeholder="Enter your reason here..."></textarea>
                                            <input type="hidden" name="transaction_id" id="cancelledTransactionId" value="{{ $query->transaction_id }}">
                                            <input type="hidden" name="resource_id" id="cancelledResourceId" value="{{ $query->resource_id }}">
                                            <input type="hidden" name="action" value="cancelled">
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md"
                                                    onclick="closeCancelledRemarksModal()">Cancel</button>
                                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
                                            </div>
                                        </form>
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
                        <div class="bg-white lg:p-4 rounded-md shadow-md flex-1 md:p-1 sm:p-1">
                            <p class="font-semibold text-base lg:text-lg sm:text-sm text-gray-600 text-balance">Pick-Up
                                Date:</p>
                            <p class="sm:text-xs lg:text-base break-words lg:font-normal text-gray-500">
                                {{ $query->pickup_datetime }}
                            </p>
                        </div>
                        <div class="bg-white lg:p-4 rounded-md shadow-md flex-1 md:p-1 sm:p-1">
                            <p class="font-semibold text-base lg:text-lg sm:text-sm text-gray-600">Return Date:</p>
                            <p class="sm:text-xs lg:text-base lg:font-normal text-gray-500">
                                {{ $query->return_datetime }}
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
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->user_id }}</div>
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Name:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->last_name }}, {{ $query->first_name }}</div>
                                </div>
                                <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Resource Type:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->resource_type }}</div>
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Department Owner:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->dept }}</div>
                                </div>
                                <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Professor:</div>
                                    @if ($query->professor_first_name == 'N/A' && $query->professor_last_name == 'N/A')
                                    <div class="px-4 py-2 w-full sm:w-4/5">N/A</div>
                                    @else
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->professor_last_name }}, {{ $query->professor_first_name }}</div>
                                    @endif
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Subject:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->subject ?? 'N/A' }}</div>
                                </div>
                                <div class="flex flex-col sm:flex md:flex md:flex-row ">
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Section:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->section ?? 'N/A' }}</div>
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Course Schedule:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $query->schedule ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div class="border-2 border-gray-200 mb-3">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Purpose:</div>
                                </div>
                                <div class="px-4 py-2">{{ $query->purpose }}</div>
                            </div>

                            <!-- Group Members -->
                            <div class="border-2 border-gray-200">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Group Members:</div>
                                </div>
                                <div class="px-4 py-2">{{ $query->group_members ?? 'N/A' }}</div>
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
                                <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Approved By:</div>
                                    <div class="px-4 py-2 lg:w-2/4 sm:w-4/5">{{ $query->approved_by_first_name ? $query->approved_by_first_name . ' ' . $query->approved_by_last_name : 'N/A' }}</div>
                                    <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Released By:</div>
                                    <div class="px-4 py-2 lg:w-2/4 sm:w-4/5">{{ $query->released_by_first_name ? $query->released_by_first_name . ' ' . $query->released_by_last_name : 'N/A' }}</div>

                                </div>
                                <div class="flex flex-col sm:flex md:flex md:flex-row">
                                    <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Noted By:</div>
                                    <div class="px-4 py-2 lg:w-2/4 sm:w-4/5">{{ $query->noted_by_first_name ? $query->noted_by_first_name . ' ' . $query->noted_by_last_name : 'N/A' }}</div>
                                    <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Returned To:</div>
                                    <div class="px-4 py-2 lg:w-2/4 sm:w-4/5">{{ $query->returned_to_first_name ? $query->returned_to_first_name . ' ' . $query->returned_to_last_name : 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="border-2 border-gray-200">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Remarks:</div>
                                </div>
                                <div class="px-4 py-2">{{ $query->remarks ? $query->remarks : 'No Remarks Added by the Admin.' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Policy -->
                    <div class="mt-4">
                        <h5 class="lg:text-xl md:text-lg sm:text-base font-semibold">Policy</h5>
                    </div>

                    <div class="bg-white p-6 rounded-md shadow-md mt-2 items-center">
                        @if($query->policy_name || $query->policy_content || $query->inclusions)
                        @if($query->policy_name)
                        <h1 class="text-lg font-bold text-left">{{ $query->policy_name }}</h1>
                        @endif

                        @if($query->policy_content)
                        <p class="text-sm lg:text-base md:text-sm mt-4 text-justify">
                            {!! nl2br(e($query->policy_content)) !!}
                        </p>
                        @endif

                        @if($query->inclusions)
                        <p class="text-sm lg:text-base md:text-sm mt-2 text-justify">
                            <b>Inclusions:</b> {!! nl2br(e($query->inclusions)) !!}
                        </p>
                        @endif

                        @if(!$query->policy_name && !$query->policy_content && !$query->inclusions)
                        <p class="sm:text-sm lg:text-base md:text-sm text-center lg:font-semibold text-gray-300">No
                            Policy for this Resource</p>
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
        function showRemarksModal(transactionId, resourceId) {
            document.getElementById('transaction_id').value = transactionId;
            document.getElementById('resource_id').value = resourceId;
            document.getElementById('remarksModal').classList.remove('hidden');
        }

        function closeRemarksModal() {
            document.getElementById('remarksModal').classList.add('hidden');
        }
    </script>

    <script>
        function showRejectedRemarksModal(transactionId, resourceId) {
            document.getElementById('rejectedTransactionId').value = transactionId;
            document.getElementById('rejectedResourceId').value = resourceId;
            document.getElementById('rejectedRemarksModal').classList.remove('hidden');
        }

        function closeRejectedRemarksModal() {
            document.getElementById('rejectedRemarksModal').classList.add('hidden');
        }
    </script>

    <script>
        function showCancelledRemarksModal(transactionId, resourceId) {
            document.getElementById('cancelledTransactionId').value = transactionId;
            document.getElementById('cancelledResourceId').value = resourceId;
            document.getElementById('cancelledRemarksModal').classList.remove('hidden');
        }

        function closeCancelledRemarksModal() {
            document.getElementById('cancelledRemarksModal').classList.add('hidden');
        }
    </script>


</body>

</html>