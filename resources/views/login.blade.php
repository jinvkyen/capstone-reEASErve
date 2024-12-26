<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <img src="storage/assets/logo.png" alt="Logo" id="logo-pc" class="h-24 w-auto p-2 self-start opacity-0 transition-all duration-500 ease-in-out transform" draggable="false" oncontextmenu="return false;">

            <div class="flex flex-grow items-end justify-center">
                <img src="{{ asset('storage/assets/user-login-image.png') }}" alt="Login Image"
                    class="h-81 w-auto opacity-0 transition-all duration-500 ease-in-out transform"
                    id="login-image" draggable="false" oncontextmenu="return false;">
            </div>
        </div>

        <!-- Right Container (Login Form) -->
        <div class="responsive-bg md:shadow-xl w-full md:w-3/5 flex items-center justify-center p-8 relative">
            <div class="responsive-container w-full mx-5">
                <div class="mb-12">
                    <!-- Logo -->
                    <img src="{{ asset('storage/assets/logo.png') }}" draggable="false" id="logo" alt="Logo" class="md:hidden absolute top-8 left-6 h-14 w-auto mb-6 opacity-0 transition-all duration-500 ease-in-out transform">
                    <h1 class="text-4xl md:text-6xl font-bold mb-1">Welcome Back</h1>
                    <p class="text-xl md:text-2lg text-200">Login Your Account</p>
                </div>
                <form method="POST" action="{{route('login.submit')}}" id="login-form">
                    @csrf

                    <!-- For Disabling Auto Complete -->
                    <input type="text" name="fake-username" style="display: none;" autocomplete="off">
                    <input type="password" name="fake-password" style="display: none;" autocomplete="off">

                    <!-- Username -->
                    <div class="mb-4">
                        <input id="login-field" type="text" name="username" placeholder="Username" value="{{ session('username') ?? Cookie::get('remember_user_username') }}" class="w-full p-3 border border-gray-300 rounded-lg" autocomplete="off">
                        @error('username')
                        <p class=" text-red-500 text-sm mt-1">{{$message}}!</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <div class="relative">
                            <input id="password" type="password" class="w-full p-3 border border-gray-300 rounded-lg text-lg" name="password" placeholder="Password" autocomplete="off">
                            <i id="togglePassword" class="fa-regular fa-eye" onclick="togglePasswordVisibility('password', 'togglePassword')" style="position:absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                        <!-- Validation Error -->
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}!</p>
                        @enderror

                        @if(Session::has('error'))
                        <p class="text-red-500 text-sm mt-1">{{Session::get('error')}}!</p>
                        @endif
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center">
                            <input class="form-check-input ml-2" type="checkbox" name="remember_me" id="remember_me" class="mr-2">
                            <label for="remember_me" class="text-gray-600 ml-2">Remember Me</label>
                        </div>
                        <p class="text-200">Don't Have an Account? <a href="{{route('register.index')}}" class="text-[#385F6E] md:underline-offset-4 hover:underline">Register Here</a></p>
                        <a href="{{route('forgot.pass')}}" class="text-[#385F6E] md:underline-offset-4 hover:underline">Forgot Password?</a>
                    </div>

                    <!-- Login Button with Loader -->
                    <div class="text-center mb-4">
                        <button type="submit" class="w-80 bg-[#385F6E] hover:bg-[#243E48] text-white p-2 rounded-lg text-lg hover:shadow-lg relative">
                            Login
                            <span class="loader hidden absolute top-2 right-4"></span> <!-- Loader here -->
                        </button>
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

    <!--Success Validation -->
    @if(Session::has('success'))
    <script>
        swal("Success!", "{{Session::get('success')}}", 'success', {
            button: true,
            button: "OK"
        });
    </script>
    @endif

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
        document.addEventListener('DOMContentLoaded', function() {
            let image = document.getElementById('login-image');
            let logo = document.getElementById('logo');
            let logopc = document.getElementById('logo-pc');

            // Trigger zoom-in after a slight delay to ensure the image is loaded
            setTimeout(() => {
                image.classList.remove('opacity-30');
                image.classList.add('scale-100', 'opacity-100');

                logo.classList.remove('opacity-30');
                logo.classList.add('scale-100', 'opacity-100');

                logopc.classList.remove('opacity-30');
                logopc.classList.add('scale-100', 'opacity-100');
            }, 10); // Adjust the delay as needed
        });
    </script>

    <style>
        @media (max-width: 768px) {

            .responsive-bg {
                background: linear-gradient(to top, #F8FAFC, #BEE9F0);
            }
        }
    </style>

    <!-- Loader -->
    <style>
        .loader {
            color: #fff;
            font-size: 4.5px;
            width: 0.7em;
            height: 0.7em;
            border-radius: 50%;
            position: absolute;
            left: 53em;
            top: 50%;
            transform: translateY(-50%);
            text-indent: -9999em;
            animation: mulShdSpin 1.3s infinite linear;
        }

        @keyframes mulShdSpin {

            0%,
            100% {
                box-shadow: 0 -3em 0 0.2em,
                    2em -2em 0 0em, 3em 0 0 -1em,
                    2em 2em 0 -1em, 0 3em 0 -1em,
                    -2em 2em 0 -1em, -3em 0 0 -1em,
                    -2em -2em 0 0;
            }

            12.5% {
                box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em,
                    3em 0 0 0, 2em 2em 0 -1em, 0 3em 0 -1em,
                    -2em 2em 0 -1em, -3em 0 0 -1em,
                    -2em -2em 0 -1em;
            }

            25% {
                box-shadow: 0 -3em 0 -0.5em,
                    2em -2em 0 0, 3em 0 0 0.2em,
                    2em 2em 0 0, 0 3em 0 -1em,
                    -2em 2em 0 -1em, -3em 0 0 -1em,
                    -2em -2em 0 -1em;
            }

            37.5% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em,
                    3em 0em 0 0, 2em 2em 0 0.2em, 0 3em 0 0em,
                    -2em 2em 0 -1em, -3em 0em 0 -1em, -2em -2em 0 -1em;
            }

            50% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em,
                    3em 0 0 -1em, 2em 2em 0 0em, 0 3em 0 0.2em,
                    -2em 2em 0 0, -3em 0em 0 -1em, -2em -2em 0 -1em;
            }

            62.5% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em,
                    3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 0,
                    -2em 2em 0 0.2em, -3em 0 0 0, -2em -2em 0 -1em;
            }

            75% {
                box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em,
                    3em 0em 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em,
                    -2em 2em 0 0, -3em 0em 0 0.2em, -2em -2em 0 0;
            }

            87.5% {
                box-shadow: 0em -3em 0 0, 2em -2em 0 -1em,
                    3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em,
                    -2em 2em 0 0, -3em 0em 0 0, -2em -2em 0 0.2em;
            }
        }
    </style>

    <script>
        document.getElementById('login-form').addEventListener('submit', function() {
            const loader = document.querySelector('.loader');
            loader.classList.remove('hidden'); // Show loader
        });
    </script>
</body>