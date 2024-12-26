<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account {{$cms['dept_label']}}</title>
    <!-- .ico logo -->
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap JS Query -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <script src="https://unpkg.com/cropperjs/dist/cropper.js"></script>
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />

    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.deptadmin.dept_admin_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Account</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Account Settings Content -->
            <div class="flex flex-col p-2 rounded-lg shadow-lg mx-4 mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="flex flex-col lg:flex-row items-center align-middle lg:p-10 sm:p-2">
                    <div class="lg:order-1 m-3 lg:pr-4">
                        <img class="w-32 h-32 sm:w-36 sm:h-36 lg:w-48 lg:h-48 rounded-full border border-gray-500" src="{{ asset($userData['profile_pic']) }}" alt="User Image">
                    </div>

                    <div class="lg:order-2 flex flex-col items-center lg:items-start text-center lg:text-left w-full lg:w-auto space-y-2">
                        <p class="sm:text-sm lg:text-xl font-bold mb-0">{{ $userData['first_name'] }} {{ $userData['last_name'] }}</p>
                        <p class="sm:text-sm lg:text-lg text-gray-500 mt-0 mb-4">{{ $userData['user_id'] }}</p>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-mont mb-4 sm:text-xs lg:text-base" data-bs-toggle="modal" data-bs-target="#imageModal">
                            Change Image
                        </button>
                        <div class="overflow-x-auto w-full">
                            <table class="sm:text-xs lg:text-base text-left w-full text-balance">
                                <tr>
                                    <td class="font-semibold pb-2 pr-2">Full Name</td>
                                    <td class="pb-2">{{ $userData['first_name'] }} {{ $userData['last_name'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pb-2 pr-2">Email Address</td>
                                    <td class="pb-2 break-all">{{ $userData['email'] }}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold pr-2">Password</td>
                                    <td>
                                        <form action="{{ route('admin.password.change') }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <!-- Password Modal -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="text-blue-500 hover:text-blue-600 no-underline">Change Password</a>
                                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered rounded-t-lg">
                                                    <div class="modal-content bg-white shadow-xl font-mont" style="border-radius: 20px;">
                                                        <div class="modal-header bg-gradient-to-r from-teal-700 to-teal-900 text-white py-4 px-6 rounded-">
                                                            <h1 class="modal-title text-lg font-bold" id="staticBackdropLabel">Change Password</h1>
                                                            <button type="button" class="text-white hover:text-gray-200 focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-6">
                                                            <div class="space-y-4">
                                                                <!-- Old Password -->
                                                                <div class="relative">
                                                                    <input id="old_password" type="password" name="old_pass" class="w-full px-4 py-2 border border-gray-300 font-mont rounded-lg" placeholder="Old Password *" required>
                                                                    <i id="toggleOldPassword" class="fa-regular fa-eye" onclick="togglePasswordVisibility('old_password', 'toggleOldPassword')" style="position:absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                                                </div>

                                                                <!-- New Password -->
                                                                <div class="relative">
                                                                    <input id="password" onfocus="focusPassword()" oninput="validatePassword()" type="password" name="new_pass" class="w-full px-4 py-2 border border-gray-300 font-mont rounded-lg focus:outline-none" placeholder="New Password *" required>
                                                                    <i id="togglePassword" class="fa-regular fa-eye" onclick="togglePasswordVisibility('password', 'togglePassword')" style="position:absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                                                </div>

                                                                <!-- Password Criteria -->
                                                                <div class="bg-blue-50 p-4 rounded-lg font-mont">
                                                                    <h3 class="text-sm font-semibold text-blue-800 mb-2 font-mont">New Password Must Be:</h3>
                                                                    <ul class="text-xs text-blue-700 space-y-1 list-disc list-inside font-mont">
                                                                        <li>Must Be More Than 8 Characters</li>
                                                                        <li>Should Contain at least one special character (~!@#$%^&*_-+=|(){}[]:;"'<>,.?/)</li>
                                                                        <li>Should Contain at least one number (0-9)</li>
                                                                    </ul>
                                                                </div>

                                                                <!-- Confirm Password -->
                                                                <div class="relative">
                                                                    <input id="password_confirmation" oninput="validatePasswordConfirmation()" type="password" name="confirm_pass" class="w-full px-4 py-2 border outline-offset-0 border-gray-300 font-mont rounded-lg focus:outline-none" placeholder="Confirm Password *" required>
                                                                    <i id="togglePasswordConfirmation" class="fa-regular fa-eye" onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation')" style="position:absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-gray-50 px-6 py-4 rounded-b-lg font-mont">
                                                            <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 font-mont rounded-lg hover:bg-gray-400 transition-colors" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="px-4 py-2 bg-teal-700 text-white font-mont rounded-lg hover:bg-teal-800 transition-colors">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change User Image Modal -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-white shadow-xl font-mont">
                        <div class="modal-header bg-white text-slate-600 py-4 px-6">
                            <h1 class="modal-title text-lg font-bold" id="imageModalLabel">Change Profile Picture</h1>
                            <button type="button" class="text-black hover:text-gray-200 focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="px-6 py-4">
                            <form id="image-upload-form" action="{{ route('admin.image_upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="space-y-4 mt-0">
                                    <!-- Image Preview Container -->
                                    <div class="img-preview-container mb-3 flex justify-center">
                                        <img id="previewImageAdd"
                                            src="{{ isset($userData['profile_pic']) ? asset($userData['profile_pic']) : '' }}"
                                            alt="Profile Picture"
                                            class="max-w-full max-h-72 object-cover {{ isset($userData['profile_pic']) ? '' : 'hidden' }}" />
                                    </div>

                                    <div class="form-group">
                                        <label for="emblemAdd" class="block text-sm font-medium text-gray-700">Upload Image <span class="text-red-500">*</span></label>
                                        <input type="file" id="emblemAdd" name="image" class="font-mont block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                    </div>
                                </div>

                                <div>
                                    <button type="button" id="cropImageAdd" class="w-full bg-blue-500 text-white px-2 py-2 rounded-md hover:bg-blue-600 font-mont cursor-not-allowed" disabled>
                                        Crop
                                    </button>
                                </div>
                                <div class="flex-grow border-t border-gray-400"></div>
                                <div class="py-3 grid grid-cols-2 gap-4 sm:text-sm m-0">
                                    <div>
                                        <button type="button" id="resetImage" class="w-full bg-red-500 text-white px-2 py-2 rounded-md hover:bg-red-600 font-mont cursor-not-allowed" disabled">
                                            Delete Image
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" id="saveImageAdd" class="w-full bg-gray-500 text-white px-2 py-2 rounded-md hover:bg-gray-600 font-mont cursor-not-allowed" disabled>
                                            Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</html>

<!-- Added Script for the Eye-Toggle View Password -->
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Function to validate password format and length
    function validatePassword() {
        const passwordInput = document.getElementById('password');
        const passwordValue = passwordInput.value;

        // Password criteria regex
        const passwordCriteria = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        // Check if password meets criteria
        if (passwordCriteria.test(passwordValue)) {
            passwordInput.classList.remove('border-red-500', 'ring-red-500', 'ring-2');
            passwordInput.classList.add('border-green-500', 'ring-green-500', 'ring-2');
        } else {
            passwordInput.classList.remove('border-green-500', 'ring-green-500', 'ring-2');
            passwordInput.classList.add('border-red-500', 'ring-red-500', 'ring-2');
        }
    }

    // Function to validate if password and confirmation match
    function validatePasswordConfirmation() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');

        // Check if both passwords match and meet the criteria
        if (passwordInput.value === passwordConfirmationInput.value && passwordInput.value.length >= 8) {
            passwordConfirmationInput.classList.remove('border-red-500', 'ring-red-500', 'ring-2');
            passwordConfirmationInput.classList.add('border-green-500', 'ring-green-500', 'ring-2');
        } else {
            passwordConfirmationInput.classList.remove('border-green-500', 'ring-green-500', 'ring-2');
            passwordConfirmationInput.classList.add('border-red-500', 'ring-red-500', 'ring-2');
        }
    }
</script>



<script>
    let cropper_add;
    let croppedBlob;
    const saveImageButton = document.getElementById('saveImageAdd');
    const cropImageButton = document.getElementById('cropImageAdd');
    const previewImage = document.getElementById('previewImageAdd');
    const emblemInput = document.getElementById('emblemAdd');
    const resetImageButton = document.getElementById('resetImage');

    // Initially disable "Crop" and "Save" buttons
    cropImageButton.disabled = true;
    cropImageButton.classList.add('bg-gray-500', 'hover:bg-gray-600');
    saveImageButton.disabled = true;
    saveImageButton.classList.add('bg-gray-500', 'hover:bg-gray-600');

    // Handle file input change
    emblemInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';

                // Enable "Crop" button after a new image is uploaded
                cropImageButton.disabled = false;
                cropImageButton.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                cropImageButton.classList.add('bg-blue-500', 'hover:bg-blue-600');

                // Destroy any previous cropper instance
                if (cropper_add) cropper_add.destroy();

                // Initialize a new Cropper instance
                cropper_add = new Cropper(previewImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1.0,
                    responsive: true,
                    zoomable: true,
                    scalable: true,
                    rotatable: true,
                });
            };
            reader.readAsDataURL(file);

            // Disable "Save" button until cropping is done
            saveImageButton.disabled = true;
            saveImageButton.classList.add('bg-gray-500', 'hover:bg-gray-600');
            saveImageButton.classList.remove('bg-green-500', 'hover:bg-green-600');
        }
    });

    // When "Crop" button is clicked
    cropImageButton.addEventListener('click', function() {
        if (cropper_add) {
            const canvas = cropper_add.getCroppedCanvas({
                width: 400,
                height: 400
            });
            canvas.toBlob(function(blob) {
                if (!blob) {
                    swal("Error", "Failed to create blob from canvas", "error");
                    return;
                }
                croppedBlob = blob;

                // Enable "Save" button after cropping
                saveImageButton.disabled = false;
                saveImageButton.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                saveImageButton.classList.add('bg-green-500', 'hover:bg-green-600');
            }, 'image/png');
        }
    });

    // When "Save" button is clicked
    // Modified Save Button Logic
    saveImageButton.addEventListener('click', function() {
        const formData = new FormData();

        if (croppedBlob) {
            // If a cropped image exists, upload it
            formData.append('emblem', croppedBlob, 'cropped_emblem.png');
        } else if (previewImage.src.includes('default_image.png')) {
            // If the preview image is the default image, send a reset flag
            formData.append('reset_image', true); // Backend will handle resetting
        } else {
            swal("Warning", "Please crop the image or reset it to the default before saving.", "warning");
            return;
        }

        // Proceed with the save process
        fetch('{{ route("admin.image_upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) throw new Error("Server error");
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    swal("Changed!", "Profile Picture has been Changed", "success")
                        .then(() => location.reload());
                } else {
                    swal("Error", result.message || "Failed to upload image.", "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error", "An error occurred. Please check the logs.", "error");
            });
    });

    // Reset Image
    resetImageButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent form submission

        // Reset the preview image to the default image
        previewImage.src = '/storage/assets/default_image.png';
        previewImage.style.display = 'block';

        // Disable the "Crop" button
        cropImageButton.disabled = true;
        cropImageButton.classList.add('bg-gray-500', 'hover:bg-gray-600');
        cropImageButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');

        // Enable the "Save" button
        saveImageButton.disabled = false;
        saveImageButton.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        saveImageButton.classList.add('bg-green-500', 'hover:bg-green-600');

        // Destroy the cropper instance if it exists
        if (cropper_add) {
            cropper_add.destroy();
            cropper_add = null;
        }

        // Clear croppedBlob to indicate no custom cropped image
        croppedBlob = null;

        // Notify user about the reset
        swal("Notice", "The image has been reset to default. Click 'Save' to confirm.", "info");
    });

    // JavaScript to control button styles based on state
