<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
        }
        /* small red dot for new/pending orders in admin nav */
        .dot-order {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #dc3545;
            border-radius: 50%;
            margin-left: .5rem;
            box-shadow: 0 0 0 3px rgba(220,53,69,0.12);
            vertical-align: middle;
        }

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

        /* Show submenu only when parent has .open (we toggle this via JS on click)
           Use opacity/transform/visibility so CSS transitions animate smoothly
           instead of toggling display which prevents transitions. */
        .dropdown-submenu > .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform-origin: top center;
            transform: translateY(-8px) scaleY(.98);
            transition: transform .22s ease, opacity .22s ease, visibility .22s;
        }
        .dropdown-submenu.open > .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scaleY(1);
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
        /* Animated dropdown reveal for mobile and touch devices */
        .navbar-nav .dropdown-menu {
            transition: transform .22s ease, opacity .22s ease, visibility .22s;
            transform-origin: top center;
            opacity: 0;
            transform: translateY(-8px) scaleY(.98);
            visibility: hidden;
        }
        /* when parent has .open, show with animation */
        .nav-item.dropdown.open > .dropdown-menu,
        .dropdown-submenu.open > .dropdown-menu {
            opacity: 1;
            transform: translateY(0) scaleY(1);
            visibility: visible;
        }

        /* Mobile collapsible behavior: animate max-height for smoother reveal on small screens */
        @media (max-width: 767.98px) {
            .navbar-nav .dropdown-menu {
                max-height: 0; /* collapsed */
                overflow: hidden;
                transition: max-height .28s ease, opacity .22s ease, transform .22s ease;
            }
            .nav-item.dropdown.open > .dropdown-menu,
            .dropdown-submenu.open > .dropdown-menu {
                /* when .open is present we let JS set an explicit max-height */
                opacity: 1;
                transform: translateY(0) scaleY(1);
                visibility: visible;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="position:sticky;top:0;z-index:1000;">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-boxes"></i> ToserbaCOY
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse menu-stok" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="stokDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-warehouse"></i> Transaksi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('stok.in.form') }}"><i class="fas fa-right-to-bracket"></i> Pembelian</a></li>
                            <li><a class="dropdown-item" href="{{ route('stok.out.form') }}"><i class="fas fa-right-from-bracket"></i> Pengeluaran</a></li>
                            <li class="nav-item dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#" id="riwayatDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-history"></i> Riwayat
                                </a>
                                <ul class="dropdown-menu dropdown-submenu-left">
                                    <li><a class="dropdown-item" href="{{ route('stok.in.index') }}"><i class="fas fa-list"></i> Beli</a></li>
                                    <li><a class="dropdown-item" href="{{ route('stok.out.index') }}"><i class="fas fa-list"></i> Keluarkan</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produk.index') }}">
                            <i class="fas fa-box"></i> Produk
                        </a>
                    </li>
                    @if(Auth::user()->role == 'admin')
                        @php $unreadOrders = \App\Models\Order::where('status', 'pending')->count(); @endphp
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="{{ route('admin.orders.index') }}">
                                <i class="fas fa-receipt"></i>
                                <span class="ms-2">Orders</span>
                                @if($unreadOrders)
                                    <span class="dot-order" title="{{ $unreadOrders }} pending"></span>
                                @endif
                            </a>
                        </li>
                    @endif
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Register</a>
                        </li>
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
            // Toggle submenu open/close on click (instead of hover) for accessibility and touch devices
            document.querySelectorAll('.dropdown-submenu').forEach(function(submenu){
                const toggle = submenu.querySelector('.dropdown-toggle');
                if(!toggle) return;
                // ensure aria attribute exists
                toggle.setAttribute('aria-haspopup','true');
                toggle.setAttribute('aria-expanded', toggle.getAttribute('aria-expanded') || 'false');
                toggle.addEventListener('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    const isOpen = submenu.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    // animate submenu on mobile using max-height
                    try{
                        const menu = submenu.querySelector('.dropdown-menu');
                        if(menu && window.innerWidth <= 767.98){
                            if(isOpen){
                                menu.style.display = 'block';
                                window.requestAnimationFrame(()=>{
                                    const h = menu.scrollHeight;
                                    menu.style.maxHeight = h + 'px';
                                    menu.style.opacity = '1';
                                    menu.style.transform = 'translateY(0) scaleY(1)';
                                });
                            } else {
                                menu.style.maxHeight = '0';
                                menu.style.opacity = '0';
                                menu.style.transform = 'translateY(-8px) scaleY(.98)';
                                setTimeout(()=>{ if(menu) menu.style.display = ''; }, 300);
                            }
                        }
                    }catch(e){/* ignore */}
                });
            });

            // Also animate top-level dropdowns (e.g., Transaksi, User) on click for mobile
            document.querySelectorAll('.nav-item.dropdown').forEach(function(dd){
                const toggle = dd.querySelector('.dropdown-toggle');
                if(!toggle) return;
                toggle.setAttribute('aria-haspopup','true');
                toggle.setAttribute('aria-expanded', toggle.getAttribute('aria-expanded') || 'false');
                toggle.addEventListener('click', function(e){
                    // On small screens we want click to toggle the menu with animation.
                    // Prevent Bootstrap's default immediate toggle so we can animate.
                    e.preventDefault();
                    e.stopPropagation();
                    const isOpen = dd.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                    // handle animated max-height for mobile devices
                    try{
                        const menu = dd.querySelector('.dropdown-menu');
                        if(menu){
                            if(window.innerWidth <= 767.98){
                                if(isOpen){
                                    // make visible and animate to its scrollHeight
                                    menu.style.display = 'block';
                                    // Allow layout to settle
                                    window.requestAnimationFrame(()=>{
                                        const h = menu.scrollHeight;
                                        menu.style.maxHeight = h + 'px';
                                        menu.style.opacity = '1';
                                        menu.style.transform = 'translateY(0) scaleY(1)';
                                    });
                                } else {
                                    // collapse
                                    menu.style.maxHeight = '0';
                                    menu.style.opacity = '0';
                                    menu.style.transform = 'translateY(-8px) scaleY(.98)';
                                    // remove display after transition
                                    setTimeout(()=>{ if(menu) menu.style.display = ''; }, 300);
                                }
                            }
                        }
                    }catch(e){/* ignore */}
                });
            });

            // Close any open submenu or dropdown when clicking outside
            document.addEventListener('click', function(e){
                document.querySelectorAll('.dropdown-submenu.open, .nav-item.dropdown.open').forEach(function(openEl){
                    if(!openEl.contains(e.target)){
                        openEl.classList.remove('open');
                        const toggle = openEl.querySelector('.dropdown-toggle');
                        if(toggle) toggle.setAttribute('aria-expanded','false');
                    }
                });
            });
        });
    </script>
    @if(Auth::check() && Auth::user()->role == 'admin')
    <script>
    // Poll admin unread orders count and update .dot-order
    document.addEventListener('DOMContentLoaded', function () {
        const countUrl = "{{ route('admin.orders.unread_count') }}";
        const indexUrl = "{{ route('admin.orders.index') }}";

        async function updateOrderDot() {
            try {
                const res = await fetch(countUrl, { credentials: 'same-origin' });
                if (!res.ok) return;
                const data = await res.json();
                const count = parseInt(data.count || 0, 10);

                const link = document.querySelector(`a[href='${indexUrl}']`);
                if (!link) return;

                let dot = link.querySelector('.dot-order');
                if (count > 0) {
                    if (!dot) {
                        dot = document.createElement('span');
                        dot.className = 'dot-order';
                        dot.title = `${count} pending`;
                        link.appendChild(dot);
                    } else {
                        dot.title = `${count} pending`;
                    }
                } else {
                    if (dot) dot.remove();
                }
            } catch (e) {
                // ignore network errors
            }
        }

        updateOrderDot();
        setInterval(updateOrderDot, 15000);
    });
    </script>
    @endif
    @yield('scripts')
</body>
</html>
