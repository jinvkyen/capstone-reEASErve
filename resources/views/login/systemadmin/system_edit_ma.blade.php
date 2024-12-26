<div class="modal fade" id="editModal{{ $department->department_id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $department->department_id }}" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title leading-none" id="editModalLabel{{ $department->department_id }}">
                    <span class="font-bold leading-none m-0 block">
                        Edit Chairperson Admin Account<br>
                        <span class="text-sm font-normal text-slate-400 m-0 w-full text-wrap">
                            {{ $department->department_name }}
                        </span>
                    </span>
                </h5>
                <button type="button" class="close focus:outline-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="editForm-{{ $department->department_id }}" action="{{ route('system.master.edit') }}" method="POST">
                @csrf
                <input name="orig_user_id" type="hidden" value="{{ $department->master_admin->user_id }}">
                <div class="modal-body">
                    <!-- Section 1: Employee ID -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                <div class="p-1 w-full sm:w-full md:w-full relative">
                                    <span>Employee Number<span class="text-red-500">*</span></span>
                                    <input id="login-field" type="number" name="user_id" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Employee Number" value="{{ old('user_id', $department->master_admin->user_id) }}">
                                    <p id="user_id-error" class="text-red-500 ml-2 text-xs mb-0 mt-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 2: First and Last Name -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                    <label class="text-black font-normal flex items-center justify-between">
                                        <span>First Name<span class="text-red-500">*</span></span>
                                    </label>
                                    <input id="login-field" type="text" name="first_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="First Name" value="{{ old('first_name', $department->master_admin->first_name) }}">
                                    <p id="first_name-error" class="text-red-500 ml-2 text-xs mb-0 mt-0"></p>
                                </div>
                                <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                    <label class="text-black font-normal flex items-center justify-between">
                                        <span>Last Name<span class="text-red-500">*</span></span>
                                    </label>
                                    <input id="login-field" type="text" name="last_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Last Name" value="{{ old('last_name', $department->master_admin->last_name) }}">
                                    <p id="last_name-error" class="text-red-500 ml-2 text-xs mb-0 mt-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 3: Email Address -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                    <label class="text-black font-normal flex items-center justify-between">
                                        <span>Email Address<span class="text-red-500">*</span></span>
                                    </label>
                                    <input id="login-field" type="email" name="email" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="@example.com" value="{{ old('email', $department->master_admin->email) }}">
                                    <p id="email-error" class="text-red-500 ml-2 text-xs mb-0 mt-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 5: Position and Status -->
                    <div class="flex flex-row p-2 mx-2 sm:text-sm">
                        <div class="flex flex-row lg:flex-row w-full">
                            <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                <div class="p-1 w-full sm:w-full md:w-full flex flex-col">
                                    <span>Status<span class="text-red-500 mr-2">*</span></span>
                                    <button type="button" id="statusEditBtn-{{ $department->department_id }}" class="{{ $department->master_admin->status == 1 ? 'bg-green-500' : 'bg-red-500' }} text-white font-bold py-2 px-4 rounded font-mont mt-2 h-9 focus:outline-none">
                                        {{ $department->master_admin->status == 1 ? 'Activated' : 'Deactivated' }}
                                    </button>
                                    <input type="hidden" name="status" id="statusEditInput-{{ $department->department_id }}" value="{{ $department->master_admin->status }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" id="resetOnAddButton">Reset</button>
                    <button type="submit" id="saveImage" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>