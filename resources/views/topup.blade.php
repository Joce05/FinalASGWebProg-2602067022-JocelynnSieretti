@extends('layout.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #f799c8; color: white;">
                    <h4 class="mb-0">Coin Topup</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                            <i class="fas fa-wallet fs-1" style="color: #f799c8;"></i>
                            <h3 class="mb-0">Current Balance: {{ number_format(Auth::user()->coin) }} coins</h3>
                        </div>

                        <div class="card mb-4 p-3">
                            <h5 class="mb-3">Topup Details</h5>
                            <p class="mb-2">Amount per click: <strong>100 coins</strong></p>
                        </div>

                        <form action="{{ route('topup.add-coins') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-lg" style="background-color: #f799c8; color: white;">
                                <i class="fas fa-plus-circle me-2"></i>Top up coins
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <h2>Avatar Shop</h2>

    <div class="row">
        @foreach($avatars as $avatar)
        <div class="col-md-3">
            <div class="card">
                <img src="{{ asset($avatar->image_path) }}" class="card-img-top" alt="Avatar">
                <div class="card-body">
                    <h5 class="card-title">{{ $avatar->price }} coins</h5>
                    <button class="btn btn-primary buy-avatar" data-avatar-id="{{ $avatar->id }}" data-price="{{ $avatar->price }}">
                        Purchase
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.buy-avatar').forEach(button => {
    button.addEventListener('click', function() {
        const avatarId = this.dataset.avatarId;
        const price = parseInt(this.dataset.price);
        const currentCoins = parseInt('{{ Auth::user()->coin }}');

        // Check coins client-side first
        if (currentCoins < price) {
            alert(`Insufficient coins! You need ${price} coins to purchase this avatar.`);
            return;
        }

        if (confirm('Are you sure you want to purchase this avatar?')) {
            fetch('/avatar/purchase', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ avatar_id: avatarId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Purchase failed');
                    console.error('Purchase error:', data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred while processing your purchase.');
            });
                    }
                });
});
</script>
@endpush
@endsection
