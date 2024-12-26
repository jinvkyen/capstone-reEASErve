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
        <div class="bg-gradient-to-t from-slate-50 to-[#BEE9F0] md:w-2/5 hidden md:flex flex-col justify-between">
            <img src="storage/assets/logo.png" alt="Logo" class="h-24 w-auto p-2 self-start" draggable="false" oncontextmenu="return false;">

            <div class="flex flex-grow items-end justify-center">
                <img src="{{ asset('storage/assets/registration.png') }}" alt="Login Image" class="h-81 w-auto" draggable="false" oncontextmenu="return false;">
            </div>
        </div>

        <!-- Right Container (Registration Form) -->
        <div class="responsive-bg w-full md:w-4/5 flex items-center justify-center mb-0 max-w-screen-lg">
            <div class="responsive-container w-full lg:py-2 lg:px-8 sm:p-4">
                <div class="mb-0 mt-5">
                    <!-- Logo -->
                    <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="md:hidden absolute top-8 left-6 h-14 w-auto mb-6">
                    <h1 class="lg:text-4xl sm:text-2xl md:text-6xl font-bold text-center">Welcome to <p class="inline text-[#385F6E]">ReEASErve</p>
                    </h1>
                    <p class="lg:text-base sm:text-xs md:text-2lg text-200 text-center mx-4">Create an Account to <span class="text-[#385F6E]">Manage, Reserve</span> and <span class="text-[#385F6E]">Borrow</span> your Organization's Resources with Ease! </p>
                </div>
                <form method="POST" action="{{route('register.create')}}" id="login-form">
                    @csrf

                    <div class="items-center justify-normal">
                        <!-- Left Side (section 1): Departments and Roles -->
                        <div class="flex flex-row p-2 mx-2 sm:text-sm">
                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                    <label class="text-black font-normal flex items-center justify-between">
                                        <span>Select Department <span class="text-red-500">*</span></span>
                                        @error('departments')
                                        <span class="text-red-500 text-xs mb-0">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select id="dropdown-button" name="departments" type="number" class="w-full p-2 border border-gray-300 rounded-lg font-mont">
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($departments as $department)
                                        @if ($department->department_id != '0')
                                        <option value="{{ $department->department_name }}" {{ old('departments') == $department->department_name ? 'selected' : '' }}>{{ $department->department_name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                    <label class="text-black font-normal flex items-center justify-between">
                                        <span>Select Position <span class="text-red-500">*</span></span>
                                        @error('position')
                                        <span class="text-red-500 text-xs mb-0">{{ $message }}!</span>
                                        @enderror
                                    </label>
                                    <select id="dropdown-button" name="position" type="number" class="w-full p-2 border border-gray-300 rounded-lg font-mont">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Student" {{ old('position') == 'Student' ? 'selected' : '' }}>Student</option>
                                        <option value="Faculty" {{ old('position') == 'Faculty' ? 'selected' : '' }}>Faculty</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!-- Left Side (section 2): First and Last Name -->
                        <div class="flex flex-row p-2 mx-2 sm:text-sm">
                            <div class="flex flex-row lg:flex-row w-full">
                                <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label class="text-black font-normal flex items-center justify-between">
                                            <span>First Name <span class="text-red-500">*</span></span>
                                            @error('first_name')
                                            <p class="text-red-500 ml-2 text-xs mb-0 mt-0">{{$message}}</p>
                                            @enderror
                                        </label>
                                        <input id="login-field" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="first_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="First Name" value="{{ old('first_name') }}">
                                    </div>
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label class="text-black font-normal flex items-center justify-between">
                                            <span>Last Name <span class="text-red-500">*</span></span>
                                            @error('last_name')
                                            <p class="text-red-500 ml-2 text-xs mb-0 mt-0">{{$message}}</p>
                                            @enderror
                                        </label>
                                        <input id="login-field" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="last_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Last Name" value="{{ old('last_name') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Left Side (section 3): Student Number -->
                        <div class="flex flex-row p-2 mx-2 sm:text-sm">
                            <div class="flex flex-row lg:flex-row w-full">
                                <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label class="text-black font-normal flex items-center justify-between">
                                            <span>Student Number <span class="text-red-500">*</span></span>
                                            @error('user_id')
                                            <p class="text-red-500 ml-2 text-xs mb-0 mt-0">{{$message}}</p>
                                            @enderror
                                        </label>
                                        <input id="login-field" type="number" name="user_id" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Student or Employee Number" oninput="this.value = Math.abs(this.value)" value="{{ old('user_id') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Left Side (section 4): Email -->
                        <div class="flex flex-row p-2 mx-2 sm:text-sm">
                            <div class="flex flex-row lg:flex-row w-full">
                                <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label class="text-black font-normal flex items-center justify-between">
                                            <span>Email Address <span class="text-red-500">*</span></span>
                                            @error('email')
                                            <p class="text-red-500 ml-2 text-xs mb-0 mt-0">{{$message}}</p>
                                            @enderror
                                        </label>
                                        <input id="login-field" type="email" name="email" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="{{ $cms->email }}" value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Left Side (section 5): Password -->
                        <div class="flex flex-row p-2 mx-2 sm:text-sm">
                            <div class="flex flex-row lg:flex-row w-full">
                                <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label for="password" class="text-black font-normal flex items-center justify-between">
                                            <span>Password <span class="text-red-500">*</span></span>
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
                                                value="{{ old('password') }}"
                                                oninput="validatePassword()">
                                            <button
                                                type="button"
                                                id="togglePassword"
                                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"
                                                onclick="togglePasswordVisibility('password', 'togglePassword')">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                        </div>
                                        <p class="text-gray-400 text-xs">Must Contain a Number, a Special Character, and a Capital Letter.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Left Side (section 6): Confirm Password -->
                            <div class="flex flex-row lg:flex-row w-full">
                                <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label for="password_confirmation" class="text-black font-normal flex items-center justify-between">
                                            <span>Confirm Password <span class="text-red-500">*</span></span>
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
                                                value="{{ old('password_confirmation') }}"
                                                oninput="validatePasswordConfirmation()">
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
                    </div>

                    <!-- Left Side (section 7): Buttons -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex lg:flex-col w-full sm:flex-col md:flex-col justify-evenly">
                                <div class="p-1 lg:w-full sm:w-full sm:hidden lg:flex md:w-full align-center self-start mt-7">
                                    <!-- Back to Login -->
                                    <a id="back-login" href="{{ route('login') }}" class="text-[#385F6E] lg:text-lg sm:text-sm font-bold hover:text-[#243E48] no-underline font-mont">
                                        <i class="bi bi-arrow-left mr-2"></i>Back to Log in
                                    </a>
                                </div>
                                <div class="p-1 lg:w-full sm:w-full md:w-full items-center justify-end">
                                    <div class="flex justify-center">
                                        <input type="checkbox" id="tc" class="h-5 w-5 mr-2" name="tc" required>
                                        <a id="policy" class="text-[#385F6E] no-underline hover:underline cursor-pointer font-mont" data-bs-toggle="modal" data-bs-target="#exampleModal">Agree to Terms and Conditions</a>
                                    </div>


                                    <!-- Register Button with Loader -->
                                    <div class="flex justify-center mt-2 ml-3">
                                        <button type="submit" class="w-80 bg-[#385F6E] hover:bg-[#243E48] text-white p-2 rounded-lg text-lg hover:shadow-lg font-mont relative">
                                            Register
                                            <span class="loader hidden absolute top-2 right-4"></span> <!-- Loader here -->
                                        </button>
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
                                    <a id="back-login" href="{{ route('login') }}" class="text-[#385F6E]  lg:text-lg sm:text-sm font-bold hover:text-[#243E48] no-underline">
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
        // Function to toggle password visibility
        function togglePasswordVisibility(passwordFieldId, toggleButtonId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleButton = document.getElementById(toggleButtonId);
            const icon = toggleButton.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
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
    </script>

    <style>
        @media (max-width: 768px) {

            .responsive-bg {
                background: linear-gradient(to top, #F8FAFC, #BEE9F0);
            }
        }

        /* Remove spinner arrows for number input */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <style>
        .loader {
            position: absolute;
            border: 4px solid;
            border-color: #fff transparent #fff transparent;
            animation: rotate 2s linear infinite;
            border-radius: 50%;
            width: 24px;
            height: 24px;
        }

        .loader:before,
        .loader:after {
            content: "";
            display: block;
            border: 4px solid transparent;
            border-left-color: #fff;
            position: absolute;
            left: -24px;
            top: -24px;
            animation: mvx 1s infinite linear;
        }

        .loader:before {
            border-color: transparent #fff transparent transparent;
            animation-name: mvrx;
            animation-delay: 0.5s;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes mvx {

            20%,
            80% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(-50%);
            }
        }

        @keyframes mvrx {

            20%,
            80% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(50%);
            }
        }
    </style>

    <script>
        document
            .getElementById("login-form")
            .addEventListener("submit", function() {
                const loader = document.querySelector(".loader");
                loader.classList.remove("hidden"); // Show loader
            });
    </script>
</body>

</html>