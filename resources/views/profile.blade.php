@extends('layout.master')

@section('content')
<div class="container-fluid px-0">
    <div class="card mb-3" style="width: 100%; border-radius: 0;">
        <div class="row g-0">
            <div class="col-md-4 position-relative">
                <!-- Image Container -->
                <div id="image-container" class="position-relative" style="cursor: pointer;">
                    <img
                        id="profile-image"
                        src="{{ $user->image ? Storage::url($user->image) : asset('assets/profilepict.png') }}"
                        class="img-fluid rounded-start"
                        style="max-width: 300px; height: auto;"
                        alt="Profile Picture"
                    >
                    <div id="upload-overlay" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                         style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s;">
                        <span class="text-white">Click to change photo</span>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="d-flex align-items-center" style="color: #f799c8;">
                            <i class="fas fa-wallet me-2"></i>
                            <h6 class="mb-0">Wallet Balance: {{ number_format($user->coin) }} coins</h6>
                        </div>
                    </div>

                    <!-- Edit Profile Form -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Single file input for profile picture -->
                        <input type="file"
                               id="profile-image-input"
                               name="image"
                               class="d-none"
                               accept="image/*"
                               onchange="previewImage(this)">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hobby" class="form-label">Hobby</label>
                            <div>
                                @php
                                    $hobbies = ['Cooking', 'Painting', 'Hiking', 'Traveling', 'Gaming'];
                                    $userHobbies = is_array($user->hobby) ? $user->hobby : (json_decode($user->hobby, true) ?? []);
                                @endphp

                                @foreach ($hobbies as $hobby)
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="hobby[]"
                                            id="hobby_{{ $hobby }}"
                                            value="{{ $hobby }}"
                                            {{ in_array($hobby, $userHobbies) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="hobby_{{ $hobby }}">
                                            {{ $hobby }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('hobby')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="instagram" class="form-label">Instagram Username</label>
                            <input type="text" class="form-control" id="instagram" name="instagram"
                                   value="{{ old('instagram', $user->instagram) }}">
                            @error('instagram')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phonenumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="{{ old('phonenumber', $user->phonenumber) }}">
                            @error('phonenumber')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9 ms-2">
        <h3 style="text-pink">My Friends</h3>
        <div class="row">
            @forelse ($friendUsers as $friend)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ $friend->image ? asset('storage/' . $friend->image) : asset('assets/profilepict.png') }}"
                             class="card-img-top"
                             alt="Friend's Profile Picture">
                        <div class="card-body">
                            <h5 class="card-title">{{ $friend->name }}</h5>
                            <p class="card-text">
                                <strong>Instagram:</strong> {{ '@' . ltrim(parse_url($friend->instagram)['path'], '/') }}
                            </p>
                            <p class="card-text">
                                <strong>Hobbies:</strong>
                                @foreach(json_decode($friend->hobby, true) ?? [] as $hobby)
                                    <span class="badge bg-primary me-1">{{ $hobby }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p>You don't have any friends yet. Add users to your wishlist to make friends!</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4 ms-2 mb-2">
        <h5>My Avatars</h5>
        <div class="d-flex flex-wrap gap-2">
            @forelse ($purchasedAvatars as $avatar)
                <div class="position-relative" style="width: 80px; height: 80px;">
                    <img
                        src="{{ asset($avatar->image_path) }}"
                        class="img-fluid rounded cursor-pointer avatar-option"
                        data-avatar-path="{{ $avatar->image_path }}"
                        style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;
                               {{ $user->image === $avatar->image_path ? 'border: 3px solid #f799c8;' : '' }}"
                        alt="Avatar"
                    >
                </div>
            @empty
                <p>No avatars purchased yet.</p>
            @endforelse
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageContainer = document.getElementById('image-container');
    const overlay = document.getElementById('upload-overlay');
    const fileInput = document.getElementById('profile-image-input');

    // Show/hide overlay on hover
    imageContainer.addEventListener('mouseenter', () => {
        overlay.style.opacity = '1';
    });

    imageContainer.addEventListener('mouseleave', () => {
        overlay.style.opacity = '0';
    });

    // Trigger file input when clicking on image
    imageContainer.addEventListener('click', () => {
        fileInput.click();
    });
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('profile-image').src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
