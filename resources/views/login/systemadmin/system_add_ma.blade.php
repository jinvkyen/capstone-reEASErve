<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<div class="modal fade" id="addModal{{ $department->department_id }}" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title leading-none" id="addModalLabel{{ $department->department_id }}">
                    <span class="font-bold leading-none m-0 block">
                        Add Chairperson Account<br>
                        <span class="text-sm font-normal text-slate-400 m-0 w-full text-wrap">
                            {{ $department->department_name }}
                        </span>
                    </span>
                </h5>
                <button type="button" class="close focus:outline-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body with Form -->
            <form id="addForm" action="{{ route('system.master.add') }}" method="POST">
                @csrf
                <input type="hidden" name="dept_name" value="{{ $department->department_name }}">
                <div class="modal-body font-mont" style="max-height: 500px;">
                    <!-- Section 1: Employee ID -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row w-full">
                            <div class="p-1 w-full relative">
                                <span>Employee Number<span class="text-red-500">*</span></span>
                                <input id="login-field" type="number" name="user_id" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Employee Number" value="{{ old('user_id') }}">
                                <p id="user_id-error" class="text-red-500 ml-2 text-xs mb-0 mt-0 hidden"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: First and Last Name -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row w-full">
                            <div class="p-1 w-8/12 relative">
                                <label class="text-black font-normal flex items-center justify-between">
                                    <span>First Name<span class="text-red-500">*</span></span>
                                </label>
                                <input id="login-field" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="first_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="First Name" value="{{ old('first_name') }}">
                                <p id="first_name-error" class="text-red-500 ml-2 text-xs mb-0 mt-0 hidden"></p>
                            </div>
                            <div class="p-1 w-8/12 relative">
                                <label class="text-black font-normal flex items-center justify-between">
                                    <span>Last Name<span class="text-red-500">*</span></span>
                                </label>
                                <input id="login-field" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="last_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Last Name" value="{{ old('last_name') }}">
                                <p id="last_name-error" class="text-red-500 ml-2 text-xs mb-0 mt-0 hidden"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Password -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row w-full">
                            <div class="p-1 w-full relative">
                                <label for="password" class="text-black font-normal flex items-center justify-between">
                                    <span>Password<span class="text-red-500">*</span></span>
                                </label>
                                <div class="relative">
                                    <input id="passwordAdd" type="password" name="password" class="w-full p-2 pr-10 border border-gray-300 rounded-lg font-mont" placeholder="Password" value="{{ old('password') }}" oninput=" validatePassword()">
                                    <button type=" button" id="togglePasswordAdd" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500" onclick="togglePasswordVisibility('passwordAdd', 'togglePasswordAdd')">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                <p id="password-error" class="text-red-500 text-xs mb-0 hidden"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Email Address -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row w-full">
                            <div class="p-1 w-full relative">
                                <label class="text-black font-normal flex items-center justify-between">
                                    <span>Email Address<span class="text-red-500">*</span></span>
                                </label>
                                <input id="login-field" type="email" name="email" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="@adamson.edu.ph" value="{{ old('email') }}">
                                <p id="email-error" class="text-red-500 ml-2 text-xs mb-0 mt-0 hidden"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" id="resetButton">Reset</button>
                    <button type="submit" id="saveImage" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to validate password format and length
    function validatePassword() {
        const passwordInput = document.getElementById('password');
        const passwordValue = passwordInput.value;

        // Password criteria regex
        const passwordCriteria = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        // Check if password meets criteria
        if (passwordCriteria.test(passwordValue)) {
            passwordInput.classList.remove('border-red-500', 'ring-red-500', 'ring-2');
            passwordInput.classList.add('border-green-500', 'ring-green-500', 'ring-2');
        } else {
            passwordInput.classList.remove('border-green-500', 'ring-green-500', 'ring-2');
            passwordInput.classList.add('border-red-500', 'ring-red-500', 'ring-2');
        }
    }
</script>