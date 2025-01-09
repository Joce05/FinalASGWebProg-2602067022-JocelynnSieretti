<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    @include('custom.bootstrap5')
</head>
<body>
    <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded">
        {{-- Logo and navbar in the same row --}}
        <div class="d-flex justify-content-between align-items-center bg-light fixed-top shadow bg-body-tertiary rounded">
            {{-- Logo --}}
            <div class="logo">
                @include('layout.logo')
            </div>

            {{-- Navbar --}}
            <div class="navbar">
                @include('layout.navbar')
            </div>
        </div>
    </div>

            {{-- Content of the website --}}
            @yield('content')

            {{--Footer--}}
            <div class="footer">
                @include('layout.footer')
            </div>

            @include('custom.bootstrapjs')
            @stack('scripts')
</body>
</html>
