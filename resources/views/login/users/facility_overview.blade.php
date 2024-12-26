<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Overview {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

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
                <h2 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Facility Overview</h2>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- content -->
            <div class="flex justify-center mt-2">
                <div class="bg-custom-color p-6 rounded-lg mt-4 w-full mx-4 shadow-lg" style="background-color: var(--custom-color);">

                    <!-- Header -->
                    <div class="bg-100 mb-6 border-b-8 border-white lg:text-3xl md:text-2xl sm:text-xl" style="background-color: var(--custom-color);">
                        <div class="flex justify-between items-center">

                            <!-- Left side: Name and Reservation ID -->
                            <div class="flex flex-col">
                                <!-- Name -->
                                <h3 class="font-semibold lg:text-left lg:text-3xl sm:text-xl">{{ $facility->name }} <br> </h3>

                                <!-- Reservation ID -->
                                <h5 class="text-sm font-medium">Reservation ID: <span class="font-normal underline">{{ $facility->id }}</span></h5>
                            </div>

                            <!-- Right side: Status -->
                            <div class="flex justify-end">
                                <h5 class="lg:text-lg sm:text-sm font-medium">
                                    <span class="bg-opacity-20 px-3 py-1 rounded-full">
                                        <div class="text-xs font-medium">Status:</div> <!-- Status label -->
                                        <span class="{{ $facility->status_color }}">{{ $facility->status_state }}</span> <!-- Status state -->
                                    </span>
                                </h5>
                            </div>

                        </div>
                    </div>

                    <!-- Date -->
                    <div class="mt-2">
                        <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Date</h5>
                    </div>

                    <div class="flex justify-center mt-2 ml-2">
                        <div class="bg-white lg:p-4 rounded-lg shadow-md mx-1 mr-3 lg:w-1/2 md:w-1/2 sm:w-1/2 lg:text-xl md:text-lg sm:text-sm font-medium md:p-2 sm:p-2">
                            <p class="lg:text-xl md:text-lg text-200">Start Date:</p>
                            <h5 class="lg:text-xl md:text-md sm:text-sm">{{ $facility->start_datetime }}</h5>
                        </div>
                        <div class="bg-white mx-1 mr-3 lg:p-4 rounded-lg shadow-md lg:w-1/2 md:w-1/2 sm:w-1/2 lg:text-xl md:text-lg sm:text-sm font-medium md:p-2 sm:p-2">
                            <p class="lg:text-xl md:text-lg text-200">End Date:</p>
                            <h5 class="lg:text-xl md:text-md sm:text-sm">{{ $facility->end_datetime }}</h5>
                        </div>
                    </div>

                    <!-- Reservation Details -->
                    <div class="mt-4">
                        <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Reservation Details</h5>
                    </div>

                    <!-- Main Container -->
                    <div class="flex justify-center mt-2 mx-1 mr-3 font-mont">
                        <div class="bg-white p-6 rounded-lg shadow-md w-full ml-2">
                            <!-- User Information -->
                            <div class="border-2 border-gray-200 mb-4">
                                <div class="flex flex-col sm:flex md:flex md:flex-row border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full">User ID:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $facility->user_id }}</div>

                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full">Location:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $facility->location }}</div>
                                </div>

                                <!-- Requested Duration and Department Owner in the Next Row -->
                                <div class="flex flex-col sm:flex md:flex md:flex-row">
                                    <!-- Requested Duration -->
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full">Requested Duration:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">
                                        @php
                                        $startDateTime = \Carbon\Carbon::parse($facility->start_datetime)->format('g:i A');
                                        $endDateTime = \Carbon\Carbon::parse($facility->end_datetime)->format('g:i A');

                                        $totalMinutes = \Carbon\Carbon::parse($facility->start_datetime)->diffInMinutes($facility->end_datetime);

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
                                    <div class="px-4 py-2 text-200 bg-gray-100 w-full">Department Owner:</div>
                                    <div class="px-4 py-2 w-full sm:w-4/5">{{ $facility->department_owner }}</div>
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div class="border-2 border-gray-200">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Purpose:</div>
                                </div>
                                <div class="px-4 py-2">{{ $facility->purpose }}</div>
                            </div>
                        </div>

                    </div>

                    <!-- Approval Details -->
                    <div class="mt-4">
                        <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Approval Details</h5>
                    </div>

                    <div class="flex justify-center mt-2 mx-1 mr-3 font-mont">
                        <div class="bg-white p-6 rounded-lg shadow-md w-full ml-2">

                            <!-- Approved By -->
                            <div class="border-2 border-gray-200">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Approved By:</div>
                                </div>
                                <div class="px-4 py-2">
                                    @if ($facility->approved_by_first_name && $facility->approved_by_last_name)
                                    {{ $facility->approved_by_first_name }} {{ $facility->approved_by_last_name }}
                                    @else
                                    N/A
                                    @endif
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="border-2 border-gray-200">
                                <div class="border-b-2">
                                    <div class="px-4 py-2 text-200 bg-gray-100">Remarks:</div>
                                </div>
                                <div class="px-4 py-2">{{ $facility->remarks ? $transaction->remarks : 'No Remarks Added by the Admin.' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Policy -->
                    <div class="mt-4">
                        <h5 class="lg:text-2xl md:text-xl sm:text-lg font-semibold">Policy</h5>
                    </div>

                    <!-- Policy Content -->
                    <div class="flex justify-center mt-2 mx-1 mr-3">
                        <div class="bg-white p-6 rounded-lg shadow-sm w-full ml-2">
                            @if($facility && $facility->policy_name)
                            <h1 class="text-xl sm:text-lg md:text-lg lg:text-xl font-bold sm:text-center lg:text-left">{{ $facility->policy_name }}</h1>
                            <p class="text-base lg:text-xl md:text-lg sm:text-sm text-justify">
                                {!! nl2br(e($facility->policy_content)) !!}
                            </p>
                            <p class="text-base lg:text-xl md:text-lg sm:text-sm text-justify">
                                <b>Inclusions:</b> {!! nl2br(e($facility->inclusions)) !!}
                            </p>
                            @else
                            <p class="text-base lg:text-xl md:text-lg sm:text-sm text-center font-bold text-gray-400">No Policy for This Resource.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col items-center justify-center mt-6 lg:flex-row lg:space-x-4 lg:space-y-0 sm:flex-col sm:space-x-0 sm:space-y-2">
                        <div>

                            <!-- For Cancellation -->
                            <form id="cancelReservationForm" action="{{ route('user.facility.cancel') }}" method="POST">
                                @csrf
                                <input type="hidden" name="transaction_id" value="{{ $facility->id }}">
                                @if ($facility->status == '2' || $facility->status == '3')
                                <button id="cancelReservationButton"
                                    type="button" class=" font-mont sm:text-sm lg:text-lg lg:w-full text-base bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 hover:shadow-md md:w-32 sm:w-48">Cancel Reservation</button>
                                @endif
                            </form>

                            @if ($facility->status == '6' || $facility->status == '9')
                            <button class="font-mont sm:text-sm w-full text-base bg-orange-600 text-white hover:bg-orange-700 hover:shadow-md py-2 px-4 lg:text-lg rounded-lg sm:w-32" data-bs-toggle="modal" data-bs-target="#feedbackModal">Add Feedback</button>

                            <!-- Feedback Modal -->
                            <form action="{{ route('user.feedback') }}" method="POST">
                                @csrf
                                <div class="modal fade rounded-lg" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-slate-300 text-black font-bold rounded-t-lg">
                                                <h5 class="modal-title" id="feedbackModalLabel">Feedback</h5>
                                                <button type="button" class="focus:outline-none text-lg" data-bs-dismiss="modal" aria-label="Close">X</button>
                                            </div>
                                            <div class="modal-body flex flex-col items-center justify-center">
                                                <p class="sm:text-2xl md:text-2xl lg:text-1xl xl:text-1xl text-sm font-bold lg:text-center text-start mb-0 font-mont">
                                                    {{ $facility->facility_name }} | Facility #{{ $facility->id }}
                                                </p>
                                                <div class="rating-star mt-4 flex flex-row items-center justify-center space-x-2">
                                                    <button type="button" id="star-1" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="1" onclick="toggleStar(this)"></button>
                                                    <button type="button" id="star-2" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="2" onclick="toggleStar(this)"></button>
                                                    <button type="button" id="star-3" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="3" onclick="toggleStar(this)"></button>
                                                    <buZZZtton type="button" id="star-4" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="4" onclick="toggleStar(this)"></button>
                                                        <button type="button" id="star-5" class="bi bi-star cursor-pointer text-4xl text-gray-500 hover:text-yellow-500" value="5" onclick="toggleStar(this)"></button>
                                                </div>
                                                <input type="hidden" name="rating" id="rating" value="">
                                                <textarea class="mt-4 w-full h-24 p-2 border rounded-lg" name="feedback" placeholder="Enter your feedback here..." required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="lg:w-full bg-gray-300 py-2 px-4 rounded-lg text-gray-700 hover:bg-gray-400 md:w-32 sm:w-48" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="lg:w-full bg-green-500 py-2 px-4 rounded-lg text-white hover:bg-green-600 md:w-32 sm:w-48">Submit Feedback</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                        <a href="{{ route('dashboard') }}">
                            <button type="button" class="font-mont sm:text-sm lg:text-lg lg:w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 hover:shadow-md md:w-32 sm:w-48">Back to Dashboard</button>
                        </a>
                    </div>

                </div>
            </div>

            <script>
                function toggleStar(star) {
                    const rating = document.getElementById('rating');
                    const stars = document.querySelectorAll('.rating-star button');
                    const value = star.getAttribute('value');
                    rating.value = value;

                    stars.forEach(star => {
                        if (parseInt(star.getAttribute('value')) <= value) {
                            star.classList.add('text-yellow-500');
                            star.classList.remove('text-gray-500');
                        } else {
                            star.classList.add('text-gray-500');
                            star.classList.remove('text-yellow-500');
                        }
                    });
                }
            </script>

        </div>
    </div>
</body>

</html>

<script>
    document.getElementById('cancelReservationButton').addEventListener('click', function() {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            confirmButtonText: "Yes, cancel my reservation.",
            confirmButtonColor: "#DD6B55",
            showCancelButton: true,
            cancelButtonColor: "#C1C1C1"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancelReservationForm').submit();
            }
        });
    });
</script>

@if(Session::has('message'))
<script>
    swal(" Oops...", "{{ Session::get('message') }}", 'info', {
        button: "OK",
    });
</script>
@endif



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