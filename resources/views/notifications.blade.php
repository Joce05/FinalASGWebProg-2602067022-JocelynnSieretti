@extends('layout.master')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Notifications</h2>

            <div class="notifications-container">
                @forelse ($notifications as $notification)
                    <div class="card mb-3 {{ $notification->read ? 'bg-light' : 'border-primary' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <div>
                                    @if($notification->type === 'message' && isset($notification->data['sender_id']))
                                        <a href="{{ route('chat.start', ['friendid' => $notification->data['sender_id']]) }}"
                                           class="btn btn-primary btn-sm">View Message</a>
                                    @elseif($notification->type === 'wishlist')
                                        <a href="{{ route('wishlist.view') }}"
                                           class="btn btn-primary btn-sm">View Wishlist</a>
                                    @elseif($notification->type === 'friendship')
                                        <a href="{{ route('chat.index') }}"
                                           class="btn btn-primary btn-sm">View Friends</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        No notifications yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
