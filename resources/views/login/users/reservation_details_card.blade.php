<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Tailwind CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="font-montserrat">
    <div class="p-3">
        <div class="overflow-sm ">
            <div class="inline-block min-w-full">
                <!-- Check if the item is a facility or resource -->
                @if (property_exists($item, 'id') && isset($item->id))
                <a href="{{ route('facility.overview', ['id' => $item->id]) }}" class="text-black no-underline hover:no-underline">
                    @elseif (property_exists($item, 'transaction_id') && isset($item->transaction_id))
                    <a href="{{ route('overview', ['transaction_id' => $item->transaction_id]) }}" class="text-black no-underline hover:no-underline">
                        @else
                        @endif
                        <div class="rounded-xl overflow-hidden hover:shadow-lg bg-white max-w-xs mx-auto">
                            <div class="relative">
                                <!-- Image -->
                                @php
                                $imagePath = $item->resource_image ?? $item->facility_image ?? 'default_resource.png';
                                @endphp
                                <img src="{{ asset(Storage::exists('public/' . $imagePath) ? 'storage/' . $imagePath : 'storage/assets/default_resource.png') }}" class="w-full h-48 object-cover" alt="Item Image">
                                <div class="absolute inset-0 bg-black opacity-50 z-300"></div>
                                <div class="absolute inset-0 flex items-end justify-start p-2">
                                    <div class="text-white text-lg font-bold">
                                        {{ $item->resource_name ?? $item->facility_name }} ({{ $item->transaction_id ?? $item->id }})
                                        <br>
                                        <li class="inline-block text-xs font-semibold {{ $item->status_color }}">
                                            {{ $item->status_state ?? 'No Status' }}
                                        </li>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white hover:bg-red-300 p-4">
                                <ul class="text-left p-0">
                                    @if (isset($item->pickup_datetime) && isset($item->return_datetime))

                                    <!-- Resource -->
                                    <li class="list-none text-xs m-1"><span class="font-bold">PICK-UP DATE</span></li>
                                    <li class="list-none text-xs m-1">{{ $item->pickup_datetime ?? 'N/A' }}</li>
                                    <li class="list-none text-xs m-1"><span class="font-bold">RETURN DATE</span></li>
                                    <li class="list-none text-xs m-1">{{ $item->return_datetime ?? 'N/A' }}</li>
                                    <li class="list-none text-md ml-1 mt-2 mb-0 text-slate-500"><span class="font-bold">{{ $item->department_owner ?? 'N/A' }}</span></li>
                                    @elseif (isset($item->start_datetime) && isset($item->end_datetime))

                                    <!-- Facility -->
                                    <li class="list-none text-xs m-1"><span class="font-bold">START DATE</span></li>
                                    <li class="list-none text-xs m-1">{{ $item->start_datetime ?? 'N/A' }}</li>
                                    <li class="list-none text-xs m-1"><span class="font-bold">END DATE</span></li>
                                    <li class="list-none text-xs m-1">{{ $item->end_datetime ?? 'N/A' }}</li>
                                    <li class="list-none text-md ml-1 mt-2 mb-0 text-slate-500"><span class="font-bold">{{ $item->department_owner ?? 'N/A' }}</span></li>
                                    @else
                                    <!-- Default Case if neither resource nor facility data is present -->
                                    <li class="list-none text-md ml-1 mt-2 mb-0 text-slate-500"><span class="font-bold">Department Owner: N/A</span></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </a>
            </div>
        </div>
    </div>
</body>

</html>