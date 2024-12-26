<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- jQuery (only include once) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap Date Picker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Bootstrap DateTimePicker JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Bootstrap Date Picker JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

    <!-- Bootstrap JS for Modal -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.users.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h2 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Reserve Resources</h2>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Form Content -->
            <form method="POST" action="{{route('facility.request')}}">
                @csrf
                <div class="flex flex-col p-2 rounded-lg shadow-sm mx-4 mt-8" style="background-color: var(--custom-color);">

                    <div class="flex flex-col lg:flex-row lg:items-start w-full items-center">
                        <h3 class="font-semibold ml-3 mt-3 lg:text-2xl sm:text-xl">Facilities</h3>
                    </div>

                    <div class="flex flex-col xl:flex-row w-full">

                        <!-- Left Side -->
                        <div class="flex flex-col p-2 w-full xl:w-8/12 items-center lg:items-start">
                            <div class="flex flex-col w-full">
                                <div class="p-1 w-full mt-2">
                                    <label class="text-black">Choose Item <span class="text-red-500">*</span></label>
                                    <select id="dropdown-button" name="facilities" class="bg-white w-full p-3 border border-gray-300 rounded-lg font-mont">
                                        <option value="" selected disabled>Select Facility</option>
                                        @foreach ($facilities as $facility_id => $facility)
                                        @if ($facility['is_available'] = 1 && !in_array($facility['status'], [12, 7]))
                                        <option value="{{ $facility_id }}|{{ $facility['facility_name'] }}|{{ $facility['image'] }}">{{ $facility['facility_name'] }}</option>
                                        @else
                                        <option value="" disabled>{{ $facility['facility_name'] }} (Not Available)</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="p-1 w-full mt-2">
                                    <label class="text-black">Purpose <span class="text-red-500">*</span></label>
                                    <textarea id="input-fields" name="purpose" class="w-full p-3 border border-gray-300 rounded-lg font-mont" placeholder="State your Purpose">{{ old('purpose') }}</textarea>
                                </div>

                                <!-- Date Time -->
                                <div class="flex flex-col p-1 lg:w-full sm:w-full lg:items-start lg:justify-center sm:justify-center sm:items-center mt-0">
                                    <label class="text-black">Start Datetime <span class="text-red-500">*</span></label>
                                    <input class="w-full p-3 border border-gray-300 rounded-lg font-mont" type="datetime-local" id="datetimepicker" name="start_datetime" value="{{ old('start_datetime') }}" />
                                    @error('start_datetime')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col p-1 lg:w-full sm:w-full lg:items-start lg:justify-center sm:justify-center sm:items-center mt-2">
                                    <label class="text-black">End Datetime<span class="text-red-500">*</span></label>
                                    <input class="w-full p-3 border border-gray-300 rounded-lg font-mont" type="datetime-local" id="datetimepicker" name="end_datetime" value="{{ old('end_datetime') }}" />
                                    @error('end_datetime')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Side (Image) -->
                        <div class="flex flex-col p-2 w-full xl:w-4/12 justify-center items-center relative">
                            <!-- Image Container -->
                            <div class="relative flex justify-center items-center flex-col">
                                <!-- Dark Opacity Layer -->
                                <div class="absolute inset-0 bg-black opacity-20 rounded-lg"></div>
                                <!-- Image -->
                                <img class="w-full max-h-64 rounded-b-lg" id="item-image" src="{{ empty($imageSrc) ? asset('storage/assets/default_resource.png') : $imageSrc }}" alt="Item Image">
                                <!-- Select Item -->
                                <div class="absolute bottom-0 left-0 w-full h-10 bg-white flex justify-center items-center">
                                    <p id="selected-item" class="font-medium text-black mt-3">Select Item</p>
                                </div>
                            </div>

                            <!-- Reserved Dates Section -->
                            <div class="mt-4 max-h-48 overflow-y-auto">
                                <h3 class="font-semibold text-lg text-center">Reserved Dates</h3>
                                <div id="reserved-dates" class="mt-2 text-sm text-gray-700">
                                    <p>No Facility Selected.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section (Buttons) -->
                    <div class="flex lg:justify-between lg:flex-row w-full p-4 mt-3 font-mont sm:flex-col sm:items-center">
                        <div class="flex w-1/2 lg:justify-start align-middle sm:justify-center">
                            <a href="{{route('reserve.resource')}}"><button type="button" class="font-mont text-white h-auto w-32 bg-gray-500 hover:bg-gray-700 lg:text-lg rounded-lg text-sm px-4 py-2">Back</button></a>
                        </div>
                        <div class="flex w-1/2 lg:justify-end sm:mt-2 sm:justify-center">
                            <!-- Button to Trigger Policy Modal and Request -->
                            <button class="font-mont text-white h-auto w-32 bg-300 hover:bg-blue-700 rounded-lg lg:text-lg text-sm px-4 py-2" type="button" data-toggle="modal" data-target="#policyModal">Request</button>
                        </div>
                    </div>

                    <!-- Policy Content -->
                    <div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title font-semibold" id="policyModalLabel">Terms and Conditions</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h3 id="policy-title"></h3>
                                    <p id="policy-content"></p>
                                    <div id="inclusions"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="text-white w-1/3 h-9 bg-300 font-medium rounded-lg text-sm px-4 py-2 me-2 mb-2 disabled:bg-blue-300 disabled:opacity-50" id="agree-button" disabled>I Agree</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</body>

