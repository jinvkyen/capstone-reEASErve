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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

</head>

<body class="bg-white-100 font-mont">
    <div class="overlay fixed top-0 left-0 w-full h-full bg-black opacity-50 hidden z-20" onclick="Close()"></div>
    <span class="absolute text-4xl top-3 left-4 cursor-pointer" onclick="Open()">
        <i class="bi bi-list"></i>
    </span>
    <div class="sidebar shadow fixed z-20 top-0 bottom-0 lg:left-0 -left-[350px] overflow-y-auto transition-all duration-300 w-[330px]" style="background-color: var(--custom-color);">

        <!-- logo -->
        <div class="p-2.5 pt-0 mt-0 flex items-start justify-center relative">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="h-24 w-auto mt-3">
            <i class="bi bi-x-lg absolute top-0 right-0 mt-2 mr-2 cursor-pointer lg:hidden" onclick="Close()"></i>
        </div>

        <div class="relative mt-1 flex items-center justify-center">
            <div class="w-64 h-2 p-3 bg-white rounded-xl flex items-center justify-center">
                <p class=" m-0  max-w-[175px] truncate">{{$cms['dept']}}</p>
            </div>
        </div>

        <!-- MENU -->
        <p class="mt-7 uppercase text-sm text-left ml-3">Menu</p>

        <ul class="mt-3 p-0 m-0 space-y-1">
            <li class="{{ request()->is('ma/dashboard*') ? 'bg-white' : '' }}">
                <a href="{{ route('ma.dashboard') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/dashboard2.png') }}" alt="Dashboard" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('ma/requests*') ? 'bg-white' : '' }}">
                <a href="{{ route('ma.requests') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/department request.png') }}" alt="Department Requests" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Department Requests</span>
                </a>
            </li>
            <li class="{{ request()->is('ma/manage_resources*') ? 'bg-white' : '' }}">
                <a href="{{ route('ma.manage.resources') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/resources.png') }}" alt="Department Resources" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Department Resources</span>
                </a>
            </li>
            <li class="{{ request()->is('ma/policy_management*') ? 'bg-white' : '' }}">
                <a href="{{ route('ma.policy') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/policy management.png') }}" alt="Reservation Calendar" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Policy Management</span>
                </a>
            </li>
            <li class="{{ request()->is('ma/department_calendar*') ? 'bg-white active' : '' }} transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 cursor-pointer">
                <a href="{{ route('ma.calendar') }}" class=" flex items-center p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 {{ request()->is('ma/department_calendar*') ? 'bg-white active' : '' }}">
                    <img src="{{ asset('storage/assets/calendar.png') }}" alt="Calendar" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">Calendar</span>
                </a>
            </li>

            <!-- Dropdown -->
            <li class="relative">
                <div class="flex items-center p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 cursor-pointer" onclick="toggleAccordion('submenu-department-management')">
                    <img src="{{ asset('storage/assets/department management.png') }}" alt="Department Management" class="w-6 h-6 mr-3 ml-4">
                    <span>Department Management</span>
                    <i class="bi bi-chevron-down ml-2"></i>
                </div>
                <ul id="submenu-department-management" class="mt-1 w-full rounded-md transition-max-height duration-200 ease-in-out {{ request()->is('ma/cms*') || request()->is('ma/account_management*') || request()->is('ma/audit*') || request()->is('ma/archive*') || request()->is('ma/feedbacks_archived*') ? 'max-h-screen' : 'max-h-0 overflow-hidden' }}">

                    <li class="{{ request()->is('ma/cms*') ? 'bg-white active' : '' }} transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 cursor-pointer">
                        <a href="{{ route('ma.cms') }}" class=" pl-5 flex items-center p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 {{ request()->is('ma/cms*') ? 'bg-white active' : '' }}">
                            <img src="{{ asset('storage/assets/content management.png') }}" alt="Content Management" class="w-6 h-6 mr-3 ml-4">
                            <span class="text-slate-950">Content Management</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('ma/account_management*') ? 'bg-white active' : '' }} transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 cursor-pointer">
                        <a href="{{ route('ma.account.management') }}" class=" pl-5 flex items-center p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 {{ request()->is('ma/account_management*') ? 'bg-white active' : '' }}">
                            <img src="{{ asset('storage/assets/account management.png') }}" alt="Account Management" class="w-6 h-6 mr-3 ml-4">
                            <span class="text-slate-950">Account Management</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('ma/audit*') ? 'bg-white active' : '' }} transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 cursor-pointer">
                        <a href="{{ route('ma.audit') }}" class=" pl-5 flex items-center p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 {{ request()->is('ma/audit*') ? 'bg-white active' : '' }}">
                            <img src="{{ asset('storage/assets/audit trail.png') }}" alt="Audit Trail" class="w-6 h-6 mr-3 ml-4">
                            <span class="text-slate-950">Audit Trail</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('ma/feedbacks_archived*') ? 'bg-white active' : '' }} transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 cursor-pointer">
                        <a href="{{ route('ma.feedbacks.archived')}}" class=" pl-5 flex items-center p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75 {{ request()->is('ma/archive*') ? 'bg-white active' : '' }}">
                            <img src="{{ asset('storage/assets/archive feedback.png') }}" alt="Audit Trail" class="w-6 h-6 mr-3 ml-4">
                            <span class="text-slate-950">Archived Feedbacks</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- SETTINGS -->
        <p class="mt-7 uppercase text-sm text-left ml-3">Settings</p>

        <ul class="mt-3 p-0 m-0 space-y-1">
            <li class="{{ request()->is('ma/about_us*') ? 'bg-white' : '' }}">
                <a href="{{ route('ma.about') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
                    <img src="{{ asset('storage/assets/about.png') }}" alt="About" class="w-6 h-6 mr-3 ml-4">
                    <span class="text-slate-950">About</span>
                </a>
            </li>
            <li class="{{ request()->is('ma/accounts*') ? 'bg-white' : '' }}">
                <a href="{{ route('ma.account') }}" class="flex p-3 w-full transition ease-in-out duration-200 no-underline hover:no-underline hover:bg-white hover:bg-opacity-75">
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
</body>

<style>
    body {
        --custom-color: <?= $cms['accent_color'] ?>;
        background-image: url('{{ asset("$cms[url_bg_image]") }}');
        background-attachment: fixed;
        scrollbar-gutter: stable !important;
    }

    ::-webkit-scrollbar {
        width: 7px;
        height: 5px;
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


    ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .transition-max-height {
        transition: max-height 0.3s ease-in-out;
    }

    .group:hover .group-hover\:block {
        display: block;
    }
</style>

</html>

<script>
    function toggleAccordion(id) {
        var element = document.getElementById(id);
        if (element.classList.contains('max-h-screen')) {
            element.classList.remove('max-h-screen');
            element.classList.add('max-h-0', 'overflow-hidden');
        } else {
            element.classList.remove('max-h-0', 'overflow-hidden');
            element.classList.add('max-h-screen');
        }
    }

    // Ensure submenu remains open if the parent item has the 'active' class
    document.addEventListener('DOMContentLoaded', function() {
        var activeSubmenu = document.querySelector('li.relative.bg-white.active > ul');
        if (activeSubmenu) {
            activeSubmenu.classList.add('max-h-screen');
            activeSubmenu.classList.remove('max-h-0', 'overflow-hidden');
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