<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Resources {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">
</head>


<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.users.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h2 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Reserve Resources</h2>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Content -->
            <div class="bg-100 p-1 rounded-lg shadow-sm mx-4 mt-8" style="background-color: var(--custom-color);">
                <div class="text-center lg:mt-7 sm:mt-3">
                    <h1 class="font-semibold xl:text-4xl md:text-3xl sm:text-xl mx-4">Choose Type of Resource</h1>
                </div>

                <!-- Left Side -->
                <div class="flex flex-col justify-center items-center">
                    @if($userData['position'] == 'Faculty')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 lg:p-10 sm:p-5">
                        <a href="{{ route('equipment.form') }}" class="cursor-pointer">
                            <img class="drop-shadow-md hover:drop-shadow-lg w-96 h-auto" src="{{ asset('storage/assets/equipment image.png') }}" alt="Equipment">
                        </a>
                        <a href="{{ route('lab.form') }}" class="cursor-pointer">
                            <img class="drop-shadow-md hover:drop-shadow-lg w-96 h-auto" src="{{ asset('storage/assets/laboratory image.png') }}" alt="Laboratory">
                        </a>
                        <div class="flex items-center justify-center md:col-span-2">
                            <a href="{{ route('facility.form') }}" class="cursor-pointer">
                                <img class="drop-shadow-md hover:drop-shadow-lg w-96 h-auto" src="{{ asset('storage/assets/facility image.png') }}" alt="Facility">
                            </a>
                        </div>
                    </div>
                    @endif
                    @if($userData['position'] == 'Student')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 lg:p-10 sm:p-5">
                        <a href="{{ route('equipment.form') }}" class="cursor-pointer">
                            <img class="drop-shadow-md hover:drop-shadow-lg w-96 h-auto" src="{{ asset('storage/assets/equipment image.png') }}" alt="Equipment">
                        </a>
                        <a href="{{ route('lab.form') }}" class="cursor-pointer">
                            <img class="drop-shadow-md hover:drop-shadow-lg w-96 h-auto" src="{{ asset('storage/assets/laboratory image.png') }}" alt="Laboratory">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</body>

</html>