<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Facility {{$cms['dept_label']}}</title>
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
        <div class="flex flex-col flex-1 lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Manage Resource</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Form Content -->
            <form id="editForm" method="POST" action="{{ route('ma.edit.facility')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="facilities_id" value="{{ $facility->facilities_id }}">
                <div class="flex flex-col p-2 rounded-lg shadow-lg mx-4 mt-8" style="background-color: var(--custom-color);">
                    <!-- Header Section -->
                    <div class="flex flex-col lg:flex-row w-full">
                        <div class="flex flex-col lg:w-8/12">
                            <h3 class="font-semibold ml-3 mt-3 text-xl">Edit Department Resource</h3>
                            <span class="ml-3">
                                <p class="mb-0">Facility ID: {{$facility->facilities_id}}</p>
                                <p>Policy ID: {{$facility->policy}}</p>
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Left Side -->
                        <div class="flex flex-col lg:w-8/12 p-2">
                            <!-- Section 1: Facility Name -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black font-normal">Facility Name<span class="text-red-500">*</span></label>
                                <input id="input-fields" name="facility_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Facility Name" value="{{$facility->facility_name}}">
                                @error('facility_name')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
                            </div>
                            <!-- Section 2: Location -->
                            <div class="p-1 w-full mt-2">
                                <label class="text-black font-normal">Location<span class="text-red-500">*</span></label>
                                <input id="input-fields" name="location" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Room and Building Location" value="{{$facility->location}}">
                                @error('location')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
                            </div>

                            <!-- Resource Status -->
                            <div class="p-1 w-4/12 sm:w-full mt-2 md:w-full">
                                <label class="text-black font-normal">Facility Status<span class="text-red-500">*</span></label>
                                <select id="dropdown-button" name="resource_status" class="w-full p-2 border border-gray-300 rounded-lg font-mont h-10 text-black">
                                    <option selected disabled>Select Resource Status</option>
                                    @foreach ($status as $data)
                                    <option value="{{ $data->status_id }}" {{ (old('status_id', $facility->status) == $data->status_id) ? 'selected' : '' }}>
                                        {{ $data->status_state }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                @enderror
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
                                <img id="image_preview" src="{{ asset('storage/' . $facility->image) }}" alt="Image Preview" class="h-64 w-64 min-h-64 min-w-64 rounded-b-lg">
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

                </div>
            </form>
        </div>
    </div>
    <form id="deleteForm" action="{{ route('ma.delete.facility') }}" method="POST">
        @csrf
        <input type="hidden" name="facilities_id" value="{{ $facility->facilities_id }}">
    </form>
</body>

<!-- SWAL Error-->
@if(Session::has('error'))
<script>
    swal("Invalid", "{{Session::get('error')}}", 'error', {
        button: "OK"
    });
</script>
@endif

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

            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
    });

    document.getElementById('deleteButton').addEventListener('click', function() {
        Swal.fire({
            title: "Are you Sure?",
            text: "Deleting this Resource will Remove its Related Facility Requests",
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
</script>


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

</html>