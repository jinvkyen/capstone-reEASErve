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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
            <h2 class="ml-[27px] lg:text-xl sm:text-2xl font-bold text-slate-500">Colleges</h2>


            <!-- Add College Modal Button -->
            <div class="flex flex-col lg:w-full lg:flex-row items-start lg:justify-start lg:mt-0 mr-3 mb-2">
                <button id="newCollegeBtn"
                    class="font-mont text-white h-12 w-auto bg-blue-500 hover:bg-blue-800 rounded-lg text-base lg:text-md text-center text-nowrap p-2 py-2 mx-4"
                    type="button" data-toggle="modal" data-target="#addNewCollege">Add College
                </button>
            </div>
            <!-- End of Add College Modal Button-->

            <div class=" mx-4 p-4 rounded-lg grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" style="background-color: var(--custom-color);">

                @foreach($colleges as $college)
                <!-- College Card -->
                <div class="relative bg-slate-200 p-1 mb-2 rounded-xl px-0 py-0 flex flex-col items-center justify-evenly lg:text-sm sm:text-xs hover:shadow-lg transition-shadow duration-300">
                    <!-- Icon: Indication for Editing (Top-right of the card) -->
                    <button type="button" class="absolute top-2 right-2 focus:outline-none" data-toggle="Editmodal"
                        data-target="#editModal"
                        data-college-id="{{ $college->id }}"
                        data-college-name="{{ $college->name }}"
                        data-college-emblem="{{ asset('storage/' . $college->emblem) }}">
                        <i class="fa-solid fa-pen-to-square text-2xl text-blue-500 hover:text-blue-600 hover:scale-125 transition-all duration-300"></i>
                    </button>
                    <!-- Card Wrapper -->
                    <div class="relative rounded-lg p-3" title="{{ $college->name }}">
                        <!-- Emblem Wrapper -->
                        <a href="{{ route('system.departments', ['college' => $college->id]) }}" class="!no-underline text-gray-800 hover:text-blue-500">
                            <div class="relative w-30 h-30 rounded-full flex items-center justify-center mx-auto">
                                <!-- Clickable Image that opens the modal -->
                                <div class="cursor-pointer border-2 border-gray-300 rounded-full">
                                    <img src="{{ asset('storage/' . $college->emblem) }}" alt="{{ $college->name }} Emblem" class="rounded-full h-16 w-16 object-cover object-top">
                                </div>
                            </div>

                            <!-- Department Name and Statistics -->
                            <div class="flex flex-col items-center justify-start w-full text-center mt-2 space-y-2 text-xs lg:text-sm">
                                <!-- Department Name (2 lines max with ellipsis) -->
                                <p class="font-semibold mb-0 text-sm w-32 text-center line-clamp-2">{{ $college->name }}</p>

                                <!-- Statistics -->
                                <div>
                                    <p class="font-medium mb-0">{{ $departments[$college->name]->count ?? 0 }}</p>
                                    <p class="font-medium text-xs">Departments</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- End of College Card -->
                </div>
                @endforeach

                <!-- Empty Message -->
                @if($colleges->isEmpty())
                <div class="col-span-full text-center text-gray-500 font-mont font-medium">No Records found.</div>
                @endif


                <!-- Modal for Editing College Information -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-bold" id="editModalLabel">Edit College Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!-- Modal Body with Form -->
                            <form id="college_form" action="{{route('college.edit')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body font-mont" style="max-height: 400px; overflow-y: auto;">
                                    <!-- Image Preview Container -->
                                    <div class="img-preview-container mb-3 flex justify-center">
                                        <img id="previewImage" src="" alt="Image Preview" class="hidden max-w-full max-h-72 object-cover" />
                                    </div>

                                    <input type="hidden" name="college_id" id="college-id" value="">
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label class="text-black font-normal flex items-center justify-between">
                                            <span>College Name</span>
                                        </label>
                                        <input id="college-name" type="text" name="college_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="Edit College Name" value="{{ old('college_name') }}">
                                    </div>

                                    <!-- File Input -->
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label for="emblem" class="col-form-label">Upload New Emblem</label>
                                        <input type="file" class="font-mont block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" name="emblem" id="emblem" accept="image/*">
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-secondary" id="resetButton">Reset</button>
                                    <button type="submit" id="saveEditCollege" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of Modal for Editing College Information -->


                <!-- Start of Add College Modal Form -->
                <div class="modal fade" id="addNewCollege" tabindex="-1" role="dialog" aria-labelledby="addNewCollegeLabel" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title leading-none font-bold m-0 block" id="addNewCollegeLabel">
                                    Add a New College
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!-- Modal Body with Form -->
                            <form id="add_college_form" action="{{ route('college.add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body font-mont" style="max-height: 500px;">

                                    <!-- Image Preview -->
                                    <div class="img-preview-container mb-3" style="max-width: 400px; margin: 0 auto;">
                                        <img id="previewImageAdd" src="" alt="Image Preview" style="display:none; max-width: 100%; max-height: 300px; object-fit: cover;">
                                    </div>

                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label class="text-black font-normal flex items-center justify-between">
                                            <span>College Name <span class="text-red-500">*</span></span>
                                        </label>
                                        <input id="college-name" type="text" name="college_name" class="w-full p-2 border border-gray-300 rounded-lg font-mont" placeholder="" value="{{ old('college_name') }}">
                                    </div>

                                    <!-- File Input -->
                                    <div class="p-1 w-8/12 sm:w-full md:w-full relative">
                                        <label for="emblem" class="col-form-label">Upload New Emblem</label>
                                        <input type="file" class="font-mont block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" name="emblem" id="emblemAdd" accept="image/*">
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <!-- Reset Button -->
                                    <button type="reset" class="btn btn-secondary" id="resetOnAddButton">Reset</button>
                                    <button type="submit" id="saveNewCollege" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of Add College Modal Form -->
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

