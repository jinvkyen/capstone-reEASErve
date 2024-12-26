<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Policy</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.systemadmin.system_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8 sm:mt-[-25px] sm:ml-[25px]">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Registration Policy</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8">

                </div>
            </div>

            <!-- User Agreement Upon Registration of Users -->
            <div class="flex flex-col p-2 rounded-lg shadow-lg mx-4 mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="responsive-bg w-full flex items-center justify-center mb-0">
                    <div class="responsive-container w-full lg:p-4 sm:p-4">
                        <h3 class="mb-2">User Agreement</h3>
                        <form action="{{ route('system.policy.save')}}" method="POST">
                            <!-- Title -->
                            <div class="p-2 w-full items-center">
                                <div class="relative">
                                    <label for="title-field" class="text-black font-normal flex items-center justify-between mb-1">
                                        <span>Title<span class="text-red-500">*</span></span>
                                        @error('title')
                                        <p class="text-red-500 text-xs">{{$message}}</p>
                                        @enderror
                                    </label>
                                    <div class="relative">
                                        <input id="title-field" type="text" name="title" class="w-full p-2 pr-10 border border-gray-300 rounded-lg font-mont" placeholder="Enter a new policy title" value="{{ old('title', $policy->title ?? '') }}" readonly disabled>
                                        <button id="toggle-title-edit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition duration-300">
                                            <!-- Edit icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="edit-icon h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <!-- Check icon (hidden initially) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="check-icon hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Content -->
                                    <label for="content-field" class="text-black font-normal flex items-center justify-between mb-1 mt-4">
                                        <span>Content<span class="text-red-500">*</span></span>
                                        @error('content')
                                        <p class="text-red-500 text-xs">{{$message}}</p>
                                        @enderror
                                    </label>
                                    <div class="relative">
                                        <textarea id="content-field" name="content" rows="6" class="w-full p-2 pr-10 border resize-none border-gray-300 rounded-lg font-mont text-justify" placeholder="Enter policy content">{{ old('content', $policy->content ?? '') }}</textarea>
                                        <button id="toggle-content-edit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition duration-300">

                                            <!-- Edit icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="edit-icon h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <!-- Check icon (hidden initially) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="check-icon hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Save Button -->
                                <button id="save-button" type="submit" class="hidden mt-4 p-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg w-full font-mont">Save</button>
                            </div>
                            <p class="text-xs text-200 text-justify ml-2">Updated policies can help improve data accuracy and management, ensuring that the information collected is relevant and useful for organizational needs.</p>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Email Restriction -->
            <div class="flex flex-col p-2 rounded-lg shadow-lg mx-4 mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="responsive-bg w-full flex items-center justify-center mb-0">
                    <div class="responsive-container w-full lg:p-4 sm:p-4">
                        <h3 class="mb-2">Organization Domain Name</h3>
                        <form action="{{ route('system.domain.save')}}" method="POST">
                            @csrf
                            <!-- Title -->
                            <div class="p-2 w-full items-center">
                                <div class="relative">
                                    <label for="email-field" class="text-black font-normal flex items-center justify-between mb-1">
                                        <span>Email Address<span class="text-red-500">*</span></span>
                                        @error('email')
                                        <p class="text-red-500 text-xs">{{$message}}</p>
                                        @enderror
                                    </label>
                                    <div class="relative">
                                        <input id="email-field" type="text" name="email" class="w-full p-2 pr-10 border border-gray-300 rounded-lg font-mont" placeholder="Enter a new email address" value="{{ $domain }}" readonly disabled>
                                        <button id="toggle-edit-email" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                            <!-- Edit icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Save Button -->
                                <button id="save-button-email" class="hidden mt-2 p-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg w-full font-mont justify-center">Save</button>
                            </div>
                            <p class="text-xs text-200 text-justify ml-2">This ensures that only valid email addresses remain associated with an active account, protecting both the user and the organization from unauthorized access. </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@if (Session::has('success'))
