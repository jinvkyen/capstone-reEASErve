<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.deptadmin.dept_admin_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 text-4xl font-bold">About</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <!-- Overview Container -->
            <div class="mx-4 mt-8 p-3 rounded-lg shadow-md" style="background-color: var(--custom-color);">
                <div class="overflow-x-auto">
                    <nav class="flex space-x-4 font-semibold" aria-label="Tabs">
                        <a class="px-4 py-2 font-medium lg:text-base md:text-sm sm:text-xs bg-white no-underline cursor-pointer tab-link" data-filter="about" aria-current="page">
                            {{ $aboutContent->header ?? 'What is ReEASErve' }}
                        </a>
                        <a class="px-4 py-2 font-medium lg:text-base md:text-sm sm:text-xs text-gray-500 no-underline cursor-pointer tab-link" data-filter="how-to">
                            How to use ReEaserve?
                        </a>
                        <a class="px-4 py-2 font-medium lg:text-base md:text-sm sm:text-xs text-gray-500 no-underline cursor-pointer tab-link" data-filter="sidebar-list">
                            Sidebar List
                        </a>
                        <a class="px-4 py-2 font-medium lg:text-base md:text-sm sm:text-xs text-gray-500 no-underline cursor-pointer tab-link" data-filter="terms-cond">
                            Terms and Condition
                        </a>
                    </nav>
                </div>


                <!-- Content Sections -->
                <div class="content-section" data-filter="about">
                    <p class="text-justify p-3 bg-white rounded-lg my-4">{{ $aboutContent->content ?? 'ReEserve is a resource reservation and borrowing management system that allows users to easily access non-consumable resources within college of science departments. Specifically designed to fulfill the educational demands of students and faculty members while also assisting the management process of the aforementioned collage administration.' }}</p>
                </div>

                <!-- How to Use -->
                <div class="content-section" data-filter="how-to" style="display: none;">
                    <div class="flex lg:flex-row justify-around flex-col my-4">
                        <div class="bg-white p-3 rounded-lg shadow-sm text-center mx-1 mb-2">
                            <h6 class="font-semibold">Reserve <i class="bi bi-pencil-square"></i></h6>
                            <p>Make reservation of your desired resources</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm text-center mx-1 mb-2">
                            <h6 class="font-semibold">Borrow <i class="bi bi-arrow-right"></i></h6>
                            <p>Once approved, pick up the resources at department</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm text-center mx-1 mb-2">
                            <h6 class="font-semibold">Return <i class="bi bi-arrow-return-left"></i></h6>
                            <p>After usage, return the resources to department</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar List -->
                <div class="content-section" data-filter="sidebar-list" style="display: none;">
                    <ul class="p-0 my-4">
                        <li class="bg-white p-3 rounded-lg shadow-sm mb-3 flex items-center">
                            <img src="{{ asset('storage/assets/dashboard2.png') }}" alt="Dashboard Icon" class="w-5 h-5 mr-3">
                            <div>
                                <div>Dashboard</div>
                                <div>Resource and borrowing data reports, availability of facilities, and general policy of reosurces.</div>
                            </div>
                        </li>
                        <li class="bg-white p-3 rounded-lg shadow-sm mb-3 flex items-center">
                            <img src="{{ asset('storage/assets/department request.png') }}" alt="Reserved Resources Icon" class="w-5 h-5 mr-3">
                            <div>
                                <div>Department Requests</div>
                                <div>Manage user's request and transactions.</div>
                            </div>
                        </li>
                        <li class="bg-white p-3 rounded-lg shadow-sm mb-3 flex items-center">
                            <img src="{{ asset('storage/assets/resources.png') }}" alt="My Reservations Icon" class="w-5 h-5 mr-3">
                            <div>
                                <div>Department Resources</div>
                                <div>Manage departmental reosurces and track their statuses.</div>
                            </div>
                        </li>
                        <li class="bg-white p-3 rounded-lg shadow-sm mb-3 flex items-center">
                            <img src="{{ asset('storage/assets/policy management.png') }}" alt="My Reservations Icon" class="w-5 h-5 mr-3">
                            <div>
                                <div>Policy Management</div>
                                <div>Manage resource policies.</div>
                            </div>
                        </li>
                        <li class="bg-white p-3 rounded-lg shadow-sm mb-3 flex items-center">
                            <img src="{{ asset('storage/assets/calendar.png') }}" alt="Reservation Calendar Icon" class="w-5 h-5 mr-3">
                            <div>
                                <div>Reservation Calendar</div>
                                <div>Monitor reservation's schedules.</div>
                            </div>
                        </li>
                        <li class="bg-white p-3 rounded-lg shadow-sm mb-3 flex items-center">
                            <img src="{{ asset('storage/assets/about.png') }}" alt="About Icon" class="w-5 h-5 mr-3">
                            <div>
                                <div>About</div>
                                <div>System information.</div>
                            </div>
                        </li>
                        <li class="bg-white p-3 rounded-lg shadow-sm flex items-center">
                            <img src="{{ asset('storage/assets/user.png') }}" alt="Account Icon" class="w-5 h-5 mr-3">
                            <div>
                                <div>Account</div>
                                <div>Manage your account settings.</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Terms and Condition -->
                <div class="content-section" data-filter="terms-cond" style="display: none;">
                    <div class="bg-white p-3 rounded-lg shadow-sm my-4">
                        <p><b>1. Acceptance of Terms: </b>By accessing or using the ReEaserve system, you agree to comply with these terms and conditions.</p>
                        <p><b>2. Registration: </b>Users must register with valid credentials to access the ReEaserve system.</p>
                        <p><b>3. Resource Reservation: </b>Users can reserve non-consumable resources within the college of science departments.</p>
                        <p><b>4. Usage Guidelines: </b>Use reserved resources responsibly according to college and department policies.</p>
                        <p><b>5. Limits: </b>Reservation limits may apply based on user status and resource availability.</p>
                        <p><b>6. Privacy Policy: </b>Personal information collected during the reservation process will be handled in accordance with the ReEaserve Privacy Policy.</p>
                        <p><b>7. System Availability: </b>ReEaserve may be unavailable temporarily for maintenance or upgrades.</p>
                        <p><b>8. Contact Information: </b>For questions and service concerns regarding the system, please approach the <b>System Administrator</b> for assistance.</p>
                        <p><b>9. Modification of Terms: </b>ReEaserve reserves the right to modify these terms and conditions at any time without prior notice.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<!-- JavaScript to Handle Tabs -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-link');
        const contents = document.querySelectorAll('.content-section');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Update tab active states
                tabs.forEach(t => {
                    t.classList.toggle('bg-white', t === tab);
                    t.classList.toggle('bg-white', t === tab);
                    t.classList.toggle('text-gray-500', t !== tab);
                    t.classList.toggle('border-transparent', t !== tab);
                });

                // Show/Hide content sections based on selected tab
                contents.forEach(content => {
                    content.style.display = content.getAttribute('data-filter') === filter ? 'block' : 'none';
                });
            });
        });
    });
</script>

</html>