@if ($errors->any())
<script>
    swal("Oops...", "{{ $errors->first() }}", 'error', {
        button: true,
        button: "OK"
    });
</script>
@endif

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 4.6 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Edit College Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('[data-toggle="Editmodal"]');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get college data from the button's data attributes
                const collegeId = this.getAttribute('data-college-id');
                const collegeName = this.getAttribute('data-college-name');
                const emblemPath = this.getAttribute('data-college-emblem');

                // Set the values in the modal
                document.getElementById('college-id').value = collegeId;
                document.getElementById('college-name').value = collegeName;
                document.getElementById('previewImage').src = emblemPath;
                document.getElementById('previewImage').style.display = 'block';

                // Set the form action (adjust if not using parameters)
                document.getElementById('college_form').action = "{{ route('college.edit') }}";

                // Show the modal
                $('#editModal').modal('show');
            });
        });
    });
</script>


<!-- Cropper.js Add College Emblem -->
<script>
    let cropper_add;

    // Handle file input change event
    document.getElementById('emblemAdd').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const image = document.getElementById('previewImageAdd');
                image.src = e.target.result;
                image.style.display = 'block';

                // Destroy the previous cropper instance if it exists
                if (cropper_add) {
                    cropper_add.destroy();
                }
                // Initialize a new cropper instance
                cropper_add = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle save button click event
    document.getElementById('saveImageAdd').addEventListener('click', function() {
        if (cropper_add) {
            // Get the cropped image as a canvas
            const canvas = cropper_add.getCroppedCanvas({
                width: 400,
                height: 400, // Set desired width/height for cropping
            });

            // Convert the canvas to a Blob and send it to the server
            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('emblem', blob, 'cropped_emblem.png'); // Append the cropped image as 'emblem'

                // Send form data via AJAX
                fetch('{{ route("college.add") }}', { // Ensure this URL matches your upload route
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            location.reload(); // Reload the page or update UI after success
                        } else {
                            alert('Failed to upload cropped image.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }, 'image/png'); // Set the image type to PNG
        }
    });

    // Handle reset button click event
    document.getElementById('resetButton').addEventListener('click', function() {
        document.getElementById('emblemAdd').value = ''; // Reset the file input
        document.getElementById('previewImageAdd').style.display = 'none'; // Hide preview image

        // Destroy the Cropper instance if it exists
        if (cropper_add) {
            cropper_add.destroy();
            cropper_add = null;
        }
    });
</script>


<!-- Cropper.js Edit College Emblem -->
<script>
    let cropper;

    // Handle file input change event
    document.getElementById('emblem').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const image = document.getElementById('previewImage');
                image.src = e.target.result;
                image.style.display = 'block';

                // Destroy the previous cropper instance if it exists
                if (cropper) {
                    cropper.destroy();
                }
                // Initialize a new cropper instance
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                });
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle save button click event
    document.getElementById('saveImage').addEventListener('click', function() {
        if (cropper) {
            // Get the cropped image as a canvas
            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
            });
            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('emblem', blob, 'emblem.png');

                // Send form data via AJAX
                fetch('YOUR_UPLOAD_ROUTE', { // Ensure this URL matches your route for handling uploads
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            location.reload(); // Reload to update the image
                        } else {
                            alert('Failed to upload image.');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
            });
        }
    });

    // Handle reset button click event
    document.getElementById('resetButton').addEventListener('click', function() {
        // Reset the file input
        document.getElementById('emblem').value = '';
        college_form.reset();

        // Hide the image preview
        const previewImage = document.getElementById('previewImage');
        previewImage.style.display = 'none';

        // Destroy the Cropper instance if it exists
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    document.getElementById('resetOnAddButton').addEventListener('click', function() {
        add_college_form.reset();
    });
</script>


</html>