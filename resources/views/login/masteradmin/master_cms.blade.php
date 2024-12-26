<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.masteradmin.master_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Content Management</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <div id="content" class="mx-4 lg:p-5 sm:p-4 rounded-lg flex flex-col bg-custom-color shadow-lg" style="background-color: var(--custom-color)">
                <h1 class="font-bold xl:text-2xl sm:text-lg mb-3">General</h1>
                <form id="form1" action="{{route('ma.cms.general')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="flex lg:flex-row md:flex-row sm:flex-col mb-3 justify-between">
                        <div class="flex flex-col bg-white p-3 rounded-md shadow-sm items-center">
                            <!-- CMS 1: Accent Color -->
                            <h1 class="font-bold xl:text-xl sm:text-lg mb-2 mt-4 text-center">Accent Color</h1>
                            <div class="flex flex-col space-y-4">
                                <!-- Color Picker -->
                                <div class="flex items-center">
                                    <label for="colorPicker" class="text-md mr-4">Choose Color:</label>
                                    <input type="color" id="colorPicker" name="accent_color" value="{{ old('accent_color', $currentAccentColor) }}" class="border-2 rounded-md h-10 w-20">
                                </div>
                                <!-- Color Info -->
                                <div class="text-md">
                                    <p>Name: <span id="colorName" class="font-semibold">--</span></p>
                                    <p>Value: <span id="hexValue" class="font-semibold">{{ old('accent_color', $currentAccentColor) }}</span></p>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="flex flex-row mt-4 space-x-4 justify-center">
                                <button id="chooseColorBtn" type="button" class="font-mont text-white h-auto w-32 bg-300 hover:bg-blue-700 rounded-lg text-base lg:text-md text-center text-nowrap p-2 py-2">
                                    Preview
                                </button>
                                <button id="resetColorBtn" type="button" class="font-mont text-white h-auto w-30 bg-gray-500 hover:bg-gray-700 text-base lg:text-md rounded-lg px-4 py-2 text-center">
                                    Reset
                                </button>
                            </div>
                        </div>

                        <!-- Control for Department's Maximum Duration of Borrowing a Resource -->
                        <div class="flex bg-white p-3 rounded-md shadow-sm lg:ml-4 lg:mt-0 sm:ml-0 sm:mt-2 justify-center items-center max-w-4/12">
                            <div class="flex flex-col w-full max-w-lg">
                                <h1 class="font-bold xl:text-xl sm:text-lg mb-2 mt-3 text-center">Maximum Reservation Time</h1>

                                <!-- Equipment -->
                                <label class="text-black font-normal mt-2 mb-0">Equipment<span class="text-red-500">*</span></label>
                                <select id="equipmentDuration" name="equipment_max_duration" class="w-full p-1 border border-gray-300 rounded-lg font-mont h-8 text-black">
                                    <option selected disabled>Click to Select</option>
                                    @for ($i = 1; $i <= 336; $i++)
                                        <option value="{{ $i }}" {{ $i == ($currentDurations['Equipment'] ?? null) ? 'selected' : '' }}>
                                        {{ $i }} Hour{{ $i > 1 ? 's' : '' }}
                                        </option>
                                        @endfor
                                </select>

                                <!-- Laboratory -->
                                <label class="text-black font-normal mt-2 mb-0">Laboratory<span class="text-red-500">*</span></label>
                                <select id="laboratoryDuration" name="laboratory_max_duration" class="w-full p-1 border border-gray-300 rounded-lg font-mont h-8 text-black">
                                    <option selected disabled>Click to Select</option>
                                    @for ($i = 1; $i <= 336; $i++)
                                        <option value="{{ $i }}" {{ $i == ($currentDurations['Laboratory'] ?? null) ? 'selected' : '' }}>
                                        {{ $i }} Hour{{ $i > 1 ? 's' : '' }}
                                        </option>
                                        @endfor
                                </select>

                                <!-- Facility -->
                                <label class="text-black font-normal mt-2 mb-0">Facilities<span class="text-red-500">*</span></label>
                                <select id="facilityDuration" name="facility_max_duration" class="w-full p-1 border border-gray-300 rounded-lg font-mont h-8 text-black">
                                    <option selected disabled>Click to Select</option>
                                    @for ($i = 1; $i <= 336; $i++)
                                        <option value="{{ $i }}" {{ $i == ($currentDurations['Facility'] ?? null) ? 'selected' : '' }}>
                                        {{ $i }} Hour{{ $i > 1 ? 's' : '' }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Background Section -->
                        <div class="flex bg-white p-3 rounded-md shadow-sm lg:ml-4 lg:mt-0 sm:ml-0 sm:mt-2">
                            <div class="flex flex-col w-full">
                                <h1 class="font-bold xl:text-xl sm:text-lg mb-2 mt-4 text-center">Department Background</h1>

                                <!-- Image Preview -->
                                <div class="flex flex-col mt-4 items-center">
                                    <img id="backgroundPreview" src="{{ asset($cms['url_bg_image'] ?? 'assets/default_bg.png') }}" alt="Department Background Preview" class="min-w-48 min-h-32 max-w-48 max-h-32 object-cover rounded-md border border-gray-300" />
                                </div>

                                <!-- Upload Buttons -->
                                <div class="mt-2">
                                    <label for="backgroundUpload" class="block mb-2 text-sm font-medium text-gray-700">Upload Background (2MB Maximum File Size)</label>
                                    <input name="bg_image" id="backgroundUpload" type="file" accept="image/*" class="font-mont block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="previewBackground()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex lg:flex-row sm:flex-col sm:items-center">
                        <button data-form="form1" type="button" class="saveBtn font-mont mt-4 text-white h-auto w-30 bg-green-600 hover:bg-green-700 text-base lg:text-md rounded-lg px-4 py-2 text-center">
                            Save Changes
                        </button>
                    </div>
                </form>


                <hr class="w-100 h-2 mx-auto my-4 bg-gray-100 border-0 rounded md:my-10 dark:bg-gray-400">

                <!-- CMS 2: About Us -->
                <h1 class="font-bold xl:text-2xl sm:text-lg mb-0 mt-1 text-left">About the Department</h1>

                <form id="form2" action="{{ route('ma.cms.about') }}" method="POST">
                    @csrf
                    <input type="hidden" name="department" value="{{ session('dept_name') }}">
                    <div class="flex flex-col lg:flex-row w-full">
                        <div class="flex flex-col p-2 lg:w-full sm:mb-0">
                            <div class="flex flex-col w-full">

                                <!-- Header -->
                                <div class="p-1 w-full mt-0">
                                    <label class="text-black font-normal">Header<span class="text-red-500">*</span></label>
                                    <input id="header" name="header"
                                        class="w-full p-2 border border-gray-300 rounded-lg font-mont"
                                        value="{{ old('header', $aboutContent->header ?? '') }}">
                                    @error('header')
                                    <p class="text-red-700 ml-2 text-xs">{{ $message }}!</p>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="p-1 w-full mt-2">
                                    <label class="text-black font-normal">Content<span class="text-red-500">*</span></label>
                                    <textarea id="content" name="content"
                                        class="w-full h-24 resize-none p-2 border border-gray-300 rounded-lg font-mont ">{{ old('content', $aboutContent->content ?? '') }}</textarea>
                                    @error('content')
                                    <p class="text-red-700 ml-2 text-xs">{{ $message }}!</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section (Buttons) -->
                    <div class="flex lg:flex-row sm:flex-col sm:items-center">
                        <button data-form="form2" type="button" class="saveBtn font-mont mt-4 text-white h-auto w-30 bg-green-600 hover:bg-green-700 text-base lg:text-md rounded-lg px-4 py-2 text-center">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Color picker Javascript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const contentElement = document.getElementById('content');
                const colorPicker = document.getElementById('colorPicker');
                const hexValueElem = document.getElementById('hexValue');
                const colorNameElem = document.getElementById('colorName');
                const originalBackgroundColor = getComputedStyle(contentElement).backgroundColor;
                let selectedColor = colorPicker.value || "#f2fafc"; // Default to current color picker value or fallback

                hexValueElem.innerText = selectedColor;

                colorPicker.addEventListener('input', function() {
                    selectedColor = colorPicker.value;
                    hexValueElem.innerText = selectedColor;
                    fetch(`https://www.thecolorapi.com/id?hex=${selectedColor.substring(1)}`)
                        .then(response => response.json())
                        .then(data => {
                            colorNameElem.innerText = data.name.value;
                        });
                });

                document.getElementById('chooseColorBtn').addEventListener('click', function() {
                    contentElement.style.backgroundColor = selectedColor;
                });

                document.getElementById('resetColorBtn').addEventListener('click', function() {
                    contentElement.style.backgroundColor = originalBackgroundColor;
                    colorNameElem.innerText = '--';
                    hexValueElem.innerText = '--';
                    colorPicker.value = '#000000'; // Reset color picker value to default
                    selectedColor = originalBackgroundColor; // Reset selectedColor to original background color
                });
            });
        </script>

        <!-- Confirmation -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.saveBtn').forEach(button => {
                    button.addEventListener("click", function(event) {
                        event.preventDefault(); // Prevent the form from submitting immediately

                        // Get the form ID from the button's data attribute
                        const formId = this.getAttribute('data-form');

                        Swal.fire({
                            title: "Do you want to save the changes?",
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: "Save",
                            denyButtonText: `Don't save`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById(formId).submit();
                            } else if (result.isDenied) {
                                Swal.fire("Changes are not saved", "", "info");
                            }
                        });
                    });
                });
            });
        </script>


        <!-- For Emblem Preview -->
        <script>
            function previewEmblem() {
                const file = document.getElementById('emblemUpload').files[0];
                const preview = document.getElementById('emblemPreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            function previewBackground() {
                const file = document.getElementById('backgroundUpload').files[0];
                const preview = document.getElementById('backgroundPreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }
        </script>

        @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
            });
        </script>
        @endif

        @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
            });
        </script>
        @endif
</body>

</html>