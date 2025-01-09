@extends('layout.master')

@section('content')
<div class="container">
    <div class="row">
        @foreach ($users as $u)
        <div class="col-md-3">
            <div class="card" style="width: 18rem;">
                <img src="{{ $u->image ? Storage::url($u->image) : asset('assets/profilepict.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Name: {{ $u->name }}</h5>
                    <p class="card-text">
                        Hobbies:
                        <div class="d-flex flex-wrap">
                            @foreach (json_decode($u->hobby, true) as $hobby)
                                <span class="badge"
                                      style="background-color: #FFC0CB; color: #333; border-radius: 5px; padding: 5px 10px; margin: 5px; font-size: 0.9rem;">
                                    {{ $hobby }}
                                </span>
                            @endforeach
                        </div>
                    </p>
                    @if ($u->id != auth()->id())
                        <button class="btn btn-primary thumbs-up-button" data-user-id="{{ $u->id }}">
                            👍 Thumbs Up
                        </button>
                    @else
                        <button class="btn btn-secondary" disabled>
                            This is you
                        </button>
                    @endif
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        Instagram username: {{ '@' . ltrim(parse_url($u->instagram)['path'], '/') }}
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.thumbs-up-button');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const button = this;  // Store reference to button

            fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ target_user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Replace the button with a success message
                    button.disabled = true;
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    button.innerHTML = '✓ Added to Wishlist';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred.');
            });
        });
    });
});
</script>
@endpush
@endsection
