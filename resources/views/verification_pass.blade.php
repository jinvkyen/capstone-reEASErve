<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify</title>
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

</head>

<body class="bg-white-100 flex items-center justify-center h-screen font-mont">
    <div class="w-full h-full flex">
        <!-- Left Container -->
        <div class="bg-gradient-to-t from-slate-50 to-[#BEE9F0] md:w-2/5 hidden md:flex flex-col justify-between">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="h-24 w-auto p-2 self-start">

            <div class="flex flex-grow items-end justify-center">
                <img src="{{ asset('storage/assets/verification-code-image.png') }}" alt="Login Image" class="h-81 w-auto">
            </div>
        </div>

        <!-- Right Container (Verification Form) -->
        <div class="responsive-bg md:shadow-xl w-full md:w-3/5 flex items-center justify-center p-8 relative">
            <div class="responsive-container w-full lg:p-4 sm:p-0">
                <div class="mb-12">
                    <!-- Logo -->
                    <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="md:hidden absolute top-8 left-6 h-7 w-auto mb-6">
                    <h1 class="text-4xl md:text-6xl font-bold mb-1">Verify your Code</h1>
                    <p class="lg:text-lg md:text-2lg sm:text-sm text-left text-200 py-2 text-sm">Enter the code sent on your Email Address.
                        <span class="text-opacity-60 font-normal text-teal-800">Please Check your Inbox, Junk, Or Spam.</span>
                    </p>
                </div>
                <form method="POST" action="{{route('verify.pass.post')}}" id="verify-form">
                    @csrf
                    <!-- Enter Code -->
                    <div class="mb-8">
                        <input id="verification-code" type="number" name="token" placeholder="Enter the 6-digit Code" min="1" class="w-full p-3 border border-gray-300 rounded-lg text-lg text-gray-500 font-bold">
                        <!-- Validation Error -->
                        @error('adu_email')
                        <p class=" text-red-500 text-sm mt-1">{{$message}}!</p>
                        @enderror
                    </div>

                    <!-- Verify Code Button -->
                    <div class="text-center mb-6 mt-8" id="verify-button-div">
                        <button type="submit" class="w-80 bg-[#385F6E] hover:bg-[#243E48] text-white font-normal p-2 rounded-lg text-lg hover:shadow-lg relative" id="verify-btn">
                            Verify
                            <span class="loader hidden absolute top-2 right-4"></span> <!-- Loader here -->
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="w-full mt-12 align-center self-start lg:text-lg sm:text-sm">
                        <a id="back-login" href="{{ route('login') }}" class="text-gray-600 text-md font-bold hover:text-[#243E48]">
                            <i class="bi bi-arrow-left mr-2"></i>Back to Log in
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
    swal("Oops...", "{{Session::get('error')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif

<style>
    @media (max-width: 768px) {

        .responsive-bg {
            background: linear-gradient(to top, #F8FAFC, #BEE9F0);
        }
    }

    /* CSS to hide the up and down arrows */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
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
    document.getElementById('verify-form').addEventListener('submit', function() {
        const loader = document.querySelector('.loader');
        loader.classList.remove('hidden'); // Show loader
    });
</script>