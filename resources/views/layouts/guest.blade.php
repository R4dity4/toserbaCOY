<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    :root { --brand: #fda10d; --brand-contrast: #ffffff; }
        /* Prevent horizontal scrollbar */
        body {
            overflow-x: hidden;
            background: #fff;
            color: #222;
        }

    /* Brand-friendly card and button styles for auth pages */
    body { background: linear-gradient(180deg, rgba(253,161,13,0.06) 0%, rgba(255,255,255,1) 40%); }
    .auth-hero { background: linear-gradient(90deg, rgba(253,161,13,0.12), rgba(253,161,13,0.04)); padding: 28px 0; border-bottom: 1px solid rgba(0,0,0,0.03); }
    .auth-hero .brand { font-weight:700; color:var(--brand); font-size:1.25rem; }
    .auth-wrapper { min-height: calc(100vh - 120px); display:flex; align-items:center; justify-content:center; }
    .card { border: none; border-radius:14px; box-shadow: 0 18px 50px rgba(15,15,15,0.06); overflow: hidden; transform-origin: center; animation: floatIn .42s ease both; }
    .card-header { background: var(--brand); color: var(--brand-contrast); font-weight: 700; font-size:1.05rem; }
    .card .btn-success, .btn-primary { background: var(--brand) !important; border-color: var(--brand) !important; color: var(--brand-contrast) !important; }
    .card .btn-success:hover, .btn-primary:hover { filter: brightness(0.96); }
    a { color: var(--brand); }
    .btn-outline-warning { border-color:var(--brand); color:var(--brand); }

    @keyframes floatIn { from { opacity:0; transform: translateY(8px) scale(.995); } to { opacity:1; transform: translateY(0) scale(1); } }

    /* Google button styling */
    .google-btn { background: #fff; color: #111; border:1px solid rgba(0,0,0,0.08); box-shadow: 0 6px 18px rgba(11,11,11,0.04); display:inline-flex; align-items:center; gap:10px; justify-content:center; }
    .google-btn .fa-google { color: #DB4437; }
    .google-btn:hover { transform: translateY(-1px); box-shadow: 0 14px 40px rgba(11,11,11,0.06); }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
            /* Prevent overflow */
            max-width: 200px;
            white-space: nowrap;
        }

        /* Check if submenu would overflow and position to left instead */
        .dropdown-submenu.dropdown-submenu-left .dropdown-menu {
            left: -100%;
            right: 100%;
        }

        .dropdown-submenu:hover > .dropdown-menu {
            display: block;
        }

        /* Ensure dropdown stays within viewport */
        @media (min-width: 768px) {
            .navbar-nav .dropdown-menu {
                max-width: calc(100vw - 40px);
            }

            /* Auto-adjust submenu position based on available space */
            .dropdown-submenu:last-child .dropdown-menu,
            .dropdown-submenu:nth-last-child(2) .dropdown-menu {
                left: -100%;
                right: 100%;
            }

            .menu-stok {

                justify-content: center;
            }
        }

        @media (max-width: 767px) {
            .dropdown-submenu .dropdown-menu {
                position: static !important;
                float: none;
                width: auto;
                margin-top: 0;
                background-color: transparent;
                border: 0;
                box-shadow: none;
                margin-left: 20px;
            }

            .menu-stok {
                justify-content: center;
            }
        }
    /* Match user navbar styling */
    .navbar-user { background: #fff; border-bottom: 1px solid #eee; position: sticky !important; z-index: 1000; }
    .navbar-user .navbar-brand { color: var(--brand); font-weight: bold; }
    .navbar-user .nav-link { color: var(--brand); }
    .navbar-user .nav-link.active { font-weight: bold; }
    /* Keep guest nav items inline with brand (desktop + mobile) */
    .navbar-user .container { display:flex; align-items:center; gap:12px; flex-wrap:nowrap; }
    .navbar-user .navbar-collapse { display:flex !important; align-items:center; }
    .navbar-user .navbar-nav { display:flex; gap:8px; align-items:center; margin-left:auto; flex-wrap:nowrap; }
    .navbar-user .navbar-nav .nav-item .nav-link { padding: .4rem .6rem; white-space:nowrap; }
    .navbar-user .navbar-brand { white-space:nowrap; }
    /* allow horizontal scroll on very small screens instead of wrapping */
    .navbar-user .navbar-collapse { overflow-x:auto; -webkit-overflow-scrolling: touch; }
    .navbar-user .navbar-collapse::-webkit-scrollbar{ display:none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-user navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-boxes"></i> ToserbaCOY
            </a>
            <!-- No burger toggler for guest mobile: show nav links directly -->
            <div class="navbar-collapse menu-stok" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                    <div style="display: flex;">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            {{-- <span style="width:2px;height:24px;background-color:#c5c5c5;display:block;margin:0 6px;" aria-hidden="true"></span> --}}
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    </div>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">Dashboard</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest


                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent horizontal scroll from dropdown submenu
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');

            dropdownSubmenus.forEach(function(submenu) {
                submenu.addEventListener('mouseenter', function() {
                    const submenuDropdown = this.querySelector('.dropdown-menu');
                    if (submenuDropdown) {
                        // Reset classes
                        this.classList.remove('dropdown-submenu-left');

                        // Get positions
                        const rect = submenuDropdown.getBoundingClientRect();
                        const viewportWidth = window.innerWidth;

                        // Check if submenu would overflow to the right
                        if (rect.right > viewportWidth) {
                            this.classList.add('dropdown-submenu-left');
                        }
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
