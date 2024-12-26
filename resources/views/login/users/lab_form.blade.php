<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Bootstrap JS Query -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Date Time -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

    <!-- Bootstrap DateTimePicker JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Multiple Selection -->
    <link href="{{ asset('css/multiSelectTag.css') }}" rel="stylesheet">
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
            <form method="POST" action="{{ route('lab.request') }}">
                @csrf
                <div class="flex flex-col lg:flex-row p-2 rounded-lg shadow-sm mx-4 mt-8" style="background-color: var(--custom-color);">

                    <!-- Left Side (Form Section) -->
                    <div class="flex-grow lg:w-8/12 p-2">

                        <!-- Header -->
                        <div class="flex flex-col lg:flex-row lg:items-start w-full items-center">
                            <h3 class="font-semibold ml-1 mt-3 lg:text-2xl sm:text-xl">Laboratory</h3>
                        </div>

                        <!-- INFO  -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">

                            <!-- Subject -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black">Subject <span class="text-red-500">*</span></label>
                                <input id="input-fields" name="subject" class="w-full p-3 border border-gray-300 rounded-lg font-mont" placeholder="Course Subject" value="{{ old('subject') }}">
                            </div>

                            <!-- Section -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black">Course Section <span class="text-red-500">*</span></label>
                                <input type="text" id="section" name="section" class="w-full p-3 border border-gray-300 rounded-lg font-mont" placeholder="e.g. 29123" value="{{ old('section') }}">
                            </div>

                            <!-- Class Schedule -->
                            <div class="p-1 w-full {{ $userData['position'] == 'Student' ? '' : 'lg:col-span-2' }} align-middle">
                                <label class="text-black">Class Schedule <span class="text-red-500">*</span></label>
                                <div class="flex gap-2">
                                    <!-- Day Dropdown -->
                                    <select id="day" class="w-2/3 p-3 border border-gray-300 rounded-lg font-mont">
                                        <option value="" disabled {{ old('day') ? '' : 'selected' }}>Select Day</option>
                                        <option value="Monday" {{ old('day') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                        <option value="Tuesday" {{ old('day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                        <option value="Wednesday" {{ old('day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                        <option value="Thursday" {{ old('day') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                        <option value="Friday" {{ old('day') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                        <option value="Saturday" {{ old('day') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                        <option value="Sunday" {{ old('day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                    </select>

                                    <!-- Time Range Input -->
                                    <input id="time-range"
                                        type="text"
                                        class="w-1/3 p-3 border border-gray-300 rounded-lg font-mont"
                                        name="time_range"
                                        value="{{ old('time_range') }}"
                                        placeholder="e.g. 14:30 - 15:30 or 14-15">
                                </div>
                                <input id="schedule" type="hidden" name="schedule" value="{{ old('schedule') }}">
                            </div>

                            <!-- Professor -->
                            @if($userData['position'] == 'Student')
                            <div class="p-1 relative w-full">
                                <label class="text-black">Professor <span class="text-red-500">*</span></label>
                                <!-- Dropdown button -->
                                <button
                                    type="button"
                                    onclick="toggleDropdown(event)"
                                    class="w-full bg-gray-100 text-gray-800 font-medium p-3 rounded-lg border border-gray-300 flex justify-between items-center dropbtn">
                                    @if(old('professor'))
                                    {{ $professor->firstWhere('user_id', old('professor'))->first_name ?? 'Select Professor' }}
                                    {{ $professor->firstWhere('user_id', old('professor'))->last_name ?? '' }}
                                    @else
                                    Select Professor
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Dropdown content -->
                                <div id="dropdownContent" class="absolute z-10 bg-white w-full mt-1 rounded-lg shadow-lg border border-gray-300 hidden">
                                    <!-- Search bar -->
                                    <input
                                        type="text"
                                        id="searchInput"
                                        onkeyup="filterDropdown()"
                                        placeholder="Search professor..."
                                        class="w-full px-4 py-2 text-sm border-b border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-300" />

                                    <!-- Dynamic dropdown items -->
                                    <div class="max-h-48 overflow-y-auto">
                                        @foreach ($professor as $prof)
                                        <a
                                            href="#"
                                            onclick="selectProfessor('{{ $prof->user_id }}', '{{ $prof->first_name }} {{ $prof->last_name }}', event)"
                                            class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                                            data-value="{{ $prof->user_id }}">
                                            {{ $prof->first_name }} {{ $prof->last_name }}
                                        </a>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Hidden input to store the selected professor's ID -->
                                <input type="hidden" name="professor" id="selectedProfessor" value="{{ old('professor') }}" />
                            </div>
                            @endif

                        </div>

                        <!-- Rest of the Form with Single Column -->
                        <div class="flex flex-col w-full mt-3">
                            <!-- Members -->
                            <div class="p-1 w-full">
                                <label class="text-black">Members</label>
                                <textarea id="input-fields" name="group_members" class="w-full p-3 border border-gray-300 rounded-lg font-mont" placeholder="Format: (Name - Student Number), (Name - Student Number), (Name - Student Number)">{{ old('group_members') }}</textarea>
                            </div>

                            <!-- Choose Item -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black">Choose Item <span class="text-red-500">*</span></label>
                                <select class="shadow-lg" id="dropdown-button" name="items[]" multiple>
                                    @foreach ($resources as $resource_id => $resource)
                                    @if ($resource['status'] == 1)
                                    <option value="{{ $resource_id }}|{{ $resource['resource_name'] }}|{{ $resource['image'] }}">{{ $resource['resource_name'] }} ({{$resource['serial_number']}})</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>


                            <!-- Purpose -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black">Purpose <span class="text-red-500">*</span></label>
                                <textarea id="input-fields" name="purpose" class="w-full p-3 border border-gray-300 rounded-lg font-mont" placeholder="This Includes: (Group #), (Experiment #), or (Experiment Title)">{{ old('purpose') }}</textarea>
                            </div>

                            <!-- Pickup and Return Datetime -->
                            <div class="flex flex-col p-1 lg:w-full sm:w-full lg:items-start lg:justify-center sm:justify-center sm:items-center mt-2">
                                <label class="text-black">Pickup Datetime <span class="text-red-500">*</span></label>
                                <input name="pickup_datetime" type="datetime-local" class="w-full p-3 border border-gray-300 rounded-lg font-mont" placeholder="Select date and time" value="{{ old('pickup_datetime') }}">
                            </div>

                            <div class="flex flex-col p-1 lg:w-full sm:w-full lg:items-start lg:justify-center sm:justify-center sm:items-center mt-2">
                                <label class="text-black">Return Datetime <span class="text-red-500">*</span></label>
                                <input name="return_datetime" type="datetime-local" class="w-full p-3 border border-gray-300 rounded-lg font-mont" placeholder="Select date and time" value="{{ old('return_datetime') }}">
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
                            <img class="max-h-64 max-w-64 min-w-64 w-full rounded-b-lg" id="item-image" src="{{ empty($imageSrc) ? asset('storage/assets/default_resource.png') : $imageSrc }}" alt="Item Image">
                            <!-- Select Item -->
                            <div class="absolute bottom-0 left-0 w-full h-10 bg-white flex justify-center items-center">
                                <p id="selected-item" class="font-medium text-black mt-3">Select Item</p>
                            </div>
                        </div>

                        <!-- Reserved Dates Section -->
                        <div class="mt-4 max-h-48 overflow-y-auto">
                            <h3 class="font-semibold text-lg text-center">Reserved Dates</h3>
                            <div id="reserved-dates" class="mt-2 text-sm text-gray-700">
                                <p>No Resource Selected.</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Buttons (Back & Request) -->
                <div class="flex justify-between mx-4 -mt-2 shadow-sm" style="background-color: var(--custom-color); padding: 1rem; border-radius: 0.375rem;">
                    <!-- Back Button -->
                    <a href="{{route('reserve.resource')}}" class="w-1/2 mr-2">
                        <button type="button" class="font-mont text-white h-auto min-w-32 max-w-32 bg-gray-500 hover:bg-gray-700 lg:text-lg rounded-lg text-sm px-4 py-2">Back</button>
                    </a>
                    <!-- Request Button -->
                    <div class="flex justify-end w-1/2 ml-2">
                        <button class="font-mont text-white h-auto w-32 bg-blue-600 hover:bg-blue-700 lg:text-lg rounded-lg text-sm px-4 py-2" type="button" data-toggle="modal" data-target="#policyModal">Request</button>
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
                                <div id="policy-info-container"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="text-white w-1/3 h-9 bg-300 font-medium rounded-lg text-sm px-4 py-2 me-2 mb-2 disabled:bg-blue-300 disabled:opacity-50 font-mont" id="agree-button" disabled>I Agree</button>
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

<!-- Course Section -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sectionInput = document.getElementById('section');

        sectionInput.addEventListener('input', (event) => {
            const value = event.target.value;
            event.target.value = value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        });
    });
</script>


<!-- Schedule -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const daySelect = document.getElementById('day');
        const timeRange = document.getElementById('time-range');
        const scheduleInput = document.getElementById('schedule');

        // Allow only numbers, dashes, and colons for the time range input
        timeRange.addEventListener('input', (event) => {
            const value = event.target.value;
            event.target.value = value.replace(/[^0-9:\-]/g, ''); // Updated regex
        });

        function updateScheduleValue() {
            if (daySelect.value && timeRange.value) {
                scheduleInput.value = `${daySelect.value} ${timeRange.value}`;
            } else {
                scheduleInput.value = '';
            }
        }

        // Update schedule value on input change
        [daySelect, timeRange].forEach(el => {
            el.addEventListener('change', updateScheduleValue);
            el.addEventListener('input', updateScheduleValue);
        });

        // Initialize the schedule input with old value if present
        const oldSchedule = "{{ old('schedule') }}";
        if (oldSchedule) {
            const regex = /^(\w+)\s+([\d:\-]+)$/; // Updated regex to include colons
            const match = oldSchedule.match(regex);

            if (match) {
                const [, day, time] = match; // Destructure day and time from match groups
                daySelect.value = day || '';
                timeRange.value = time || '';
            }
        }
    });
</script>

<!-- Dropdown Professor List -->
<script>
    // Toggle dropdown visibility
    function toggleDropdown(event) {
        event.stopPropagation(); // Stop event from bubbling to window click
        document.getElementById("dropdownContent").classList.toggle("hidden");
    }

    // Filter dropdown items
    function filterDropdown() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const items = document.querySelectorAll("#dropdownContent a");

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(input) ? "" : "none";
        });
    }

    // Select professor, update hidden input, and display name
    function selectProfessor(id, name, event) {
        event.preventDefault(); // Prevent link navigation

        // Update dropdown button text
        const dropdownButton = document.querySelector(".dropbtn");
        dropdownButton.innerHTML = name + `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    `;

        // Update hidden input value
        const hiddenInput = document.getElementById("selectedProfessor");
        hiddenInput.value = id;

        // Close dropdown
        document.getElementById("dropdownContent").classList.add("hidden");
    }

    document.addEventListener("click", (e) => {
        const dropdown = document.getElementById("dropdownContent");
        const dropdownTrigger = document.querySelector(".relative"); // Replace with your actual trigger selector

        if (!dropdown || !dropdownTrigger) return; // Ensure both elements exist

        // Check if the click is inside the dropdown or its trigger
        const isClickInsideDropdown = dropdown.contains(e.target);
        const isClickInsideTrigger = dropdownTrigger.contains(e.target);

        if (!isClickInsideDropdown && !isClickInsideTrigger) {
            // Click is outside both dropdown and trigger; hide the dropdown
            dropdown.classList.add("hidden");
        }
    });

    // Optional: Add event to open the dropdown
    document.querySelector(".relative").addEventListener("click", (e) => {
        e.stopPropagation(); // Prevent click from closing immediately
        const dropdown = document.getElementById("dropdownContent");
        dropdown.classList.toggle("hidden"); // Toggle visibility
    });
</script>

<!-- Datetime Script -->
<script>
    var startDateTimeInput = document.getElementsByName("pickup_datetime")[0];
    var endDateTimeInput = document.getElementsByName("return_datetime")[0];

    var today = new Date();

    startDateTimeInput.min = today.toISOString().slice(0, 16);

    startDateTimeInput.addEventListener("change", function() {
        var selectedStartDate = new Date(this.value);

        endDateTimeInput.min = selectedStartDate.toISOString().slice(0, 16);
    });
</script>


<!-- Include the MultiSelectTag library -->
<script src="{{ asset('js/multiSelectTag.js') }}"></script>

<!-- Script for handling the dropdown change -->
<script>
    $(document).ready(function() {

        $('#agree-button').prop('disabled', true);

        // Initialize the dropdown with MultiSelectTag
        new MultiSelectTag('dropdown-button', {
            rounded: true,
            placeholder: 'Search Equipment Resources',
            tagColor: {
                textColor: '#327b2c',
                borderColor: '#92e681',
                bgColor: '#eaffe6',
            },
            onChange: function(values) {
                handleDropdownChange(values);

                $('#reserved-dates').empty();

                // Check if a resource is selected
                if (values.length === 0) {
                    $('#reserved-dates').append('<p>No resource selected.</p>');
                    return;
                }

                // Extract the latest selected resource ID
                let resourceId = values[values.length - 1].value;

                // Fetch reservations via AJAX
                $.ajax({
                    url: "{{ route('reserved.dates') }}",
                    type: "GET",
                    data: {
                        resource_id: resourceId
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.reservations.length > 0) {
                                // Loop through reservations and append to UI
                                response.reservations.forEach(function(reservation) {
                                    $('#reserved-dates').append(`
                                <p style="margin-bottom: 10px">
                                    <strong>Pickup:</strong> ${reservation.pickup}<br>
                                    <strong >Return:</strong> ${reservation.return}<br>
                                </p>
                                `);
                                });
                            } else {
                                $('#reserved-dates').append('<p class="text-center">No reservations for this resource.</p>');
                            }
                        } else {
                            console.error('Error fetching reservations:', response.message);
                            $('#reserved-dates').append('<p>Error fetching reservations.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        $('#reserved-dates').append('<p>Failed to load reservations. Please try again.</p>');
                    },
                });
            }
        });

        // Initialize arrays to store selected resources and fetched policies
        let selectedResources = [];
        let selectionOrder = [];
        let fetchedPolicies = [];

        // Variable to throttle quick dropdown changes (e.g., spam clicks)
        let fetchTimeout;

        $('#policy-info-container').html('<div class="policy-item">No Item Selected</div>');

        // Function to handle dropdown change
        function handleDropdownChange(values) {
            console.log('Dropdown values:', values);

            // Clear any pending fetch policies
            clearTimeout(fetchTimeout);

            // Update selectedResources array with currently selected values
            selectedResources = values.map(value => value.value);

            // Clear the fetched policies tracker on every change
            fetchedPolicies = [];

            // Reset the display and the agree button state
            if (values.length > 0) {
                $('#agree-button').prop('disabled', false);
            } else {
                $('#agree-button').prop('disabled', true);
                clearPolicies(); // Clear the policies if no values are selected
            }

            // Get the most recently selected item from selectionOrder
            if (values.length > 0) {
                // Reset the image and the policy container before fetching
                resetDisplay(values);
            } else {
                resetToDefaultDisplay(); // Display default settings if no item is selected
            }

            // Throttle the fetching of policies to avoid spammy behavior
            fetchTimeout = setTimeout(() => {
                fetchPolicies(selectedResources);
            }, 300); // 300ms throttle time to prevent spamming
        }

        // Function to clear the policy container
        function clearPolicies() {
            $('#policy-info-container').html('<div class="policy-item">No Item Selected</div>');
            fetchedPolicies = []; // Clear fetched policies
        }

        // Reset to default image and no item selected
        function resetToDefaultDisplay() {
            var defaultImageSrc = "{{ asset('storage/assets/default_resource.png') }}";
            $('#item-image').attr('src', defaultImageSrc);
            $('.image-container').show();
            clearPolicies();
        }

        // Reset display and update image based on selection
        function resetDisplay(values) {
            $('#policy-info-container').empty(); // Clear policies
            $('.image-container').hide();
            $('#selected-item').text('Select Item');

            // Get the latest selected resource
            var latestValue = values[values.length - 1].value;
            var latestResourceInfo = latestValue.split('|');

            if (latestResourceInfo.length >= 3) {
                var latestResourceId = latestResourceInfo[0];
                var latestImagePath = latestResourceInfo[2];
                var latestImageSrc = "{{ asset('') }}" + latestImagePath;
                var latestSelectedItemText = latestResourceInfo[1];

                $('#selected-item').text(latestSelectedItemText);
                $('#item-image').attr('src', latestImageSrc);
                $('.image-container').show();
            }
        }

        // Function to fetch policies for selected resources sequentially
        function fetchPoliciesSequentially(resources, index = 0) {
            if (index >= resources.length) return; // All resources processed

            var resourceId = resources[index].split('|')[0];

            // Check if the policy is already fetched
            if (fetchedPolicies.includes(resourceId)) {
                fetchPoliciesSequentially(resources, index + 1); // Skip and continue
                return;
            }

            // Fetch the policy via AJAX
            $.ajax({
                url: "{{ route('equipment.form') }}",
                type: "GET",
                data: {
                    resource_id: resourceId
                },
                success: function(response) {
                    appendPolicy(response.policy_name, response.policy_content, response.inclusions);
                    fetchedPolicies.push(resourceId); // Mark as fetched
                    fetchPoliciesSequentially(resources, index + 1); // Fetch next policy
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    fetchPoliciesSequentially(resources, index + 1); // Continue even on error
                }
            });
        }

        // Function to fetch policies for selected resources
        function fetchPolicies(resources) {
            if (resources.length === 0) {
                clearPolicies(); // Clear when no resources are selected
                return;
            }

            $('#policy-info-container').empty(); // Clear container before fetching
            fetchedPolicies = []; // Reset fetched policies array
            fetchPoliciesSequentially(resources);
        }

        // Function to append policy title and content to modal
        function appendPolicy(title, content, inclusion) {
            var policyContainer = $('<div class="policy-item"></div>');
            policyContainer.append('<h3>' + title + '</h3>');
            policyContainer.append('<p>' + content + '</p>');
            policyContainer.append('<p><b>Inclusion:</b> ' + inclusion + '</p><br>');

            $('#policy-info-container').append(policyContainer);
        }

        // Initialize modal on show
        $('#policyModal').on('show.bs.modal', function(event) {
            fetchPolicies(selectedResources);
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


<!-- Request Error -->
@if(Session::has('error'))
<script>
    swal("Invalid", "{{Session::get('error')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif