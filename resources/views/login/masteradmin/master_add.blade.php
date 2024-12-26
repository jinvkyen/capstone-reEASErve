<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Resources {{$cms['dept_label']}}</title>
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
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Manage Resource</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Form Content -->
            <form method="POST" action="{{ route('ma.add')}}" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col p-2 rounded-lg shadow-lg mx-4 mt-8 sm:text-sm" style="background-color: var(--custom-color);">

                    <div class="flex flex-row w-full">
                        <h3 class="font-semibold ml-3 mt-3 text-xl">Laboratory and Equipment</h3>
                    </div>
                    <div class="flex flex-col lg:flex-row w-full">
                        <!-- Left Side -->
                        <div class="flex flex-col p-2 lg:w-8/12 sm:mb-0">
                            <div class="flex flex-col w-full ml-2">
                                <!-- section 1: Resource Name, Serial Number -->
                                <div class="p-1 w-full mt-2">
                                    <label class="text-black font-normal">Resource Name<span class="text-red-500">*</span></label>
                                    <input id="input-fields" name="resource_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Resource Name" value="{{ old('resource_name') }}">
                                    @error('resource_name')
                                    <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                    @enderror
                                </div>

                                <div class="p-1 w-full mt-2">
                                    <label class="text-black font-normal">Serial Number<span class="text-red-500">*</span></label>
                                    <input id="input-fields" name="serial_number" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Serial Number" value="{{ old('serial_number') }}">
                                    @error('serial_number')
                                    <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                    @enderror
                                </div>

                                <!-- section 2: Resource Type -->
                                <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                    <div class="p-1 w-4/12 sm:w-full mt-2 md:w-full">
                                        <label class="text-black font-normal">Resource Type<span class="text-red-500">*</span></label>
                                        <select id="dropdown-button" name="resource_type" class="w-full p-2 border border-gray-300 rounded-lg font-mont h-10 text-black">
                                            <option selected disabled>Select Type</option>
                                            @foreach ($resource_type as $type)
                                            <option value="{{ $type->category_id }}" {{ old('resource_type') == $type->category_id ? 'selected' : '' }}>
                                                {{ $type->resource_type }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('resource_type')
                                        <p class="text-red-700 ml-2 text-xs">{{$message}}!</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Section 3: Policy -->
                                <div class="p-1 w-full mt-2">
                                    <label class="text-black font-normal">Policy Name<span class="text-red-500">*</span></label>
                                    <select id="policy-name-select" name="policy_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont">
                                        <option value="" disabled {{ old('policy_name') == '' ? 'selected' : '' }}>Select Policy</option>
                                        @foreach ($policies as $policyItem)
                                        <option value="{{ $policyItem->policy_id }}" data-content="{{ $policyItem->policy_content }}" data-inclusions="{{ $policyItem->inclusions }}" {{ old('policy_name', isset($policy) ? $policy->policy_id : '') == $policyItem->policy_id ? 'selected' : '' }}>
                                            {{ $policyItem->policy_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('policy_name')
                                    <p class="text-red-700 ml-2 text-xs">{{ $message }}!</p>
                                    @enderror
                                </div>
                                <div class="p-1 w-full mt-2">
                                    <label class="text-black font-normal">Policy Content<span class="text-red-500">*</span></label>
                                    <textarea id="policy-content" name="policy_content" class="w-full bg-gray-100 p-2 border border-gray-300 rounded-lg font-mont" placeholder="Policy Content" readonly>{{ old('policy_content', isset($policy) ? $policy->policy_content : '') }}</textarea>
                                    @error('policy_content')
                                    <p class="text-red-700 ml-2 text-xs">{{ $message }}!</p>
                                    @enderror
                                </div>
                                <div class="p-1 w-full mt-2">
                                    <label class="text-black font-normal">Inclusions<span class="text-red-500">*</span></label>
                                    <textarea id="policy-inclusions" name="inclusions" class="w-full bg-gray-100 resize-none p-2 border border-gray-300 rounded-lg font-mont" placeholder="Inclusions" readonly>{{ old('inclusions', isset($policy) ? $policy->inclusions : '') }}</textarea>
                                    @error('inclusions')
                                    <p class="text-red-700 ml-2 text-xs">{{ $message }}!</p>
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
                        </div>

                        <!-- Right Side (Image) -->
                        <div class="flex flex-col p-2 w-full xl:w-4/12 justify-center items-center relative">
                            <div class="relative flex justify-center items-center flex-col" id="preview">
                                <!-- Dark Opacity Layer -->
                                <div class="absolute inset-0 bg-black opacity-20 rounded-lg"></div>
                                <!-- Image Preview -->
                                <img id="image_preview" src="{{ empty($imageSrc) ? asset('storage/assets/default_resource.png') : $imageSrc }}" alt="Image Preview" class="max-h-64 max-w-64 min-h-64 min-w-64 object-cover rounded-lg">
                                <input type="file" id="imageInput" name="image" class="w-full p-2 border bg-white rounded-b-lg font-mont absolute bottom-0" onchange="displayImage(this)">
                            </div>
                            <!-- Note -->
                            <p class="mt-5">Max Size of 2MB Only</p>
                        </div>
                    </div>

                    <!-- Bottom Section (Buttons) -->
                    <div class="flex flex-col sm:flex-row-reverse justify-between w-full p-4 font-mont sm:items-center">
                        <div class="flex flex-col w-full justify-end items-center">
                            <div class="flex lg:w-full space-x-2 justify-end">
                                <!-- Submit Button -->
                                <button class="font-mont text-white h-auto w-32 bg-300 hover:bg-blue-700 rounded-lg text-base lg:text-md text-center text-nowrap p-2 py-2" type="submit">Add Resource</button>
                            </div>
                        </div>
                        <div class="flex flex-row w-full lg:justify-start sm:justify-center">
                            <!-- Back Button -->
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
    </div>
</body>


<!-- SWAL Success-->
@if(Session::has('success'))
<script>
    swal("Invalid", "{{Session::get('success')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif

<!-- SWAL Error-->
@if(Session::has('error'))
<script>
    swal("Invalid", "{{Session::get('error')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif


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
    // Do not allow negative value
    function preventNegative(event) {
        // Get the current input value
        const value = event.target.value;

        // Get the key code of the pressed key
        const keyCode = event.keyCode || event.which;

        // Check if the pressed key is the minus sign (-)
        if (keyCode === 189 || keyCode === 109) {
            // If the input value is empty or already negative, prevent the default action
            if (!value || value < 0) {
                event.preventDefault();
            }
        }
    }
</script>