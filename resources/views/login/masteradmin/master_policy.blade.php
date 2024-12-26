<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy Management {{$cms['dept_label']}}</title>
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Tailwind CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Bootstrap JS for Modal -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.masteradmin.master_sidebar')


        <!-- Main Content -->
        <div class="flex flex-col lg:ml-80 w-full my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl sm:text-2xl font-bold">Policy Management</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>
            </div>

            <div class="flex flex-col p-2 rounded-lg shadow-lg mx-4 mt-8" style="background-color: var(--custom-color);">

                <!-- Title and Button Section -->
                <div class="flex flex-col sm:flex-row justify-between items-center w-full p-4 font-mont">
                    <h1 class="font-semibold text-lg sm:text-xl lg:text-2xl mb-4 sm:mb-0">
                        {{$cms['dept']}} Resource Policies
                    </h1>
                    <!-- Add Policy Action Trigger -->
                    <button
                        class="font-mont text-white bg-green-600 hover:bg-green-700 rounded-lg p-2 text-center whitespace-nowrap w-full sm:w-auto text-xs sm:text-sm lg:text-base transition duration-300 ease-in-out"
                        type="button"
                        data-toggle="modal"
                        data-target="#addPolicyModal">
                        Add New Policy
                    </button>
                </div>

                <!-- Policies Section -->
                <div class="pt-0 pb-0 font-mont">
                    @if($policy->isEmpty())
                    <!-- No policies message -->
                    <div class="text-center py-4">
                        <p class="font-semibold text-lg text-gray-500">No policies are added</p>
                    </div>
                    @else
                    <!-- Loop through policies -->
                    @foreach ($policy as $policies)
                    <div class="accordion-section bg-white rounded-lg shadow-sm p-2 mx-4 mb-4" data-policy-id="{{ $policies->policy_id }}">
                        <!-- Accordion Header -->
                        <div class="flex justify-between items-center p-2 cursor-pointer">
                            <!-- Title -->
                            <p class="font-semibold text-lg m-0 sm:text-sm">
                                {{ $policies->policy_name }}
                            </p>
                            <!-- Action Icons -->
                            <div class="flex space-x-5">
                                <!-- Edit Modal Trigger -->
                                <i class="fa-solid fa-pen fa-lg text-blue-500 cursor-pointer hover:text-blue-700 editPolicyIcon"
                                    data-policy-id="{{ $policies->policy_id }}"
                                    data-policy-title="{{ $policies->policy_name }}"
                                    data-policy-content="{{ $policies->policy_content }}"
                                    data-policy-inclusions="{{ $policies->inclusions }}"
                                    data-toggle="modal" data-target="#editModal">
                                </i>

                                <!-- Delete Modal Trigger -->
                                <i class="deleteModalTrigger fa-solid fa-trash fa-lg text-red-500 cursor-pointer hover:text-red-700" data-policy-id="{{ $policies->policy_id }}"></i>
                            </div>
                        </div>

                        <!-- Accordion Content -->
                        <div class="accordion-content p-2 border-t border-gray-200 hidden">
                            <!-- Content -->
                            <div class="flex items-start text-justify">
                                <p class="lg:text-sm sm:text-xs pl-2"><span class="font-bold">CONTENT</span><br>
                                    <span class="sm:text-xs">{{ $policies->policy_content }}</span>
                                </p>
                            </div>
                            <!-- Inclusions -->
                            <div class="flex items-start text-justify">
                                <p class="lg:text-sm sm:text-xs pl-2"><span class="font-bold">INCLUSIONS</span><br>
                                    <span class="sm:text-xs">{{ $policies->inclusions }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>


                <!-- Download -->
                <form action="{{route('ma.download.policy')}}" method="POST">
                    @csrf
                    <button class="btn btn-primary ml-4 mb-2">Download Policies</button>
                </form>
            </div>
        </div>

        <!-- Add Policy Modal -->
        <div class="modal fade" id="addPolicyModal" tabindex="-1" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: var(--custom-color);">
                        <h5 class="modal-title text-lg font-semibold" id="addPolicyModalLabel">Add a New Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-black text-base">
                        <!-- Add Policy Form -->
                        <form method="POST" action="{{route('ma.add.policy')}}">
                            @csrf
                            <div class="form-group">
                                <label class="font-bold" for="newPolicyTitle">Policy Title</label>
                                <input name="policy_name" type="text" class=" w-full p-2 border border-gray-300 rounded-lg font-mont" id="newPolicyTitle" placeholder="Enter policy title">
                            </div>
                            <div class="form-group">
                                <label class="font-bold" for="newPolicyContent">Policy Content</label>
                                <textarea name="policy_content" class=" w-full p-2 border border-gray-300 rounded-lg font-mont resize-none" id="newPolicyContent" rows="3" placeholder="Enter policy content"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="font-bold" for="newPolicyInclusions">Inclusions</label>
                                <textarea name="inclusions" class=" w-full p-2 border border-gray-300 rounded-lg font-mont resize-none" id="newPolicyInclusions" rows="3" placeholder="Enter policy inclusions and write N/A if none..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Policy</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Edit Policy Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: var(--custom-color);">
                        <h5 class="modal-title text-lg font-semibold" id="editModalLabel">Edit Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Edit Form -->
                        <form method="POST" action="{{route ('ma.edit.policy')}}">
                            @csrf
                            <input type="hidden" id="policy_id" name="policy_id" value="{{ isset($policies) ? $policies->policy_id : '' }}">

                            <div class="form-group">
                                <label class="font-bold" for="policyTitle">Policy Title</label>
                                <input type="text" class="w-full p-2 border border-gray-300 rounded-lg font-mont" id="policyTitle" name="policyTitle" placeholder="Enter policy title..." value="{{ isset($policies) ? $policies->policy_name : '' }}">
                            </div>

                            <div class="form-group">
                                <label class="font-bold" for="policyContent">Policy Content</label>
                                <textarea class="w-full p-2 border border-gray-300 rounded-lg font-mont resize-none" id="policyContent" name="policyContent" rows="3" placeholder="Enter policy content...">{{ isset($policies) ? $policies->policy_content : '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="font-bold" for="policyInclusions">Inclusions</label>
                                <textarea class="w-full p-2 border border-gray-300 rounded-lg font-mont resize-none" id="policyInclusions" name="policyInclusions" rows="3" placeholder="Enter policy inclusions and write N/A if none...">{{ isset($policies) ? $policies->inclusions : '' }}</textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">{{ isset($policies) ? 'Save Changes' : 'Add Policy' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Current URL
            const baseUrl = "{{ url('/') }}";
            const editPolicyRoute = "{{ route('ma.edit.policy', ':id') }}";
            const deletePolicyRoute = "{{ route('ma.delete.policy', ':id') }}";
        </script>


        <script>
            // Populate edit modal with current policy values
            document.querySelectorAll('.editPolicyIcon').forEach(editButton => {
                editButton.addEventListener('click', function() {
                    // Get data attributes from the clicked icon
                    const policyTitle = this.getAttribute('data-policy-title');
                    const policyContent = this.getAttribute('data-policy-content');
                    const policyInclusions = this.getAttribute('data-policy-inclusions');
                    const policyId = this.getAttribute('data-policy-id');

                    // Populate the modal fields with the data
                    document.getElementById('policyTitle').value = policyTitle;
                    document.getElementById('policyContent').value = policyContent;
                    document.getElementById('policyInclusions').value = policyInclusions;
                    document.getElementById('policy_id').value = policyId;

                    // Set the action URL for the edit form to include the policy ID
                    const editForm = document.querySelector('#editModal form');
                    editForm.action = editPolicyRoute.replace(':id', policyId);
                });
            });
        </script>

        <!-- Delete -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.deleteModalTrigger').forEach(button => {
                    button.addEventListener('click', function() {
                        const policyId = this.dataset.policyId;

                        Swal.fire({
                            title: "Are you sure?",
                            text: "This action cannot be reverted.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Delete"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Create and submit the form to delete the policy
                                const deleteForm = document.createElement('form');
                                deleteForm.action = "{{ route('ma.delete.policy', ':id') }}".replace(':id', policyId);
                                deleteForm.method = 'POST';
                                deleteForm.style.display = 'none';

                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = '{{ csrf_token() }}';

                                deleteForm.appendChild(csrfInput);
                                document.body.appendChild(deleteForm);
                                deleteForm.submit();
                            }
                        });
                    });
                });
            });
        </script>


        <!-- JavaScript for Accordion -->
        <script>
            $(document).ready(function() {
                $('.accordion-section').on('click', function() {
                    // Close other open accordions
                    $('.accordion-content').not($(this).children('.accordion-content')).slideUp();
                    $('.accordion-section').not($(this)).find('.fa-chevron-down').removeClass('rotate-180');

                    // Toggle the clicked accordion
                    $(this).children('.accordion-content').slideToggle();
                    $(this).find('.fa-chevron-down').toggleClass('rotate-180');
                });
            });
        </script>

        <!-- SWAL Success-->
        @if(Session::has('success'))
        <script>
            swal("Success", "{{Session::get('success')}}", 'success', {
                button: true,
                button: "OK"
            });
        </script>
        @endif

        <!-- SWAL Info-->
        @if(Session::has('info'))
        <script>
            swal("Information", "{{Session::get('info')}}", 'info', {
                button: true,
                button: "OK"
            });
        </script>
        @endif

        <!-- SWAL Error-->
        @if(Session::has('error'))
        <script>
            swal("Error", "{{Session::get('error')}}", 'error', {
                button: true,
                button: "OK"
            });
        </script>
        @endif

</body>

</html>