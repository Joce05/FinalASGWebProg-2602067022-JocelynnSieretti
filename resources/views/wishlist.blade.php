@extends('layout.master')

@section('content')
<div class="container mt-4">
    <h1>My Wishlist</h1>
    <div class="row">
        @foreach ($wishlistedUsers as $wishlist)
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="{{ $wishlist->targetUser->image ? Storage::url($wishlist->targetUser->image) : asset('assets/profilepict.png') }}" >
                    <div class="card-body">
                        <h5 class="card-title">Name: {{ $wishlist->targetUser->name }}</h5>
                        <p class="card-text">
                            Instagram: {{ '@' . $wishlist->targetUser->instagram }}
                        </p>
                        <p class="card-text">Hobbies:</p>
                        <div class="d-flex flex-wrap">
                            @foreach (json_decode($wishlist->targetUser->hobby, true) as $hobby)
                                <span class="badge bg-primary me-2 mb-2">{{ $hobby }}</span>
                            @endforeach
                        </div>
                        <!-- Cancel Wishlist Button -->
                        <button class="btn btn-danger cancel-wishlist-button" data-user-id="{{ $wishlist->targetUser->id }}">
                            Cancel Wishlist
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle Cancel Wishlist button click
        document.querySelectorAll('.cancel-wishlist-button').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');

                fetch('/wishlist/remove', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ target_user_id: userId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Optionally remove the card from the UI
                        this.closest('.card').remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the user from the wishlist.');
                });
            });
        });
    });
</script>

@endsection
