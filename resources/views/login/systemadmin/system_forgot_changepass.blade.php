<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Include other external CSS and JavaScript files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-white-100 flex items-center justify-center h-screen font-mont">
    <div class="w-full h-full flex">
        <!-- Left Container -->
        <div class="bg-gradient-to-t from-slate-50 to-[#BEE9F0] md:w-2/5 hidden md:flex flex-col justify-between">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="h-24 w-auto p-2 self-start">

            <div class="flex flex-grow items-end justify-center">
                <img src="{{ asset('storage/assets/change-password-image.png') }}" alt="Change Password Image" class="h-81 w-auto">
            </div>
        </div>

        <!-- Right Container (New Passwords Form) -->
        <div class="responsive-bg md:shadow-xl w-full md:w-3/5 flex items-center justify-center p-8 relative">
            <div class="responsive-container w-full">
                <div class="mb-12">
                    <!-- Logo -->
                    <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="md:hidden absolute top-8 left-6 h-7 w-auto mb-6">
                    <h1 class="lg:text-4xl sm:text-2xl md:text-6xl font-bold mb-1">Reset your Password</h1>
                    <p class="lg:text-lg md:text-2lg sm:text-md text-left text-200 py-2">Enter a New Password.</p>
                </div>
                <form method="POST" action="{{ route('system.forgot.change.send') }}" id="change-form">
                    @csrf
                    <!-- Enter New Password -->
                    <div class="mb-4">
                        <div class="relative">
                            <input id="password" type="password" class="w-full p-3 border border-gray-300 rounded-lg text-lg focus:outline-none"
                                name="password" placeholder="New Password" oninput="validatePassword()">
                            <i id="togglePassword" class="fa-regular fa-eye" onclick="togglePasswordVisibility('password', 'togglePassword')"
                                style="position:absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                        <!-- Validation Error -->
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}!</p>
                        @enderror
                    </div>

                    <!-- Enter Password Confirmation -->
                    <div class="mb-4">
                        <div class="relative">
                            <input id="password_confirmation" type="password" class="w-full p-3 border border-gray-300 rounded-lg text-lg focus:outline-none"
                                name="password_confirmation" placeholder="Confirm Password" oninput="validatePasswordConfirmation()">
                            <i id="togglePasswordConfirmation" class="fa-regular fa-eye" onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation')"
                                style="position:absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                        <!-- Validation Error -->
                        @error('password_confirmation')
                        <p class="text-red-500 text-xs mb-0">{{$message}}</p>
                        @enderror
                    </div>

                    <!-- Change Pass Button -->
                    <div class="text-center mb-4" id="verify-button-div">
                        <button type="submit" class="w-80 bg-[#385F6E] hover:bg-[#243E48] text-white font-semibold p-2 rounded-lg text-lg hover:shadow-lg relative" id="verify-btn">
                            Change password
                            <span class="loader hidden absolute top-2 right-4"></span> <!-- Loader here -->
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="w-full mt-6 align-center self-start">
                        <a id="back-login" href="{{ route('logout') }}" class="text-[#385F6E] text-sm font-bold hover:text-[#243E48]">
                            <i class="bi bi-arrow-left mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<!-- SWAL Alert for Email Validation -->
@if(Session::has('error'))
<script>
    swal("Oops...", "{{ Session::get('error') }}", 'error', {
        button: true,
        button: "OK"
    });
</script>
@endif

<style>
    @media (max-width: 768px) {
        .responsive-bg {
            background: linear-gradient(to top, #F8FAFC, #BEE9F0);
        }
    }
</style>

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
</script>


<script>
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
    document.getElementById('change-form').addEventListener('submit', function() {
        const loader = document.querySelector('.loader');
        loader.classList.remove('hidden'); // Show loader
    });
</script>