@extends('layout.master')

@section('content')
<div class=" container ms-1 me-1">
    <form class="d-flex" role="search" method="GET" action="{{ route('explore.search') }}">
        <input class="form-control me-2" type="search" name="hobby" placeholder="Search hobby" aria-label="Search" value="{{ $search ?? '' }}">

        <select class="form-select me-2" name="gender" aria-label="Filter options">
            <option value="">Filter by gender</option>
            <option value="male" {{ ($gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ ($gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
        </select>

        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>

    <div class="container mt-2">
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
</div>

@endsection