<script>
    swal("Changed.", "{{ Session::get('success') }}", 'success', {
        button: true,
        button: "OK"
    })
</script>
@endif

@if (session('info'))
<script>
    swal("Info", "{{ session('info') }}", "info");
</script>
@endif

@if ($errors->any())
<script>
    swal("Error", "{{ implode(' ', $errors->all()) }}", "error");
</script>
@endif

<!-- User Agreement Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleField = document.getElementById('title-field');
        const contentField = document.getElementById('content-field');
        const toggleTitleButton = document.getElementById('toggle-title-edit');
        const toggleContentButton = document.getElementById('toggle-content-edit');
        const saveButton = document.getElementById('save-button');
        const titleEditIcon = toggleTitleButton.querySelector('.edit-icon');
        const titleCheckIcon = toggleTitleButton.querySelector('.check-icon');
        const contentEditIcon = toggleContentButton.querySelector('.edit-icon');
        const contentCheckIcon = toggleContentButton.querySelector('.check-icon');
        let isEditingTitle = false;
        let isEditingContent = false;

        // Toggle title editing
        function toggleTitleEdit(event) {
            event.preventDefault(); // Prevent form submission
            isEditingTitle = !isEditingTitle;
            titleField.readOnly = !isEditingTitle;
            titleField.disabled = !isEditingTitle;

            // Toggle icons with animation
            titleEditIcon.classList.toggle('hidden', isEditingTitle);
            titleCheckIcon.classList.toggle('hidden', !isEditingTitle);
            saveButton.classList.toggle('hidden', !(isEditingTitle || isEditingContent));
        }

        // Toggle content editing
        function toggleContentEdit(event) {
            event.preventDefault(); // Prevent form submission
            isEditingContent = !isEditingContent;
            contentField.readOnly = !isEditingContent;
            contentField.disabled = !isEditingContent;
            if (isEditingContent) contentField.focus();
            saveButton.classList.toggle('hidden', !(isEditingTitle || isEditingContent));

            // Toggle icons with animation
            contentEditIcon.classList.toggle('hidden', isEditingContent);
            contentCheckIcon.classList.toggle('hidden', !isEditingContent);
        }

        // Add click listeners for toggling edit modes
        toggleTitleButton.addEventListener('click', toggleTitleEdit);
        toggleContentButton.addEventListener('click', toggleContentEdit);

        // Save changes via AJAX request
        saveButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission on save button
            const title = titleField.value;
            const content = contentField.value;

            fetch('{{ route("system.policy.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        title: title,
                        content: content
                    })
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.json();
                    }
                })
                .then(result => {
                    if (result && result.success) {
                        swal("Updated!", "User Registration Policy is Updated", "success")
                            .then(() => {
                                location.reload();
                            });
                    } else {
                        swal("Error", result.message || "Failed to update the policy.", "error");
                    }
                })
                .catch(error => {
                    swal("Error", "An error occurred. Please try again.", "error");
                });
        });
    });
</script>

<!-- Domain Restriction -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailField = document.getElementById('email-field');
        const toggleButton = document.getElementById('toggle-edit-email');
        const saveButton = document.getElementById('save-button-email');
        let isEditing = false;

        function toggleEdit() {
            event.preventDefault();
            isEditing = !isEditing;
            emailField.readOnly = !isEditing;
            emailField.disabled = !isEditing; // Disable field when not editing
            emailField.focus();
            saveButton.classList.toggle('hidden', !isEditing); // Show save button when editing

            // Toggle between edit and save icons
            toggleButton.innerHTML = isEditing ?
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>' :
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>';
        }

        toggleButton.addEventListener('click', toggleEdit);

        saveButton.addEventListener('click', function() {
            event.preventDefault();
            const email = emailField.value;

            // Make AJAX request to save email
            fetch('{{ route("system.domain.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal("Updated!", "Email Domain Updated", "success")
                            .then(() => {
                                location.reload();
                            });
                    } else {
                        swal("Error", result.message || "Failed to Update Domain Email.", "error");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
</script>

</html>