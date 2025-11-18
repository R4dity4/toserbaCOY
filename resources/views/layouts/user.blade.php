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
    /* small red dot on cart/history/wishlist links */
    .dot-cart, .dot-history, .dot-wishlist { position: absolute; top:6px; right:8px; width:10px; height:10px; border-radius:50%; background:#d63031; box-shadow:0 0 0 3px rgba(214,48,49,.12); display:none; }
    .dot-cart.show, .dot-history.show, .dot-wishlist.show { display:block; }
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
            /* make navbar toggler (burger) visible on white background */
            .navbar-toggler { border: none; color: #222; }
            .navbar-toggler:focus { box-shadow: none; }
            .navbar-toggler .navbar-toggler-icon {
                /* dark SVG bars so icon is visible on white */
                background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='%23222' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
            }
        /* hide top navbar links on small screens (we'll show a bottom nav there) */
        @media (max-width: 767.98px) {
            .navbar-user .navbar-nav { display: none !important; }
            /* make sure brand remains visible */
            .navbar-user .navbar-brand { display: inline-flex; }
            body { padding-bottom: 96px; } /* reserve more space for taller bottom nav */
        }
        /* mobile bottom nav styles (taller) */
        .mobile-bottom-nav { height: 86px; }
        .mobile-bottom-nav .mobile-nav-link { flex: 1 1 auto; color: #444; padding-top:6px; }
        .mobile-bottom-nav .mobile-nav-link .fa-lg { display:block; font-size:1.25rem; margin-bottom:4px; }
        .mobile-bottom-nav .mobile-nav-link .small { display:block; margin-top:0; }
    /* Responsive table wrapper for mobile: allow horizontal drag/scroll without showing default scrollbar */
    .table-responsive-custom { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-responsive-custom { -ms-overflow-style: none; scrollbar-width: none; }
    .table-responsive-custom::-webkit-scrollbar { display: none; }
    /* Ensure tables inside wrapper keep their intrinsic width so user can drag to see columns */
    .table-responsive-custom table { width: auto; min-width: 520px; }
    </style>
</head>
<body>
        <nav class="navbar navbar-expand-lg navbar-user" style="position:sticky;top:0;z-index:1000;background:white;">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/user') }}">
                <i class="fas fa-store"></i> ToserbaCOY
            </a>
            <!-- toggler removed per request (mobile uses bottom navigation) -->
            <div class="collapse navbar-collapse" id="navbarUser">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-shop {{ request()->is('user') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-user fa-lg"></i> Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-shop {{ request()->routeIs('user.shop') ? 'active' : '' }}" href="{{ route('user.shop') }}">
                            <i class="fas fa-shopping-bag"></i> Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-cart {{ request()->routeIs('user.cart') ? 'active' : '' }}" href="{{ route('user.cart') }}">
                            <i class="fas fa-shopping-cart"></i> Cart Check
                            <span id="navCartDot" class="dot-cart" aria-hidden="true"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-cart {{ request()->routeIs('user.orders') ? 'active' : '' }}" href="{{ route('user.orders') }}">
                            <i class="fas fa-history"></i> Riwayat
                            <span id="navHistoryDot" class="dot-history" aria-hidden="true"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-cart {{ request()->routeIs('user.wishlist') ? 'active' : '' }}" href="{{ route('user.wishlist') }}">
                            <i class="fas fa-heart"></i> Wishlist
                            <span id="navWishlistDot" class="dot-wishlist" aria-hidden="true"></span>
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
    <!-- Mobile bottom navigation (visible on xs/sm only) -->
    <nav class="mobile-bottom-nav d-block d-md-none fixed-bottom bg-white border-top" aria-label="Navigasi bawah">
        <div class="d-flex justify-content-around px-2 py-2">
            <a href="{{ route('user.dashboard') }}" class="text-center text-decoration-none text-muted mobile-nav-link mt-3">
                <div><i class="fas fa-user fa-lg"></i></div>
                <div class="small mt-3">Profil</div>
            </a>
            <a href="{{ route('user.shop') }}" class="text-center text-decoration-none text-muted mobile-nav-link mt-3">
                <div><i class="fas fa-shopping-bag fa-lg"></i></div>
                <div class="small mt-3">Menu</div>
            </a>
                <a href="{{ route('user.cart') }}" class="text-center text-decoration-none text-muted position-relative mobile-nav-link mt-3">
                <div><i class="fas fa-shopping-cart fa-lg"></i></div>
                <div class="small mt-3">Cart</div>
                <span id="navCartDotMobile" class="dot-cart" aria-hidden="true" style="top:6px;right:26px;"></span>
            </a>
                <a href="{{ route('user.orders') }}" class="text-center text-decoration-none text-muted position-relative mobile-nav-link mt-3">
                <div><i class="fas fa-history fa-lg"></i></div>
                <div class="small mt-3">Riwayat</div>
                <span id="navHistoryDotMobile" class="dot-history" aria-hidden="true" style="top:6px;right:26px;"></span>
            </a>
            <a href="{{ route('user.wishlist') }}" class="text-center text-decoration-none text-muted position-relative mobile-nav-link mt-3">
                <div><i class="fas fa-heart fa-lg"></i></div>
                <div class="small mt-3">Wishlist</div>
                <span id="navWishlistDotMobile" class="dot-wishlist" aria-hidden="true" style="top:6px;right:26px;"></span>
            </a>
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
        // Automatically wrap tables with .table-responsive-custom on small screens
        (function(){
            function wrapTables(){
                try{
                    if(window.innerWidth <= 767.98){
                        document.querySelectorAll('.container table').forEach(function(tbl){
                            if(tbl.closest('.table-responsive-custom')) return; // already wrapped
                            var wrapper = document.createElement('div');
                            wrapper.className = 'table-responsive-custom mb-3';
                            tbl.parentNode.insertBefore(wrapper, tbl);
                            wrapper.appendChild(tbl);
                        });
                    } else {
                        // unwrap tables when larger (optional)
                        document.querySelectorAll('.table-responsive-custom').forEach(function(w){
                            var tbl = w.querySelector('table');
                            if(tbl) w.parentNode.insertBefore(tbl, w);
                            w.parentNode.removeChild(w);
                        });
                    }
                }catch(e){console && console.warn && console.warn('wrapTables error', e)}
            }
            // run on load and on orientation/resize
            window.addEventListener('load', wrapTables);
            window.addEventListener('resize', function(){ clearTimeout(window.__wrapTablesTimer); window.__wrapTablesTimer = setTimeout(wrapTables, 180); });
        })();
    </script>
    @yield('scripts')
</body>
</html>
