@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="gender"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>

                                <div class="col-md-6 d-flex gap-5 align-items-center">
                                    <div class="form-check">
                                        <input id="male" type="radio"
                                            class="form-check-input @error('gender') is-invalid @enderror" name="gender"
                                            value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="male">
                                            {{ __('Male') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input id="female" type="radio"
                                            class="form-check-input @error('gender') is-invalid @enderror" name="gender"
                                            value="female" {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="female">
                                            {{ __('Female') }}
                                        </label>
                                    </div>

                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phonenumber"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text"
                                        class="form-control @error('phonenumber') is-invalid @enderror" name="phonenumber"
                                        value="{{ old('phone') }}" required autocomplete="phonenumber" autofocus>

                                    @error('phonenumber')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="form-text text-muted">
                                        Must be digit
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="instagram"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Instagram') }}</label>

                                <div class="col-md-6">
                                    <input id="linkedin" type="url"
                                        class="form-control @error('instagram') is-invalid @enderror" name="instagram"
                                        value="{{ old('instagram') }}" required autocomplete="instagram" autofocus>

                                    @error('instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="form-text text-muted">Enter full URL (e.g., http://www.instagram.com/username).</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="field"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Hobbies') }}</label>

                                <div class="col-md-6">
                                    @foreach (['Cooking', 'Painting', 'Hiking', 'Traveling', 'Gaming'] as $hobby)
                                        <div class="form-check">
                                            <input type="checkbox" name="hobby[]" value="{{ $hobby }}"
                                                class="form-check-input"
                                                {{ in_array($hobby, old('hobby', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="field">{{ $hobby }}</label>
                                        </div>
                                    @endforeach

                                    @error('hobbies')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="form-text text-muted">
                                        Select minimum 3
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="address"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                                <div class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('address') is-invalid @enderror" name="address"
                                        value="{{ old('skill') }}" required autocomplete="skill" autofocus>

                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="form-text text-muted">
                                        Email must be end with "@gmail.com"
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Proceed to Payment') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
