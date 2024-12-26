<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Panel</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.systemadmin.system_sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8 sm:mt-[-25px] sm:ml-[25px]">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Control Panel</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8">
                </div>
            </div>
            <h2 class="ml-[27px] lg:text-xl sm:text-2xl font-bold text-slate-500">{{$college->name ?? 'Oops.. Something is Wrong'}}</h2>

            <!-- Add Department Modal Button -->
            <div class="flex flex-col lg:w-full lg:flex-row items-start lg:justify-start lg:mt-0 mr-3 mb-2">
                <button id="newDepartmentModalButton"
                    class="font-mont text-white h-12 w-auto bg-blue-500 hover:bg-blue-800 focus:outline-none rounded-lg text-base lg:text-md text-center text-nowrap p-2 py-2 mx-4"
                    type="button" data-toggle="modal" data-target="#newDepartmentModal">Add Departments
                </button>
            </div>
            <!-- End of Add Department Modal Button-->

            <!-- START of Design for Edit Modal Form -->
            @if($departments->isEmpty())
            <h1 class="mx-4 p-4 text-gray-600 mt-2 bg-gray-300 text-center rounded-md">No Departments Available for this College.</h1>
            @else
            <div class="mx-4 p-4 rounded-lg grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" style="background-color: var(--custom-color);">
                @foreach($departments as $department)
                <!-- Department Card -->
                <div class="flex flex-col bg-slate-200 rounded-lg items-center justify-center hover:shadow-xl transition-shadow duration-200 cursor-pointer mb-3">
                    <div class="relative items-center justify-center flex flex-col min-w-full">
                        <div class="relative top-0 min-w-full h-auto rounded-full flex items-center justify-center">
                            <!-- Bg of the Departments -->
                            <div class="relative top-0 w-full h-24 flex items-center justify-center">
                                <img src="{{ asset('storage/' . ($department->bg_image ?? 'assets/default_bg.png')) }}" alt="Background Image" class="rounded-t-md min-w-full h-24 object-cover">
                            </div>
                        </div>

                        <!-- Emblem Wrapper -->
                        <div class="absolute bg-white w-30 h-30 rounded-full flex items-center justify-center mx-auto sm:top-2 md:top-28 lg:top-28 mt-20">
                            <!-- Clickable Image that opens the modal -->
                            <div class="relative cursor-pointer rounded-full border-3 border-slate-300 group" data-toggle="modal" data-target="#chairpersonModal{{ $department->department_id }}">
                                <!-- Image with hover black opacity -->
                                <img src="{{ asset('storage/' . ($department->emblem ?? 'emblem/default_emblem.png')) }}"
                                    alt="Logo Image"
                                    class="rounded-full xs:h-10 xs:w-10 sm:h-12 sm:w-12 md:h-16 md:w-16 lg:w-20 lg:h-20 object-cover object-top border-solid border-2 ">
                            </div>
                        </div>
                    </div>

                    <!-- Combined Card for Opening the Conditional Modal -->
                    <button type="button"
                        data-toggle="modal"
                        data-target="#chairpersonModal{{ $department->department_id }}"
                        class="flex font-mont bg-transparent mb-3 rounded-xl px-0 py-0 items-center justify-center lg:text-sm sm:text-xs focus:outline-none">
                        <div class="flex flex-col hover:text-blue-800 w-full text-center lg:mt-5 md:mt-5 sm:mt-5 space-y-2 space-x-2 text-xs lg:text-sm">
                            <p class="text-sm leading-none">
                                <span class="font-semibold mb-0 text-sm">
                                    {{ $department->department_name }}
                                    <i class="bi bi-pencil-square"></i> <!-- Pencil icon added beside the department name -->
                                </span><br>
                                @php
                                $head = $department->head->first();
                                @endphp
                                <span class="text-xs text-slate-400 font-thin">{{ $head ? $head->first_name . ' ' . $head->last_name : 'N/A' }}</span>
                            </p>

                            <div class="flex justify-between mt-0">
                                <div class="flex lg:px-16 md:px-12 sm:px-0">
                                    <p class="justify-start">
                                        <span class="font-bold">{{ $department->user_count ?? 0 }}</span><br>
                                        <span class="text-xs">Users</span>
                                    </p>
                                </div>
                                <div class="flex lg:px-16 md:px-12 sm:px-0">
                                    <p class="justify-end">
                                        <span class="font-bold">{{ $department->d_admin_count ?? 0 }}</span><br>
                                        <span class="text-xs">D. Admin</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Conditional Chairperson Modal -->
                <div class="modal fade" id="chairpersonModal{{ $department->department_id }}" tabindex="-1" role="dialog" aria-labelledby="chairpersonModalLabel{{ $department->department_id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold" id="chairpersonModalLabel{{ $department->department_id }}">{{ $department->department_name }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @php
                            $chairperson = $department->head->first();
                            @endphp

                            <div class="modal-body">
                                <div class="text-center">
                                    @if($chairperson)
                                    <!-- Button to trigger Edit Chairperson Modal -->
                                    <button type="button" class="font-mont font-medium w-full px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-700 transition duration-300 ease-in-out" data-toggle="modal" data-target="#editModal{{ $department->department_id }}" data-dismiss="modal">
                                        Edit Chairperson Account
                                    </button>
                                    @else
                                    <!-- Button to trigger Add Chairperson Modal -->
                                    <button type="button" class="font-mont font-medium w-full px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-700 transition duration-300 ease-in-out" data-toggle="modal" data-target="#addModal{{ $department->department_id }}" data-dismiss="modal">
                                        Add Chairperson Account
                                    </button>
                                    @endif

                                    <p class="my-2">OR</p>

                                    <!-- Button to trigger Edit Department Name Modal -->
                                    <button type="button" class="font-mont font-medium w-full px-4 py-2 text-white bg-gray-400 rounded-full hover:bg-gray-500 transition duration-300 ease-in-out" data-toggle="modal" data-target="#editDepartmentModal{{ $department->department_id }}" data-dismiss="modal">
                                        Edit Department Name
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Master Admin Modal -->
                @if($department->m_admin_count == 0)
                @include('login.systemadmin.system_add_ma', ['department' => $department])
                @endif

                <!-- Edit Master Admin Modal -->
                @if($department->m_admin_count == 1 && $department->master_admin)
                @include('login.systemadmin.system_edit_ma', ['department' => $department])
                @endif

                <!-- Edit Department Name Modal -->
                @include('login.systemadmin.system_edit_dept', ['department' => $department])

                @endforeach
                @endif

                <!-- End of Department Card -->

                <!-- Add Department Modal Form -->
                <div class="modal fade" id="newDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="viewAddDepartmentModal" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title leading-none" id="viewAddDepartmentModal">
                                    <span class="font-bold leading-none m-0 block">
                                        Add New Department<br>
                                        <!-- Truncation applied correctly inside span -->
                                        <span class="text-sm font-normal text-slate-400 m-0 w-full text-wrap">
                                            {{$college->name ?? 'Oops.. Something is Wrong'}}
                                        </span>
                                    </span>
                                </h5>
                                <button type="button" class="close focus:outline-none" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!-- Modal Body with Form -->
                            <form id="addDeptForm" action="{{route('department.add', ['collegeId' => $collegeId])}}" method="POST">
                                @csrf
                                <div class="modal-body font-mont" style="max-height: 500px;">
                                    <!-- Section 1: Deparment Name -->
                                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                                        <div class="flex flex-row lg:flex-row w-full">
                                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                                <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                                    <label class="text-black font-normal flex items-center justify-between">
                                                        <span>Deparment Name</span>
                                                        @error('message')
                                                        <p class="text-red-500 ml-2 text-xs mb-0 mt-0"></p>
                                                        @enderror
                                                    </label>
                                                    <input id="department-name" type="text" name="department_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Enter a new department" value="{{ old('department_name') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <!-- Reset Button -->
                                    <button type="reset" class="btn btn-secondary" id="resetDeptAddButton">Reset</button>
                                    <button type="submit" id="saveImage" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</body>

@if(Session::has('success'))
<script>
    swal("Success!", "{{Session::get('success')}}", 'success', {
        button: true,
        button: "OK"
    })
</script>
@endif

@if(Session::has('info'))
<script>
    swal("Information", "{{Session::get('info')}}", 'info', {
        button: true,
        button: "OK"
    })
</script>
@endif

@if(Session::has('error'))
<script>
    swal("Oops..", "{{Session::get('error')}}", 'error', {
        button: true,
        button: "OK"
    })
</script>
@endif

@if ($errors->any())
<script>
    swal({
        title: "Oops..",
        text: "@foreach ($errors->all() as $error) {{ $error }} @endforeach",
        icon: "error",
        button: "OK"
    });
</script>
@endif


<!-- Add MA Account -->
<script>
    $(document).on('submit', '#addForm', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();
        let actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    swal("Success!", response.success, "success", {
                        button: "OK",
                    }).then(() => {
                        form[0].reset(); 
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON?.error) {
                    swal("Oops..", xhr.responseJSON.error, "error", {
                        button: "OK",
                    });
                }

                // Display field-specific errors
                let errors = xhr.responseJSON?.errors || {};
                form.find('.error-message').text('').addClass('hidden'); 
                for (const [key, value] of Object.entries(errors)) {
                    form.find(`#${key}-error`).text(value[0]).removeClass('hidden');
                }
            },
        });
    });
