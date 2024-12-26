<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.users.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h2 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Dashboard</h2>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- My Reservations -->
            <div class="flex flex-col p-4 mx-4 rounded-lg shadow-lg mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="top-0 bg-100 h-10" style="background-color: var(--custom-color);">
                    <h1 class="lg:text-3xl sm:text-2xl font-semibold">My Reservations</h1>
                </div>
                <div class="bg-100 pt-2 mt-3 overflow-x-auto custom-scrollbar sm:overflow-x-scroll rounded-lg" style="background-color: var(--custom-color);">
                    <div class="flex space-x-3 align-middle items-center">
                        @forelse ($reservations as $item)
                        <div class="w-30 lg:w-80 md:w-72 sm:w-60 flex-shrink-0">
                            @php
                            $route = $item->type === 'facility' ? route('facility.overview', ['id' => $item->id]) : route('overview', ['transaction_id' => $item->transaction_id]);
                            @endphp
                            <a href="{{ $route }}" class="text-black no-underline hover:no-underline">
                                @include('login.users.reservation_details_card', ['item' => $item])
                            </a>
                        </div>
                        @empty
                        <div class="flex w-full justify-center">
                            <h4 class="lg:text-lg sm:text-sm bg-gray-500 text-white px-2 rounded">No Current Reservations</h4>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($userData['position'] == 'Faculty')
            <!-- Start of Facilities Availability -->
            <div class="mx-4 p-4 mt-4 rounded-lg flex flex-col" style="background-color: var(--custom-color);">
                <div class="flex justify-between py-2">
                    <h1 class="lg:text-3xl sm:text-xl font-semibold">Facilities Availability</h1>
                </div>
                <!-- Facility List -->
                <div id="facility-list" class="space-y-4 bg-white p-3 sm:text-sm w-full mt-3 rounded-lg shadow-sm items-center">
                    @if ($facilities->isEmpty())
                    <p class="text-gray-500 text-center text-lg font-bold">No Facilities Available</p>
                    @else
                    @foreach ($facilities as $facility)
                    @php
                    $isUnderMaintenance = $facility->facility_status == 12; // Use aliased facility_status
                    $isActuallyAvailable = !$isUnderMaintenance;
                    $currentReservation = $facility->reservations->whereIn('status', [3, 5])->first();
                    @endphp

                    <!-- Facility Item -->
                    <div class="facility-item flex items-center justify-between lg:text-base sm:text-xs" data-is-available="{{ $isActuallyAvailable ? '1' : '0' }}" data-end-datetime="{{ optional($currentReservation)->end_datetime }}">
                        <div class="flex items-center space-x-2 xs:text-sm">
                            <span class="{{ $isActuallyAvailable ? 'bg-green-700' : 'bg-red-700' }} w-5 h-5 rounded-full mb-2"></span>
                            <div class="text-justify xs:text-sm">
                                <h2 class="font-semibold mb-0 sm:text-sm lg:text-base text-left">{{ $facility->facility_name }}</h2>
                                <p class="text-gray-600 mt-0 sm:text-sm lg:text-base text-left">Location: {{ $facility->location }}</p>
                            </div>
                        </div>

                        <p class="font-semibold {{ $isActuallyAvailable ? 'text-green-700' : 'text-gray-500' }} lg:text-base text-right sm:text-xs break-words">
                            @if ($isActuallyAvailable)
                            Available
                            @else
                            @if ($isUnderMaintenance)
                            <span class="text-red-700">Under Maintenance</span>
                            @endif
                            @endif
                        </p>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            <!-- End of Facilities Availability -->
            @endif

            <!-- Policies -->
            <div class="flex flex-col p-4 mx-4 rounded-lg shadow-lg mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="p-2 h-10" style="background-color: var(--custom-color);">
                    <h1 class="lg:text-3xl sm:text-2xl font-semibold">General Policy</h1>
                </div>
                <!-- Policy -->
                @if($policies->isEmpty())
                <div class="mt-3 mx-3 rounded-lg bg-white lg:p-6 shadow-sm flex items-center justify-center sm:p-4">
                    <h4 class="text-gray-500 mt-2 lg:text-lg sm:text-sm">No Content Yet</h4>
                </div>
                @else
                <!-- Policy List -->
                @foreach($policies as $policy)
                <div class="mt-3 rounded-lg bg-white shadow-sm flex flex-col lg:flex-row lg:p-6 sm:p-6 mb-4 relative">
                    <div class="flex absolute top-0 right-0 mt-2 space-x-2">
                        <!-- Button for Editing -->
                        <button class="fa-solid fa-pen-to-square text-2xl focus:outline-none mx-2 text-blue-600" data-toggle="modal" data-target="#editModal" data-policy-id="{{ $policy->id }}" data-policy-title="{{ $policy->policy_name }}" data-policy-description="{{ $policy->policy_content }}">
                        </button>
                        <!-- Button for Deleting -->
                        <button class="fa-solid fa-trash-can text-2xl focus:outline-none mx-2 mr-3 text-red-500" data-policy-id="{{ $policy->id }}" onclick="confirmDelete('{{ $policy->id }}')">
                        </button>
                    </div>
                    <div class="w-full lg:pl-0">
                        <h5 class="font-semibold text-lg lg:text-xl break-words mb-2">{{ $policy->policy_name }}</h5>
                        <p class="text-sm lg:text-base break-words text-justify">{{ $policy->policy_content }}</p>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</body>

</html>

@if(Session::has('success'))
<script>
    swal("Success!", "{{Session::get('success')}}", 'success', {
        button: true,
        button: "OK"
    })
</script>
@endif

@if(Session::has('error'))
<script>
    swal("Oops...", "{{Session::get('error')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif