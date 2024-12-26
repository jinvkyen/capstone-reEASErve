<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resource {{$cms['dept_label']}}</title>
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
        @include('login.masteradmin.master_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Manage Resources</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <div class="bg-custom-color mx-4 mt-4 p-3 rounded-lg shadow-lg" style="background-color: var(--custom-color);">
                <!-- Header Section -->
                <div class="flex flex-col lg:flex-row w-full justify-between">
                    <div class="flex flex-col lg:w-8/12">
                        <h3 class="font-semibold ml-3 mt-3 text-xl">Edit Department Resource</h3>
                        <span class="ml-3">
                            <p class="mb-0">Resource ID: {{$resource->resource_id}}</p>
                            <p>Policy ID: {{ $resource->policy_id ?? 'None' }}</p>
                        </span>
                    </div>

                    <div class="flex flex-col space-y-2 lg:flex-row lg:items-center lg:justify-end lg:space-x-2 lg:space-y-0 lg:mt-0 lg:ml-10">
                        <!-- User Feedback Modal Button -->
                        <button id="UserFeedbackBtn"
                            class="font-mont text-white h-12 w-full lg:w-auto bg-green-600 hover:bg-green-700 rounded-lg text-base text-center p-2"
                            type="button" data-toggle="modal" data-target="#viewFeedbackModal">
                            User Feedbacks
                        </button>
                    </div>

                </div>

                <!-- Form Content -->
                <form id="editForm" method="POST" action="{{ route('ma.edit')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="resource_id" value="{{ $resource->resource_id }}">
                    <div class="flex flex-col lg:flex-row w-full">
                        <!-- Left Side -->
                        <div class="flex flex-col lg:w-8/12 p-2">

                            <!-- section 1: Resource Name, Serial Number -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black font-normal">Resource Name<span class="text-red-500">*</span></label>
                                <input id="input-fields" name="resource_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Resource Name" value="{{ $resource->resource_name }}">
                                @error('resource_name')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
                            </div>

                            <div class="p-1 w-full mt-2">
                                <label class="text-black font-normal">Serial Number<span class="text-red-500">*</span></label>
                                <input id="input-fields" name="serial_number" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Serial Number" value="{{ $resource->serial_number }}">
                                @error('serial_number')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
                            </div>
                            <!-- section 2: Type, Resource Status -->
                            <div class="flex flex-row">
                                <div class="p-1 w-4/12 mt-2 sm:w-full md:w-full">
                                    <label class="text-black font-normal">Resource Type<span class="text-red-500">*</span></label>
                                    <select id="dropdown-button" name="resource_type" class="w-full p-2 border border-gray-300 rounded-lg font-mont text-black">
                                        <option selected disabled>Select Type</option>
                                        @foreach ($resource_types as $type)
                                        <option value="{{ $type->category_id }}" {{ $resource->resource_type == $type->category_id ? 'selected' : '' }}>
                                            {{ $type->resource_type }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('resource_type')
                                    <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                    @enderror
                                </div>

                                <div class="p-1 w-6/12 sm:w-full mt-2 md:w-full">
                                    <label class="text-black font-normal">Resource Status<span class="text-red-500">*</span></label>
                                    <select id="dropdown-button" name="resource_status" class="w-full p-2 border border-gray-300 rounded-lg font-mont text-black">
                                        <option selected disabled>Select Resource Status</option>
                                        @foreach ($status as $data)
                                        <option value="{{ $data->status_id }}" {{ (old('status_id', $resource->status) == $data->status_id) ? 'selected' : '' }}>
                                            {{ $data->status_state }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status_id')
                                    <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section 3: Policy -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black font-normal">Policy Name<span class="text-red-500">*</span></label>
                                <select id="policy-name-select" name="policy_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont">
                                    <option value="" disabled selected>Select Policy</option>
                                    @foreach ($policies as $policyItem)
                                    <option value="{{ $policyItem->policy_id }}" data-content="{{ $policyItem->policy_content }}" data-inclusions="{{ $policyItem->inclusions }}" {{ isset($policy) && $policy->policy_id == $policyItem->policy_id ? 'selected' : '' }}>{{ $policyItem->policy_name }}</option>
                                    @endforeach
                                </select>
                                @error('policy_name')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
                            </div>
                            <div class="p-1 w-full mt-2">
                                <label class="text-black font-normal">Policy Content<span class="text-red-500">*</span></label>
                                <textarea id="policy-content" name="policy_content" class="w-full bg-gray-100 p-2 border border-gray-300 rounded-lg font-mont" placeholder="Policy Content" readonly>{{ isset($policy) ? $policy->policy_content : '' }}</textarea>
                                @error('policy_content')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
                            </div>
                            <div class="p-1 w-full mt-2">
                                <label class="text-black font-normal">Inclusions<span class="text-red-500">*</span></label>
                                <textarea id="policy-inclusions" name="inclusions" class="w-full bg-gray-100 resize-none p-2 border border-gray-300 rounded-lg font-mont" placeholder="Type here if there are any items included when reserving the resource. Leave blank if none." readonly>{{ isset($policy) ? $policy->inclusions : '' }}</textarea>
                                @error('inclusions')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
                            </div>

                            <!-- For Policy Dropdown -->
                            <script>
                                document.getElementById('policy-name-select').addEventListener('change', function() {
                                    var selectedOption = this.options[this.selectedIndex];
                                    document.getElementById('policy-content').value = selectedOption.getAttribute('data-content');
                                    document.getElementById('policy-inclusions').value = selectedOption.getAttribute('data-inclusions');
                                });
                            </script>

                        </div>

                        <!-- Right Side -->
                        <div class="flex p-2 w-full xl:w-4/12 justify-center items-center relative">
                            <div class=" relative flex justify-center items-center flex-col" id="preview">
                                <!-- Dark Opacity Layer -->
                                <div class="absolute inset-0 bg-black opacity-20 rounded-lg"></div>
                                <!-- Image Preview -->
                                <img id="image_preview" src="{{ asset('storage/' . $resource->image) }}" alt="Image Preview" class="h-64 w-64 min-h-64 min-w-64 rounded-b-lg">
                                <input type="file" id="imageInput" name="image" class="w-full p-2 border bg-white rounded-b-lg font-mont absolute bottom-0" onchange="displayImage(this)">
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section (Buttons) -->
                    <div class="flex flex-col sm:flex-row-reverse justify-between w-full font-mont sm:items-center">
                        <div class="flex flex-col sm:flex-row-reverse w-full justify-between sm:space-x-0 space-y-4 sm:space-y-0">
                            <div class="flex lg:w-full space-x-2 justify-end">
                                <!-- Delete Button -->
                                <button id="deleteButton" class="font-mont text-white h-auto w-full sm:w-auto bg-red-500 hover:bg-red-700 rounded-lg lg:text-base sm:text-xs lg:text-md text-center text-nowrap px-4 py-2" type="button">
                                    Delete
                                </button>
                                <!-- Submit Button -->
                                <button id="saveChangesButton" class="font-mont text-white h-auto w-full sm:w-auto bg-blue-500 hover:bg-blue-700 rounded-lg lg:text-base sm:text-xs lg:text-md text-center text-wrap px-4 py-2" type="button">
                                    Save Changes
                                </button>
                            </div>
                            <div class="flex flex-row w-full justify-center sm:justify-start sm:w-full">
                                <a href="{{route('ma.manage.resources')}}">
                                    <button type="button" class="font-mont text-white h-auto w-full sm:w-auto bg-gray-500 hover:bg-gray-700 rounded-lg text-base sm:text-sm lg:text-md text-center text-nowrap px-4 py-2">
                                        Back
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- View User Feedbacks Modal -->
            <div class="modal fade" id="viewFeedbackModal" tabindex="-1" aria-labelledby="viewFeedbackLabel"
                data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: var(--custom-color);">
                            <h5 class="modal-title xs:text-base font-normal" id="viewFeedbackLabel">Ratings and
                                Feedbacks for
                                {{ $resource->resource_name }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body overflow-y-auto" style="background-color: var(--custom-color);">
                            <!-- Action Form -->
                            <div class="w-full items-center text-center mt-2">
                                <label class="font-semibold text-xl" for="policyTitle">Overall Rating</label>
                                <p class="text-5xl font-semibold mb-0">{{ $rating }}</p>

                                <!-- Display overall rating -->
                                <div class="text-xs flex flex-col items-center text-center mt-2">
                                    <div class="flex flex-col items-center">
                                        <div class="flex flex-row items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($rating>= $i)
                                                <svg class="star filled text-yellow-500 w-8 h-8"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M10 15l-5.47 2.87 1.04-6.05L1 7.76l6.06-.88L10 2l2.94 4.88 6.06.88-4.57 4.06 1.04 6.05z" />
                                                </svg>
                                                @elseif ($rating > $i - 1 && $rating < $i)
                                                    <svg class="star half-filled text-yellow-500 w-8 h-8"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <defs>
                                                        <linearGradient id="half-filled">
                                                            <stop offset="50%" stop-color="currentColor" />
                                                            <stop offset="50%" stop-color="transparent" />
                                                        </linearGradient>
                                                    </defs>
                                                    <path fill="url(#half-filled)"
                                                        d="M10 15l-5.47 2.87 1.04-6.05L1 7.76l6.06-.88L10 2l2.94 4.88 6.06.88-4.57 4.06 1.04 6.05z" />
                                                    </svg>
                                                    @else
                                                    <svg class="star empty w-8 h-8" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path
                                                            d="M10 15l-5.47 2.87 1.04-6.05L1 7.76l6.06-.88L10 2l2.94 4.88 6.06.88-4.57 4.06 1.04 6.05z"
                                                            fill="none" stroke="currentColor" />
                                                    </svg>
                                                    @endif
                                                    @endfor
                                        </div>
                                        <div class="review-count text-sm text-slate-600 mt-1 mb-2">
                                            <p class="text-md font-sm">Based on {{ $totalReviews }} reviews
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Display overall rating -->

                                <!-- Start of grid container -->
                                <div class="lg:columns-2 gap-2 p-2">
                                    <!-- User Cards -->
                                    @foreach ($reviews as $review)
                                    @if (!empty($review->feedback))
                                    <div id="review-card-{{ $review->feedback_id }}" class="mb-2 break-inside-avoid-column antialiased">
                                        <div class="relative flex flex-col light:text-black bg-white light:bg-neutral-900 p-3 rounded-sm">
                                            <!-- Archive Button -->
                                            <div class="absolute top-2 right-2 group">
                                                <button type="button" class="delete7days flex items-center bg-purple-500 text-white px-2 py-1 rounded-md hover:bg-purple-600 transition deleteModalTrigger" data-id="{{ $review->feedback_id }}">
                                                    <i class="bi bi-archive-fill text-sm"></i>
                                                </button>
                                            </div>
                                            <div class="flex flex-row justify-between items-center mb-0">
                                                <!-- User Profile -->
                                                <div class="flex flex-row items-center mb-0">
                                                    <!-- Image -->
                                                    <img class="h-12 w-12 bg-white rounded-full" src="{{ asset($review->profile_pic) }}" alt="User Profile Picture"></img>
                                                    <div class="ml-2 text-left">
                                                        <!-- Name -->
                                                        <p class="text-sm mb-0 font-medium">
                                                            {{ $review->username }}
                                                        </p>
                                                        <!-- Date -->
                                                        <p class="text-xs text-slate-500">
                                                            {{ $review->formatted_date }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="relative group">
                                                    <p class="text-xs mt-0 text-justify mb-0 line-clamp-2 max-w-64">
                                                        {{ $review->feedback }}
                                                    </p>
                                                    <!-- Tooltip for full content on hover -->
                                                    <div class="absolute hidden group-hover:block bg-gray-500 text-white text-xs rounded p-2 w-64 max-h-24 overflow-y-auto z-10">
                                                        {{ $review->feedback }}
                                                    </div>
                                                </div>
                                                <!-- Rating -->
                                                <p class="text-md text-yellow-600 font-medium m-0">
                                                    {{ $review->rating }} stars
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="deleteForm" action="{{ route('ma.delete.resource') }}" method="POST">
                        @csrf
                        <input type="hidden" name="resource_id" value="{{ $resource->resource_id }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- SWAL Error-->
@if(Session::has('error'))
<script>
    swal("Invalid", "{{Session::get('error')}}", 'error', {
        button: "OK"
    });
</script>
@endif

<!-- SWAL Confirmations -->
<script>
    document.getElementById('saveChangesButton').addEventListener('click', function() {
        Swal.fire({
            title: "Save your Changes?",
            text: "",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#2669D5",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editForm').submit();
            }
        });
    });

    document.getElementById('deleteButton').addEventListener('click', function() {
        Swal.fire({
            title: "Are you Sure?",
            text: "Deleting this Resource will Remove Forever",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2669D5",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                // Change form action to delete and submit
                var form = document.getElementById('deleteForm');
                form.submit();
            }
        });
    });

    // Image Display
    function displayImage(input) {
        var previewImage = document.getElementById('image_preview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Negative Input Prevention
    function preventNegative(event, element) {
        if (event.key === '-' || event.key === 'e') {
            event.preventDefault();
        }
    }
</script>


<!-- Image Preview -->
<script>
    function displayImage(input) {
        var previewImage = document.getElementById('image_preview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<!-- Arhcive Feedback Record -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Attach click event to the archive button
        $(document).on('click', '.deleteModalTrigger', function() {
            const id = $(this).data('id'); // Use the id from the button
            const card = $('#review-card-' + id); // Target the card to remove

            $.ajax({
                url: '{{ route("ma.archive.feedback", ["id" => "__id__"]) }}'.replace('__id__', id),
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        card.remove(); // Remove the card containing the deleted review
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        });
    });
</script>


</html>