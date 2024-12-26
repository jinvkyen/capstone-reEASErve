<!-- Edit Department Name Modal -->
<div class="modal fade" id="editDepartmentModal{{ $department->department_id }}" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel{{ $department->department_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel{{ $department->department_id }}">Edit {{ $department->department_name }} Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="{{ route('department.edit', [$department->department_id, $collegeId]) }}" method="POST">
                    @csrf
                    <div class="modal-body font-mont" style="max-height: 500px;">
                        <!-- Section 1: Deparment Name -->
                        <div class="flex flex-row p-2 mx-2 sm:text-sm">
                            <div class="flex flex-row lg:flex-row w-full">
                                <div class="flex flex-row w-full sm:flex-col md:flex-col">
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label class="text-black font-normal flex items-center justify-between">
                                            <label>Deparment Name<span class="text-red-500">*</span></label>
                                            @error('department_name')
                                            <p class="text-red-500 ml-2 text-xs mb-0 mt-0"></p>
                                            @enderror
                                        </label>
                                        <input id="department-name" type="text" name="department_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Edit name" value="{{ $department->department_name }}" required>
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