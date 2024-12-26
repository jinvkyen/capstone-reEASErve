<div class="sticky top-0 right-0 p-4 font-mont">
    <div class="sub-container w-full h-20 flex justify-center items-center shadow-sm rounded-lg" style="background-color: var(--custom-color);">
        <!-- Profile Picture -->
        <div class="profile-image flex justify-center items-center p-1 ml-3">
            <img src="{{ asset($userData['profile_pic'] ?? 'public/storage/assets/default_image.png') }}" class="h-16 w-16 rounded-full border-solid border-1 border-blue-700 mr-3">
        </div>
        <!-- Name and Student Number -->
        <div class="flex-col item-start justify-center hidden lg:flex">
            <h5 class="truncate text-lg font-medium mb-0">{{ $userData['first_name'] }} {{ $userData['last_name'] }}</h5>
            <p class="text-xs mt-0">{{ $userData['user_id'] }}</p>
        </div>
        <!-- COS Emblem -->
        <div class="flex justify-center items-center ml-3 hidden lg:flex">
            <img src="{{ $cms['url_emblem'] }}" alt="Emblem" class="h-12 w-12 rounded-full mr-3">
        </div>
    </div>
</div>