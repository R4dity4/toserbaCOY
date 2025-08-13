<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Prevent horizontal scrollbar */
        body {
            overflow-x: hidden;
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
                            <li><a class="dropdown-item" href="{{ route('stok.out.form') }}"><i class="fas fa-right-from-bracket"></i> Penjualan</a></li>
                            <li class="nav-item dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#" id="riwayatDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-history"></i> Riwayat
                                </a>
                                <ul class="dropdown-menu dropdown-submenu-left">
                                    <li><a class="dropdown-item" href="{{ route('stok.in.index') }}"><i class="fas fa-list"></i> Beli</a></li>
                                    <li><a class="dropdown-item" href="{{ route('stok.out.index') }}"><i class="fas fa-list"></i> Jual</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produk.index') }}">
                            <i class="fas fa-box"></i> Produk
                        </a>
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
