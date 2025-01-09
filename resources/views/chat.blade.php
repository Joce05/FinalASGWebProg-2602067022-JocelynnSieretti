@extends('layout.master')

@section('content')
<div class="col-md-9 ms-2">
    <h3 style="text-pink">My Friends</h3>
    <div class="row">
        @forelse ($friendUsers as $friend)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ $friend->image ? Storage::url($friend->image) : asset('assets/profilepict.png') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $friend->name }}</h5>
                        <p class="card-text">
                            <strong>Instagram:</strong> {{ '@' . ltrim(parse_url($friend->instagram)['path'], '/') }}
                        </p>
                        <p class="card-text">
                            <strong>Hobbies:</strong>
                            @foreach(json_decode($friend->hobby, true) ?? [] as $hobby)
                                <span class="badge me-1" style="background-color: #FFC0CB; color: #333;" >{{ $hobby }}</span>
                            @endforeach
                            <div class="mt-3">
                                <a href="{{ route('chat.start', $friend->id) }}" class="btn btn-primary">Chat</a>
                            </div>
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
@endsection
