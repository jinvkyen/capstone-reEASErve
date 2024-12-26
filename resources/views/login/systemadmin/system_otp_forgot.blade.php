<?php

use Illuminate\Support\Facades\Session;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify New Account</title>
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
                    <h2 class="lg:text-5xl sm:text-2xl md:text-6xl font-bold text-left">Account <p class="inline text-[#385F6E]">Verification</p>
                    </h2>
                    <p class="lg:text-lg md:text-2lg sm:text-sm text-left text-200 py-2 text-sm">Enter the code received on your Organizational Email Address.
                        <span class="font-semibold text-[#385F6E] underline">Please check your Inbox, Junk, Or Spam.</span>
                    </p>
                </div>
                <form method="POST" action="{{route('system.forgot.otp.send')}}">
                    @csrf
                    <!-- Enter Code -->
                    <div class="mb-8">
                        <input id="verification-code" type="number" name="token" placeholder="Enter the Code" min="1" class="w-full p-3 border border-gray-300 rounded-lg text-lg text-gray-500 font-bold">
                        <!-- Validation Error -->
                        @error('adu_email')
                        <p class=" text-red-500 text-sm mt-1">{{$message}}!</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse lg:flex-row items-center justify-between mt-8 space-y-4 lg:space-y-0 lg:space-x-4">
                        <!-- Back to Login -->
                        <div class="self-start lg:text-lg sm:text-sm w-full lg:w-auto text-left lg:pt-0 sm:pt-5">
                            <a id="back-login" href="{{ route('system.logout') }}" class="text-md font-bold text-[#385F6E] hover:text-[#243E48]">
                                <i class="bi bi-arrow-left mr-2"></i>Cancel
                            </a>
                        </div>
                        <!-- Verify Code Button -->
                        <div id="verify-button-div" class="text-center">
                            <button type="submit" class="w-60 bg-[#385F6E] hover:bg-[#243E48] text-white font-normal p-2 rounded-lg text-lg hover:shadow-lg" id="verify-btn">Verify</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<!-- SWAL Alert for Email Validation -->
@if(Session::has('errors'))
<script>
    swal("Oops...", <?= json_encode(Session::get('errors')->first()) ?>, 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif

@if(Session::has('error'))
<script>
    swal("Oops...", <?= json_encode(Session::get('error')) ?>, 'error', {
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