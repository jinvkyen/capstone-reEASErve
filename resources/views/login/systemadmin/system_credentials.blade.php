<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Include other external CSS and JavaScript files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-white-100 flex items-center justify-center h-screen font-mont">
    <div class="w-full h-full flex">
        <!-- Left Container -->
        <div class="bg-gradient-to-b from-slate-50 to-[#BEE9F0] p-8 md:w-2/5 hidden md:flex flex-col justify-between">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="h-10 w-auto mb-6 self-start">
            <div class="flex flex-grow items-center justify-center">
                <img src="{{ asset('storage/assets/registration.png') }}" alt="Login Image" class="h-80 w-auto">
            </div>
        </div>

        <!-- Right Container (Registration Form) -->
        <div class="responsive-bg md:shadow-xl w-full md:w-3/5 flex items-center justify-center p-8 relative">
            <div class="responsive-container w-full lg:p-4 sm:p-0">
                <div class="mb-8">
                    <!-- Logo -->
                    <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="md:hidden absolute top-8 left-6 h-7 w-auto mb-6">
                    <h2 class="lg:text-5xl sm:text-2xl md:text-6xl font-bold text-left">Account <p class="inline text-teal-800">Credentials</p>
                    </h2>
                    <p class="lg:text-lg md:text-2lg sm:text-sm text-left text-200 py-2 text-sm">Enter your personal information and credentials to complete your account.</p>
                </div>

                <form method="POST" action="{{route('system.credentials')}}">
                    @csrf
                    <!-- Section 1: First and Last Name -->
                    <div class="flex flex-row sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex flex-col w-full">
                                <div class="p-1 w-full relative">
                                    <label class="text-black font-normal flex items-center justify-between">
                                        <span>First Name<span class="text-red-500">*</span></span>
                                        @error('first_name')
                                        <p class="text-red-500 ml-2 text-xs mb-0 mt-0">{{$message}}</p>
                                        @enderror
                                    </label>
                                    <input id="login-field" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="first_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="First Name" value="{{ old('first_name') }}">
                                </div>
                                <div class="p-1 w-full relative">
                                    <label class="text-black font-normal flex items-center justify-between">
                                        <span>Last Name<span class="text-red-500">*</span></span>
                                        @error('last_name')
                                        <p class="text-red-500 ml-2 text-xs mb-0 mt-0">{{$message}}</p>
                                        @enderror
                                    </label>
                                    <input id="login-field" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="last_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Last Name" value="{{ old('last_name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 2: Password -->
                    <div class="flex flex-col sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                <div class="p-1 w-full sm:w-full md:w-full relative">
                                    <label for="password" class="text-black font-normal flex items-center justify-between">
                                        <span>Password<span class="text-red-500">*</span></span>
                                        @error('password')
                                        <p class="text-red-500 text-xs mb-0">{{$message}}</p>
                                        @enderror
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            class="w-full p-2 pr-10 border border-gray-300 rounded-lg font-mont focus:outline-none"
                                            placeholder="Password"
                                            value="{{ old('password') }}" oninput="validatePassword()">
                                        <button
                                            type="button"
                                            id="togglePassword"
                                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"
                                            onclick="togglePasswordVisibility('password', 'togglePassword')">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Confirm Password -->
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                <div class="p-1 w-full sm:w-full md:w-full relative">
                                    <label for="password_confirmation" class="text-black font-normal flex items-center justify-between">
                                        <span>Confirm Password<span class="text-red-500">*</span></span>
                                        @error('password_confirmation')
                                        <p class="text-red-500 text-xs mb-0">{{$message}}</p>
                                        @enderror
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            class="w-full p-2 pr-10 border border-gray-300 rounded-lg font-mont focus:outline-none"
                                            placeholder="Confirm Password"
                                            value="{{ old('password_confirmation') }}" oninput="validatePasswordConfirmation()">
                                        <button
                                            type="button"
                                            id="togglePasswordConfirmation"
                                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"
                                            onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation')">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Buttons -->
                    <div class="flex flex-row sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex lg:flex-row w-full sm:flex-col md:flex-col justify-evenly">
                                <div class="p-1 lg:w-8/12 sm:w-full sm:hidden lg:flex md:w-full align-center self-start mt-7">
                                    <!-- Back to Login -->
                                    <a id="back-login" href="{{ route('system.logout') }}" class="text-gray-600 lg:text-lg sm:text-sm font-bold hover:text-teal-800 no-underline font-mont">
                                        <i class="bi bi-arrow-left mr-2"></i>Back to Log in
                                    </a>
                                </div>
                                <div class="p-1 mt-2 lg:w-8/12 sm:w-full md:w-full md:justify-center sm:justify-center">
                                    <div class="flex lg:justify-end ">
                                        <input type="checkbox" id="tc" class="h-5 w-5 mr-2" name="tc" required>
                                        <a id="policy" class="text-teal-800 no-underline hover:underline cursor-pointer font-mont" data-bs-toggle="modal" data-bs-target="#exampleModal">Agree to Terms and Conditions</a>
                                    </div>
                                    <div class="flex lg:justify-end mt-2 sm:justify-center">
                                        <button id="register-btn" type="submit" class="w-75 bg-[#385F6E] hover:bg-[#243E48] text-white p-2 rounded-lg text-lg hover:shadow-lg font-mont">Submit</button>
                                    </div>
                                </div>
                                <!-- Policy Content -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable w-full max-w-lg">
                                        <div class="modal-content rounded-lg shadow-lg">
                                            <div class="modal-header flex justify-between items-center border-b border-gray-200 p-4">
                                                <h5 class="modal-title text-xl font-medium font-mont" id="exampleModalLabel">Terms and Conditions</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-6 max-h-80 overflow-y-auto">
                                                <h2 class="font-bold text-gray-700 text-center font-mont">{{$registration_policies->title}}</h2>
                                                <p class="text-sm text-gray-700 text-justify p-3 font-mont">{{$registration_policies->content}}</p>
                                            </div>
                                            <div class="modal-footer border-t border-gray-200 p-4 flex justify-end space-x-4">
                                                <button type="button" class="bg-[#385F6E] hover:bg-[#243E48] text-white py-2 px-4 rounded font-mont" data-bs-dismiss="modal" onclick="acceptTerms()">Accept</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-1 lg:w-8/12 sm:w-full sm:flex lg:hidden md:w-full align-center self-start mt-3">
                                    <!-- Back to Login -->
                                    <a id="back-login" href="{{ route('system.login') }}" class="text-gray-600 text-md font-bold hover:text-teal-800">
                                        <i class="bi bi-arrow-left mr-2"></i>Back to Log in
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Registration Successful -->
    @if(Session::has('message'))
    <script>
        swal("Welcome!", "{{Session::get('message')}}", 'success', {
            button: true,
            button: "OK"
        });
    </script>
    @endif

    <!-- Error -->
    @if(Session::has('error'))
    <script>
        swal("Registration Error", "{{Session::get('error')}}", 'error', {
            button: true,
            button: "OK"
        })
    </script>
    @endif

    <script>
        function acceptTerms() {
            var checkbox = document.getElementById('tc');
            checkbox.checked = true;
        }
    </script>

    <!-- Added Script for the Eye-Toggle View Password -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId).querySelector('i');

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
                passwordInput.classList.remove('ring-red-500', 'border-red-500', 'ring-2');
                passwordInput.classList.add('ring-green-500', 'border-green-500', 'ring-2');
            } else {
                passwordInput.classList.remove('ring-green-500', 'border-green-500', 'ring-2');
                passwordInput.classList.add('ring-red-500', 'border-red-500', 'ring-2');
            }
        }

        // Function to validate if password and confirmation match
        function validatePasswordConfirmation() {
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');

            // Check if both passwords match and meet the criteria
            if (passwordInput.value === passwordConfirmationInput.value && passwordInput.value.length >= 8) {
                passwordConfirmationInput.classList.remove('ring-red-500', 'ring-2');
                passwordConfirmationInput.classList.add('ring-green-500', 'ring-2');
            } else {
                passwordConfirmationInput.classList.remove('ring-green-500', 'ring-2');
                passwordConfirmationInput.classList.add('ring-red-500', 'ring-2');
            }
        }
    </script>

    <style>
        @media (max-width: 768px) {

            .responsive-bg {
                background-color: #E9F3FD;
            }
        }

        /* Remove spinner arrows for number input */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</body>

</html>