</html>

<style>
    html {
        scroll-behavior: smooth;
    }

    body {
        background-image: url('{{ asset("$cms[url_bg_image]") }}');
    }

    .btn {
        transition: 0.2s ease;
    }
</style>

<script>
    $(document).ready(function() {

        $('#agree-button').prop('disabled', true);

        // Setting minimum date for start and end datetime inputs
        var startDateTimeInput = document.getElementsByName("start_datetime")[0];
        var endDateTimeInput = document.getElementsByName("end_datetime")[0];

        var today = new Date();
        startDateTimeInput.min = today.toISOString().slice(0, 16);

        startDateTimeInput.addEventListener("change", function() {
            var selectedStartDate = new Date(this.value);
            endDateTimeInput.min = selectedStartDate.toISOString().slice(0, 16);
        });

        $('#policy-content').html('Select a Facility First');

        // Dropdown change event for updating image, modal, and reservation content
        $('#dropdown-button').change(function() {
            var selectedOption = $(this).find('option:selected');
            var resourceId = selectedOption.val();

            if (resourceId) {
                // Enable the agree button
                $('#agree-button').prop('disabled', false);

                // Extract resource details
                var parts = resourceId.split('|');
                var id = parts[0]; // Facility ID
                var imagePath = parts[2]; // Image path

                // Update selected item details
                var imageSrc = "{{ asset('') }}" + imagePath;
                var selectedItemText = selectedOption.text();
                $('#selected-item').text(selectedItemText);
                $('.image-container').show();
                $('#item-image').attr('src', imageSrc);

                // Fetch and display policy information
                $.ajax({
                    url: "{{ route('facility.form') }}",
                    type: "GET",
                    data: {
                        facility_id: id
                    },
                    success: function(response) {
                        // Display policy details in the modal
                        $('#policy-title').html(response.policy_name);
                        $('#policy-content').html(response.policy_content);
                        $('#inclusions').html('<b>Inclusions: </b>' + response.inclusions);
                        $('#policyModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Policy AJAX Error:', xhr.responseText);
                    }
                });

                // Clear and fetch reservations
                $('#reserved-dates').empty();
                $.ajax({
                    url: "{{ route('reserved.facilities') }}",
                    type: "GET",
                    data: {
                        resource_id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.reservations.length > 0) {
                                // Display reservations
                                response.reservations.forEach(function(reservation) {
                                    $('#reserved-dates').append(`
                                <p class="mb-2">
                                    <strong>Start:</strong> ${reservation.start}<br>
                                    <strong>End:</strong> ${reservation.end}<br>
                                </p>
                            `);
                                });
                            } else {
                                $('#reserved-dates').append('<p class="text-center">No reservations for this facility.</p>');
                            }
                        } else {
                            console.error('Error fetching reservations:', response.message);
                            $('#reserved-dates').append('<p>Error fetching reservations.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Reservations AJAX Error:', error);
                        $('#reserved-dates').append('<p>Failed to load reservations. Please try again.</p>');
                    }
                });
            } else {
                // No resource selected
                $('#agree-button').prop('disabled', true);
                $('#policy-content').html('<p class="policy-item">Select a Facility First</p>');
                $('#policyModal').modal('show');
                $('#reserved-dates').empty().append('<p>No Facility Selected.</p>');
            }
        });

    });
</script>

<!-- Submit Button -->
<script>
    document.getElementById('agree-button').addEventListener('click', function() {
        // Dismiss the modal
        $('#policyModal').modal('hide');
        // Submit the form
        document.querySelector('form').submit();
    });
</script>

<!-- Request Successful -->
@if(Session::has('request_fail'))
<script>
    swal("Invalid", "{{Session::get('request_fail')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif

<!-- Quantity Error -->
@if(Session::has('unavailable'))
<script>
    swal("Invalid", "{{Session::get('unavailable')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif

<!-- General Error -->
@if(Session::has('error'))
<script>
    swal("Invalid", "{{Session::get('error')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif

<?php if (session('conflictList')): ?>
    <script>
        let conflictList = <?= json_encode(session('conflictList')) ?>;
        let formattedList = conflictList.map(dateRange => `<li>${dateRange}</li>`).join('');
        Swal.fire({
            icon: 'error',
            title: 'Conflicting Reservations Detected',
            html: `<p>The following time slots are already reserved for this facility:</p><br><p>${formattedList}</p>`,
            confirmButtonText: 'Okay'
        });
    </script>
<?php endif; ?>