function updateResetButtonState() {
        if (resetImageButton.disabled) {
            resetImageButton.classList.add('bg-gray-500', 'hover:bg-gray-600', 'cursor-not-allowed');
            resetImageButton.classList.remove('bg-red-500', 'hover:bg-red-600');
        } else {
            resetImageButton.classList.remove('bg-gray-500', 'hover:bg-gray-600', 'cursor-not-allowed');
            resetImageButton.classList.add('bg-red-500', 'hover:bg-red-600');
        }
    }

    // Initial check when the page loads
    window.addEventListener('load', () => {
        checkDefaultImage();
        updateResetButtonState();
    });

    function checkDefaultImage() {
        if (previewImage.src.includes('default_image.png')) {
            resetImageButton.disabled = true;
        } else {
            resetImageButton.disabled = false;
        }
        updateResetButtonState();
    }

    // Disable reset button if default image is already set
    function checkDefaultImage() {
        if (previewImage.src.includes('default_image.png')) {
            resetImageButton.disabled = true;
        } else {
            resetImageButton.disabled = false;
        }
    }

    // Call checkDefaultImage on page load
    window.addEventListener('load', checkDefaultImage);
</script>


<style>
    body {
        background-image: url('{{ asset($cms["url_bg_image"]) }}');
    }
</style>


<!-- Change Password Error -->
@if (Session::has('success'))
<script>
    swal("Changed.", "{{ Session::get('success') }}", 'success', {
        button: true,
        button: "OK"
    })
</script>
@endif

@if (session('error'))
<script>
    swal("Oops..", "{{ session('error') }}", "error");
</script>
@endif

<!-- Image Error -->
@if (session('success'))
<script>
    swal("Success", "{{ session('success') }}", "success");
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