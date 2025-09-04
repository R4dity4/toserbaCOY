<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ToserbaCOY User')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        html { overflow-x: hidden; }
        .navbar-user { background: #fff; border-bottom: 1px solid #eee; position: sticky !important; z-index: 1000;}
        .navbar-user .navbar-brand { color: #fda10d; font-weight: bold; }
    .navbar-user .nav-link { color: #fda10d; position:relative; }
        /* icon links: make them circular and center icons */
        .navbar-user .nav-link.nav-icon {
            width:44px;
            height:44px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
            transition: all .18s ease;
            margin:0 6px;
        }
        .navbar-user .nav-link.nav-icon i { font-size:1.05rem; }
        .navbar-user .nav-link.nav-icon:hover { background: rgba(253,161,13,0.08); }
        .navbar-user .nav-link.nav-icon.active { background: #fda10d; color:#fff; box-shadow:0 6px 18px -8px rgba(253,161,13,.45); }
    .navbar-user .nav-link.active { font-weight: bold; }
    /* small red dot on cart link */
    .cart-dot { position: absolute; top:6px; right:8px; width:10px; height:10px; border-radius:50%; background:#d63031; box-shadow:0 0 0 3px rgba(214,48,49,.12); display:none; }
    .cart-dot.show { display:block; }
    /* highlight shop menu with border when active */
    .navbar-user .nav-link.nav-shop.active {
        border: 2px solid #fda10d;
        border-radius: 8px;
        padding: 6px 10px;
        box-shadow: 0 6px 18px -12px rgba(253,161,13,.45);
        background: rgba(253,161,13,0.04);
    }
        .navbar-user .nav-link.nav-shop { transition: all .18s ease; }
        .navbar-user .nav-link.nav-shop:hover { background: rgba(253,161,13,0.06); transform: translateY(-2px); }
        /* highlight cart menu with border when active (same as shop) */
        .navbar-user .nav-link.nav-cart.active {
            border: 2px solid #fda10d;
            border-radius: 8px;
            padding: 6px 10px;
            box-shadow: 0 6px 18px -12px rgba(253,161,13,.45);
            background: rgba(253,161,13,0.04);
        }
        .navbar-user .nav-link.nav-cart { transition: all .18s ease; }
        .navbar-user .nav-link.nav-cart:hover { background: rgba(253,161,13,0.06); transform: translateY(-2px); }
    </style>
</head>
<body>
        <nav class="navbar navbar-expand-lg navbar-user" style="position:sticky;top:0;z-index:1000;background:white;">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/user') }}">
                <i class="fas fa-store"></i> ToserbaCOY
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarUser">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-shop {{ request()->is('user') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-home"></i> beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-shop {{ request()->routeIs('user.shop') ? 'active' : '' }}" href="{{ route('user.shop') }}">
                            <i class="fas fa-shopping-bag"></i> Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-cart {{ request()->routeIs('user.cart') ? 'active' : '' }}" href="{{ route('user.cart') }}">
                            <i class="fas fa-shopping-cart"></i> Cart Check
                            <span id="navCartDot" class="cart-dot" aria-hidden="true"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
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
    @yield('scripts')
</body>
</html>
