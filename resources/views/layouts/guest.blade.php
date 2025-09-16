<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Fashion Store')</title>

    {{-- Bootstrap CSS (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Optional: your app css if you have one in public/css --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    {{-- Top navigation bar --}}
    @include('partials.navbar')

    {{-- Main content --}}
    <main class="container mt-5">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Bootstrap JS bundle (includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Optional: your app js if you have one in public/js --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
