<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />


    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Bootstrap Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.users.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h2 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Request Overview</h2>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- content -->
            <div class="flex justify-center mt-2 items-center">
                <div class="bg-custom-color p-6 rounded-lg mt-4 w-full mx-4 shadow-lg" style="background-color: var(--custom-color);">
                    <div id="printable-section">
                        <!-- Header -->
                        <div class="bg-100 flex justify-between mb-6 border-b-8 border-white lg:text-3xl md:text-2xl sm:text-xl" style="background-color: var(--custom-color);">
                            <div class="flex flex-col lg:justify-start">

                                <!-- name -->
                                <h3 class="font-semibold lg:text-left lg:text-3xl sm:text-xl">{{ $transaction->name }} <br> </h3>

                                <!-- transaction # -->
                                <h5 class="text-sm font-medium">Transaction ID: <span class="font-normal" style="text-decoration:underline;"> {{ $transaction->transaction_id }} </span></h5>

                                <!-- serial number -->
                                <h5 class="text-sm font-medium">Resource Serial Number: <span class="font-normal" style="text-decoration:underline;"> {{$transaction->serial_number}} </span></h5>

                            </div>

                            <div class="relative group mb-3 flex items-center space-x-4">

                                <!-- Status -->
                                <h5 class="lg:text-lg sm:text-sm font-medium flex justify-end">
                                    <span class="bg-opacity-20 px-3 py-1 rounded-full">
                                        <div class="text-xs font-medium ">Status:</div> <!-- Status label -->
                                        <span class="{{ $transaction->status_color }}"> {{ $transaction->status_state }} </span> <!-- Status state -->
                                    </span>
                                </h5>


                                <!-- Print button with icon -->
                                <button onclick="printDiv('printable-section')" class="font-mont text-lg bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 hover:shadow-md">
                                    <i class="fas fa-print"></i>
                                </button>

                            </div>
                        </div>


                        <!-- Date -->
                        <div>
                            <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Date</h5>
                        </div>

                        <div class="flex justify-center mt-2 ml-2 font-mont">
                            <div class="bg-white lg:p-3 rounded-lg shadow-md mx-1 mr-3 lg:w-1/2 md:w-1/2 sm:w-1/2 lg:text-xl md:text-lg sm:text-sm font-medium md:p-2 sm:p-2">
                                <p class="lg:text-lg md:text-lg text-200">Pick-Up Date:</p>
                                <h5 class="lg:text-lg md:text-md sm:text-sm font-mont">{{ $transaction->pickup_datetime }}</h5>
                            </div>
                            <div class="bg-white mx-1 mr-3 lg:p-3 rounded-lg shadow-md lg:w-1/2 md:w-1/2 sm:w-1/2 lg:text-xl md:text-lg sm:text-sm font-medium md:p-2 sm:p-2">
                                <p class="lg:text-lg md:text-lg text-200">Return Date:</p>
                                <h5 class="lg:text-lg md:text-md sm:text-sm font-mont">{{ $transaction->return_datetime }}</h5>
                            </div>
                        </div>

                        <!-- Reservation Details -->
                        <div class="mt-4">
                            <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Reservation Details</h5>
                        </div>

                        <!-- Main Container -->
                        <div class="flex justify-center mt-2 mx-1 mr-3 font-mont">
                            <div class="bg-white p-3 rounded-lg shadow-md w-full ml-2">
                                <!-- User Information -->
                                <div class="border-2 border-gray-200 mb-3">
                                    <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">User ID:</div>
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $transaction->user_id }}</div>
                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Name:</div>
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $transaction->last_name }}, {{ $transaction->first_name }}</div>
                                    </div>
                                    <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Resource Type:</div>
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $transaction->resource_type }}</div>
                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Department Owner:</div>
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $department_owner }}</div>
                                    </div>
                                    <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Professor:</div>
                                        @if ($professor->first_name == 'N/A' && $professor->last_name == 'N/A')
                                        <div class="px-4 py-2 w-full sm:w-4/5">N/A</div>
                                        @else
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $professor->last_name }}, {{ $professor->first_name }}</div>
                                        @endif

                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Subject:</div>
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $transaction->subject ?? 'N/A' }}</div>
                                    </div>
                                    <div class="flex flex-col sm:flex md:flex md:flex-row ">
                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Section:</div>
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $transaction->section ?? 'N/A' }}</div>
                                        <div class="px-4 py-2 text-200 bg-gray-100 w-full sm:w-full">Course Schedule:</div>
                                        <div class="px-4 py-2 w-full sm:w-4/5">{{ $transaction->schedule ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <!-- Purpose -->
                                <div class="border-2 border-gray-200 mb-3">
                                    <div class="border-b-2">
                                        <div class="px-4 py-2 text-200 bg-gray-100">Purpose:</div>
                                    </div>
                                    <div class="px-4 py-2">{{ $transaction->purpose }}</div>
                                </div>

                                <!-- Group Members -->
                                <div class="border-2 border-gray-200">
                                    <div class="border-b-2">
                                        <div class="px-4 py-2 text-200 bg-gray-100">Group Members:</div>
                                    </div>
                                    <div class="px-4 py-2">{{ $transaction->group_members ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Details -->
                        <div class="mt-4">
                            <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Approval Details</h5>
                        </div>

                        <div class="flex justify-center mt-2 mx-1 mr-3 font-mont">
                            <div class="bg-white p-6 rounded-lg shadow-md w-full ml-2">
                                <!-- Approved By and Returned To -->
                                <div class="border-2 border-gray-200 mb-3">
                                    <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                        <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Approved By:</div>
                                        <div class="px-4 py-2 lg:w-1/4 sm:w-4/5">{{ $transaction->approved_by_first_name ? $transaction->approved_by_first_name . ' ' . $transaction->approved_by_last_name : 'N/A' }}</div>
                                        <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Released By:</div>
                                        <div class="px-4 py-2 lg:w-1/4 sm:w-4/5">
                                            {{ $transaction->released_by_first_name && $transaction->released_by_last_name ? $transaction->released_by_first_name . ' ' . $transaction->released_by_last_name : 'N/A' }}
                                        </div>

                                    </div>
                                    <div class="flex flex-col sm:flex md:flex md:flex-row">
                                        <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Noted By:</div>
                                        <div class="px-4 py-2 lg:w-1/4 sm:w-4/5">{{ $transaction->noted_by_first_name ? $transaction->noted_by_first_name . ' ' . $transaction->noted_by_last_name : 'N/A' }}</div>
                                        <div class="px-4 py-2 text-200 bg-gray-100 lg:w-1/4 sm:w-full">Returned To:</div>
                                        <div class="px-4 py-2 lg:w-1/4 sm:w-4/5">{{ $transaction->returned_to_first_name ? $transaction->returned_to_first_name . ' ' . $transaction->returned_to_last_name : 'N/A' }}</div>
                                    </div>
                                </div>

                                <!-- Remarks -->
                                <div class="border-2 border-gray-200">
                                    <div class="border-b-2">
                                        <div class="px-4 py-2 text-200 bg-gray-100">Remarks:</div>
                                    </div>
                                    <div class="px-4 py-2">{{ $transaction->remarks ? $transaction->remarks : 'No Remarks Added by the Admin.' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($transaction->status_state == 'For Replacement')
                        <div class="hidden-print flex lg:flex-row md:flex-row sm:flex-col lg:justify-between md:justify-between sm:justify-center p-6 mt-8 rounded-md items-center mx-3">
                            <!-- Left Signature Area -->
                            <div class="flex flex-col items-center">
                                <div class="border-b-2 border-black w-72 ">
                                    <span class="flex flex-col text-center font-mont uppercase font-bold">
                                        {{ $transaction->returned_to_first_name ? $transaction->returned_to_first_name . ' ' . $transaction->returned_to_last_name : 'N/A' }}
                                    </span>
                                </div>
                                <label class="mt-2 text-sm">Certified By</label>
                            </div>

                            <!-- Right Signature Area -->
                            <div class="flex flex-col items-center ">
                                <div class="border-b-2 border-black w-72">
                                    <span class="flex flex-col text-center font-mont uppercase font-bold">
                                        {{ $transaction->first_name }} {{ $transaction->last_name }}
                                    </span>
                                </div>
                                <label class="mt-2 text-sm">Student</label>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Policy Section -->
                    <div class="mt-4 policy-section">
                        <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Policy</h5>
                    </div>

                    <div class="bg-white p-6 rounded-md shadow-md mt-2 items-center mx-3 policy-section">
                        @if($transaction->policy_name || $transaction->policy_content || $transaction->inclusions)
                        @if($transaction->policy_name)
                        <h1 class="text-lg font-bold text-left">{{ $transaction->policy_name }}</h1>
                        @endif

                        @if($transaction->policy_content)
                        <p class="text-sm lg:text-base md:text-sm mt-4 text-justify">
                            {!! nl2br(e($transaction->policy_content)) !!}
                        </p>
                        @endif

                        @if($transaction->inclusions)
                        <p class="text-sm lg:text-base md:text-sm mt-2 text-justify">
                            <b>Inclusions:</b> {!! nl2br(e($transaction->inclusions)) !!}
                        </p>
                        @endif
                        @else
                        <p class="sm:text-sm lg:text-base md:text-sm text-center lg:font-semibold text-gray-300">No Policy for this Resource</p>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col items-center justify-center mt-6 lg:flex-row lg:space-x-4 lg:space-y-0 sm:flex-col sm:space-x-0 sm:space-y-2">
                        <div>
                            <!-- For Cancellation -->
                            <form action="{{ route('user.cancel') }}" method="POST">
                                @csrf
                                <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_id }}">
                                @if ($transaction->status == '2' || $transaction->status == '3')
                                <button type="submit" class="font-mont lg:w-full sm:text-sm lg:text-lg bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 hover:shadow-md md:w-32 sm:w-48">Cancel Reservation</button>
                                @endif
                            </form>

                            @if ($transaction->status == '6' || $transaction->status == '9')
                            <button
                                class="font-mont w-full sm:text-sm lg:text-lg py-2 px-4 rounded-lg hover:shadow-md 
                                @if ($feedbackExists) bg-orange-600 text-white hover:bg-orange-700 cursor-not-allowed @else bg-orange-600 text-white hover:bg-red-700 @endif"
                                data-bs-toggle="modal"
                                data-bs-target="#feedbackModal"
                                @if ($feedbackExists) disabled @endif>
                                Add Feedback
                            </button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <form action="{{ route('user.feedback') }}" method="POST">
        @csrf
        <div class="modal fade rounded-lg" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-slate-300 text-black font-bold rounded-t-lg font-mont">
                        <h5 class="modal-title" id="feedbackModalLabel">Feedback</h5>
                        <button type="button" class="font-mont focus:outline-none text-lg" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body flex flex-col items-center justify-center">
                        <p class="sm:text-2xl md:text-2xl lg:text-1xl xl:text-1xl text-sm font-bold lg:text-center text-start mb-0 font-mont">
                            {{ $transaction->name }} | Transaction #{{ $transaction->transaction_id }}
                        </p>
                        <div class="rating-star mt-4 flex flex-row items-center justify-center space-x-2">
                            <button type="button" id="star-1" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="1" onclick="toggleStar(this)"></button>
                            <button type="button" id="star-2" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="2" onclick="toggleStar(this)"></button>
                            <button type="button" id="star-3" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="3" onclick="toggleStar(this)"></button>
                            <button type="button" id="star-4" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="4" onclick="toggleStar(this)"></button>
                            <button type="button" id="star-5" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="5" onclick="toggleStar(this)"></button>
                        </div>
                        <p class="mt-2 font-mont">Rate this Resource</p>
                        <div class="input-group mt-1">
                            <textarea id="input-fields" class="w-full p-3 border-2 border-gray-300 rounded-lg font-mont" name="comment" placeholder="This is Optional but we Appreciate if you would Like to Leave a Feedback, Thank you!" value="{{ old('comment') }}"></textarea>
                        </div>
                        <input type="hidden" name="rating" id="rating-value">
                        <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_id }}">
                        <input type="hidden" name="resource_id" value="{{ $transaction->resource_id }}">
                    </div>
                    <div class="modal-footer p-2">
                        <button type="submit" class="font-mont lg:w-full bg-green-500 py-2 px-4 rounded-lg text-white hover:bg-green-600 md:w-32 sm:w-48 sm:text-sm lg:text-lg">Submit Feedback</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>

@if(Session::has('message'))
<script>
    swal("Oops...", "{{ Session::get('message') }}", 'info', {
        button: "OK",
    });
</script>
@endif

<div class="hidden-print">
    <!-- Print button outside content -->
    <button onclick="printDiv('printable-section')" class="font-mont bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 hover:shadow-md">
        Print
    </button>
</div>

<!-- JavaScript to print only the specified content -->
<script>
    function printDiv(divId) {
        var content = document.getElementById(divId).innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = content;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>

<!-- CSS to hide policy section during print -->
<style>
    .hidden-print {
        display: none;
    }

    /* When printing, make these elements visible */
    @media print {
        .hidden-print {
            display: flex !important;
            flex-direction: row;
        }

        /* Hide the print button during printing */
        button {
            display: none;
        }

        /* You can also hide other UI elements that are unnecessary in print view */
        .no-print {
            display: none !important;
        }

        #printable-section {
            transform: scale(0.9);
            /* Scale down the entire document */
            transform-origin: top left;
        }
    }
</style>


<!-- Rating Star JavaScritpt -->
<script>
    function toggleStar(element) {
        event.preventDefault();
        const stars = document.querySelectorAll('.rating-star button');
        const value = parseInt(element.value);

        // Update the hidden input with the rating value
        document.getElementById('rating-value').value = value;

        // Fill stars up to the clicked one
        stars.forEach(star => {
            const starValue = parseInt(star.value);
            if (starValue <= value) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill', 'text-yellow-500');
            } else {
                star.classList.remove('bi-star-fill', 'text-yellow-500');
                star.classList.add('bi-star');
            }
        });
    }
</script>