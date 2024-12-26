<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Calendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

</head>

<body class="bg-white-100 font-mont">
    <div class="overlay fixed top-0 left-0 w-full h-full bg-black opacity-50 hidden z-20" onclick="Close()"></div>
    <span class="absolute text-4xl top-3 left-4 cursor-pointer" onclick="Open()">
        <i class="bi bi-list"> </i>
    </span>
    <div class="sidebar shadow fixed z-20 top-0 bottom-0 lg:left-0 -left-[350px] overflow-y-auto text-center transition-all duration-300" style="width: 320px; background-color: var(--custom-color);">

        <!-- logo -->
        <div class="p-2.5 pt-0 mt-0 flex items-start justify-center relative">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="h-24 w-auto mt-3">
            <i class="bi bi-x-lg absolute top-0 right-0 mt-2 mr-2 cursor-pointer lg:hidden" onclick="Close()"></i>
        </div>

        <div class="relative mt-1 flex items-center justify-center">
            <!-- Dropdown button -->
            <button id="dropdownButton" class="hover:shadow-xl w-64 h-2 p-4 bg-white rounded-xl flex items-center justify-between">
                <p class="m-0 font-mont max-w-[175px] truncate">{{$cms['dept']}}</p>
                <!-- Icon -->
                <i id="dropdownIcon" class="bi bi-chevron-down"></i>
            </button>
            <!-- Dropdown menu -->
            <div id="dropdownMenu" class="hidden absolute mt-4 top-10 w-64 bg-white rounded-xl shadow-xl hover:shadow-xl transition-shadow overflow-y-auto max-h-40 font-mont">
                <form action="{{ route('user.change') }}" method="POST">
                    @csrf
                    <ul class="text-gray-700 w-full p-0 font-mont">
                        @if (Auth::user()->user_type == 2)
                        <li class="w-full bg-gray-200 text-center font-semibold py-3">Switch Role</li>
                        <li class="w-full cursor-pointer text-center font-mont @if(Session::get('current_role') == 1) text-green-600 hover:bg-gray-100 font-bold @else hover:bg-gray-100 @endif">
                            <button type="submit" name="role" value="1" class="w-full px-4 py-2 font-mont">
                                User
                            </button>
                        </li>
                        <li class="w-full cursor-pointer text-center font-mont @if(Session::get('current_role') == 2) text-green-600 hover:bg-gray-100 font-bold @else hover:bg-gray-100 @endif">
                            <button type="submit" name="role" value="2" class="w-full px-4 py-2 font-mont">
                                Department Admin
                            </button>
                        </li>
                        @endif
                    </ul>
                </form>
            </div>
        </div>

        <!-- MENU -->
        <p class="mt-7 uppercase text-sm text-left ml-3">Menu</p>

        <ul class="mt-3 p-0 m-0">
            <li class="{{ request()->is('admin/dashboard*') ? 'bg-white' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/dashboard2.png') }}" alt="Dashboard" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/requests*') ? 'bg-white' : '' }}">
                <a href="{{ route('admin.requests') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/department request.png') }}" alt="Department Requests" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Department Requests</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/manage_resources*') ? 'bg-white' : '' }} ">
                <a href="{{ route('admin.manage.resources') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/resources.png') }}" alt="Department Resources" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Department Resources</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/policy_management*') ? 'bg-white' : '' }} ">
                <a href="{{ route('admin.policy') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/policy management.png') }}" alt="Reservation Calendar" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Policy Management</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/department_calendar*') ? 'bg-white' : '' }}">
                <a href="{{ route('admin.calendar') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline
                    hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/calendar.png') }}" alt="Reservation Calendar" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Reservation Calendar</span>
                </a>
            </li>
        </ul>

        <!-- SETTINGS -->
        <p class="mt-7 uppercase text-sm text-left ml-3">Settings</p>

        <ul class="mt-3 p-0 m-0">
            <li class="{{ request()->is('admin/about_us*') ? 'bg-white' : '' }}">
                <a href="{{ route('admin.about') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/about.png') }}" alt="About" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">About</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/accounts*') ? 'bg-white' : '' }}">
                <a href="{{ route('admin.account') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/user.png') }}" alt="Account" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Account</span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/log out.png') }}" alt="Log Out" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <script>
        // DROPDOWN
        document.getElementById('dropdownButton').addEventListener('click', function() {
            var dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            var dropdownMenu = document.getElementById('dropdownMenu');
            var dropdownButton = document.getElementById('dropdownButton');
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        function Open() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.remove('-left-[350px]');
            sidebar.classList.add('left-0');

            var overlay = document.querySelector('.overlay');
            overlay.classList.remove('hidden');
        }

        function Close() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.remove('left-0');
            sidebar.classList.add('-left-[350px]');

            var overlay = document.querySelector('.overlay');
            overlay.classList.add('hidden');
        }

        window.addEventListener('resize', function() {
            var windowWidth = window.innerWidth;
            var overlay = document.querySelector('.overlay');
            if (windowWidth > 1024) {
                overlay.classList.add('hidden');
            }
        });
    </script>
</body>
<style>
    body {
        --custom-color: <?= $cms['accent_color'] ?>;
        background-image: url('{{ asset("$cms[url_bg_image]") }}');
        background-attachment: fixed
    }

    ::-webkit-scrollbar {
        width: 7px;
        height: 4px;
    }

    ::-webkit-scrollbar-track {
        background: #d1d1d1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

</html>