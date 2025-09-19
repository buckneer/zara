{{-- resources/views/layouts/admin.blade.php --}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
        }

        .admin-main {
            margin-left: 260px;
            transition: margin .15s ease;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                height: 100%;
                z-index: 1050;
                background: #f8f9fa;
            }

            .admin-sidebar.show {
                left: 0;
                box-shadow: 0 0 0 100vmax rgba(0, 0, 0, .4);
            }

            .admin-main {
                margin-left: 0;
            }
        }

        .sidebar-section {
            font-size: .85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .nav-link.active {
            font-weight: 600;
            background: rgba(0, 0, 0, 0.03);
            border-radius: .375rem;
        }
    </style>

    
        @vite(['resources/css/admin.css'])
    @stack('head')
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <button class="btn btn-outline-secondary d-lg-none me-2" id="admin-sidebar-toggle"
                aria-label="Toggle sidebar">☰</button>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank">View site</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        {{-- Sidebar --}}
        <aside class="border-end admin-sidebar bg-white p-3" id="admin-sidebar">
            @include('partials.sidebar')
        </aside>

        {{-- Main --}}
        <main class="admin-main flex-fill p-4" id="admin-main">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS (bundle includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function() {
            const toggleBtn = document.getElementById('admin-sidebar-toggle');
            const sidebar = document.getElementById('admin-sidebar');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });

               
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 991.98 && sidebar.classList.contains('show')) {
                        if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>