</script>

<!-- Edit MA Account -->
<script>
    $(document).on('submit', 'form[id^="editForm-"]', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();
        let actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    swal("Success!", response.success, "success", {
                        button: "OK",
                    }).then(() => {
                        location.reload(); 
                    });
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON?.error) {
                    swal("Oops..", xhr.responseJSON.error, "error", {
                        button: "OK",
                    });
                }

                let errors = xhr.responseJSON?.errors || {};
                form.find('.error-message').text('').addClass('hidden'); 
                for (const [key, value] of Object.entries(errors)) {
                    form.find(`#${key}-error`).text(value[0]).removeClass('hidden');
                }
            },
        });
    });
</script>

<!-- JS for Edit Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize toggle buttons for all modals
        document.querySelectorAll('[id^="statusEditBtn-"]').forEach(button => {
            const cardId = button.id.split('-')[1];
            const statusEditInput = document.getElementById(`statusEditInput-${cardId}`);

            // Set initial button state
            const status = parseInt(statusEditInput.value, 10);
            if (status === 1) {
                button.innerText = 'Activated';
                button.classList.remove('bg-red-500');
                button.classList.add('bg-green-500');
            } else {
                button.innerText = 'Deactivated';
                button.classList.remove('bg-green-500');
                button.classList.add('bg-red-500');
            }

            // Toggle button functionality
            button.addEventListener('click', function() {
                if (statusEditInput.value == 1) {
                    button.innerText = 'Deactivated';
                    button.classList.remove('bg-green-500');
                    button.classList.add('bg-red-500');
                    statusEditInput.value = 0; // Set hidden input to 0
                } else {
                    button.innerText = 'Activated';
                    button.classList.remove('bg-red-500');
                    button.classList.add('bg-green-500');
                    statusEditInput.value = 1; // Set hidden input to 1
                }
            });
        });
    });
</script>

<!-- JS for Add Status Modal -->
<script>
    // Get form and reset button elements for Add Modal
    const addForm = document.getElementById('addForm');
    const resetAddButton = document.getElementById('resetOnAddButton');

    // Reset button functionality
    resetAddButton.addEventListener('click', function() {
        // Reset the form fields
        addForm.reset();
    });
</script>


<!-- JS for Add New Department Modal -->
<script>
    // Get form and reset button elements
    const addDeptForm = document.getElementById('addDeptForm');
    const resetDeptAddButton = document.getElementById('resetDeptAddButton');
    const statusDeptAddButton = document.getElementById('statusDeptAddButton');

    // Reset button functionality
    resetDeptAddButton.addEventListener('click', function() {
        // Reset the form fields
        addDeptForm.reset();

        // Reset the status button to its original state
        statusDeptAddButton.innerText = 'Active'; // Set the original text
        statusDeptAddButton.classList.remove('bg-red-500'); // Remove any other state classes
        statusDeptAddButton.classList.add('bg-green-500'); // Reset to active state
    });

    // Example toggle functionality for the status button
    statusDeptAddButton.addEventListener('click', function() {
        if (statusDeptAddButton.innerText === 'Active') {
            statusDeptAddButton.innerText = 'Inactive';
            statusDeptAddButton.classList.remove('bg-green-500');
            statusDeptAddButton.classList.add('bg-red-500');
        } else {
            statusDeptAddButton.innerText = 'Active';
            statusDeptAddButton.classList.remove('bg-red-500');
            statusDeptAddButton.classList.add('bg-green-500');
        }
    });
</script>

<!-- Added Script for the Eye-Toggle View Password -->
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId).querySelector('i');

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
    function togglePasswordVisibility(passwordId, toggleButtonId) {
        const passwordInput = document.getElementById(passwordId);
        const toggleButton = document.getElementById(toggleButtonId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.innerHTML = '<i class="fa-regular fa-eye-slash"></i>'; // Change to eye-slash icon
        } else {
            passwordInput.type = 'password';
            toggleButton.innerHTML = '<i class="fa-regular fa-eye"></i>'; // Change to eye icon
        }
    }
</script>

<style>
    @media (max-width: 768px) {

        .responsive-bg {
            background-color: #E9F3FD;
        }
    }

    /* Remove spinner arrows for number input */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

</html>