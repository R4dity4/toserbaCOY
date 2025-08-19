@extends('layouts.user')
@section('title', 'ToserbaCOY Shop')
@section('content')
@php
    $categories = $produk->pluck('kategori')->filter()->unique()->sort();
@endphp
<style>
    :root { --accent:#ff6700; --accent-dark:#e65c00; --bg-soft:#fafafa; }
    .hero-banner { background: linear-gradient(90deg,#1a1a1a 0%,#2d2d2d 50%,#4d4d4d 100%); border-radius:24px; padding:48px 40px; color:#fff; position:relative; overflow:hidden; }
    .hero-banner:before { content:""; position:absolute; inset:0; background:radial-gradient(circle at 70% 40%, rgba(255,103,0,.35), transparent 60%); }
    .hero-title { font-weight:700; font-size:2.2rem; letter-spacing:.5px; }
    .hero-sub { font-size:1.05rem; opacity:.85; }
    .hero-cta { background:var(--accent); color:#fff; border:none; padding:10px 22px; border-radius:30px; font-weight:600; transition:.25s; }
    .hero-cta:hover { background:var(--accent-dark); }
    .category-bar { display:flex; gap:12px; overflow-x:auto; padding:6px 2px 2px; scrollbar-width:none; }
    .category-bar::-webkit-scrollbar { display:none; }
    .cat-chip { flex:0 0 auto; padding:8px 18px; background:#fff; border:1px solid #eee; border-radius:24px; font-size:.85rem; font-weight:600; cursor:pointer; transition:.25s; position:relative; }
    .cat-chip.active, .cat-chip:hover { background:var(--accent); color:#fff; border-color:var(--accent); box-shadow:0 4px 14px -4px rgba(255,103,0,.5); }
    .shop-toolbar { display:flex; flex-wrap:wrap; gap:16px; align-items:center; margin-top:28px; }
    .shop-search { flex:1 1 260px; position:relative; }
    .shop-search input { width:100%; padding:10px 40px 10px 16px; border:1px solid #ddd; border-radius:14px; background:#fff; transition:.25s; }
    .shop-search input:focus { outline:none; box-shadow:0 0 0 3px rgba(255,103,0,.25); border-color:var(--accent); }
    .shop-search i { position:absolute; right:14px; top:50%; transform:translateY(-50%); color:#888; }
    .product-grid { margin-top:30px; }
    .product-card { border:none; border-radius:22px; overflow:hidden; background:#fff; position:relative; box-shadow:0 4px 18px -6px rgba(0,0,0,.12); transition:.4s; height:100%; display:flex; flex-direction:column; }
    .product-card:hover { transform:translateY(-6px); box-shadow:0 10px 28px -8px rgba(0,0,0,.25); }
    .product-img-wrap { aspect-ratio:1/1; background:#f5f5f5; position:relative; }
    .product-img-wrap img { width:100%; height:100%; object-fit:cover; }
    .badge-price { background:var(--accent); font-size:.75rem; letter-spacing:.5px; }
    .p-meta { font-size:.7rem; text-transform:uppercase; letter-spacing:.08em; color:#ff6700; font-weight:600; }
    .p-title { font-size:.9rem; font-weight:600; margin-bottom:6px; line-height:1.3; min-height:38px; }
    .price { font-weight:700; color:#222; font-size:1rem; }
    .add-btn { margin-top:auto; background:#fff; color:var(--accent); border:1px solid var(--accent); font-weight:600; width:100%; border-radius:14px; padding:9px 0; transition:.25s; position:relative; overflow:hidden; }
    .add-btn:hover { background:var(--accent); color:#fff; }
    .add-btn:active { transform:scale(.97); }
    .wishlist-btn { position:absolute; top:10px; right:10px; background:rgba(255,255,255,.85); border:none; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:.3s; }
    .wishlist-btn:hover { background:#fff; box-shadow:0 4px 14px -6px rgba(0,0,0,.4); }
    .empty-state { padding:80px 0; text-align:center; color:#777; }
    .toast-cart { position:fixed; bottom:28px; right:28px; background:#222; color:#fff; padding:14px 20px; border-radius:14px; font-size:.9rem; display:none; z-index:999; box-shadow:0 12px 30px -10px rgba(0,0,0,.5); }
    .toast-cart.show { display:block; animation:fadeSlide .6s ease; }
    @keyframes fadeSlide { 0% { opacity:0; transform:translateY(14px); } 100% { opacity:1; transform:translateY(0); } }
    body { background:var(--bg-soft); }
    .section-divider { height:1px; width:100%; background:linear-gradient(90deg,transparent, #ddd, transparent); margin:40px 0 10px; }
    .promo-tag { background:#222; color:#fff; padding:4px 10px; border-radius:18px; font-size:.6rem; letter-spacing:.09em; font-weight:600; position:absolute; top:10px; left:10px; }
    @media (max-width: 575px) {
        .hero-title { font-size:1.6rem; }
        .p-title { min-height:unset; }
        .shop-toolbar { gap:10px; }
    }
</style>

<div class="hero-banner mb-4">
    <div class="row align-items-center">
        <div class="col-md-7 mb-4 mb-md-0 position-relative">
            <div class="promo-tag ">SPESIAL</div><br><br>
            <h1 class="hero-title">Belanja Produk Kebutuhan</h1>
            <p class="hero-sub mb-4">Temukan produk pilihan dengan pengalaman sederhana, cepat, dan nyaman.</p>
            <button class="hero-cta" onclick="document.getElementById('catalog').scrollIntoView({behavior:'smooth'})">JELAJAHI KATALOG</button>
        </div>
        <div class="col-md-5 text-center">
            <img src="https://dummyimage.com/480x360/ff6700/ffffff&text=ToserbaCOY" alt="Hero" class="img-fluid" style="border-radius:18px; box-shadow:0 14px 40px -10px rgba(0,0,0,.55);">
        </div>
    </div>
</div>

<div class="category-bar mb-3">
    <div class="cat-chip active" data-category="all">Semua</div>
    @foreach($categories as $cat)
        <div class="cat-chip" data-category="{{ $cat }}">{{ ucfirst(str_replace('_',' ', $cat)) }}</div>
    @endforeach
</div>

<div class="shop-toolbar">
    <div class="shop-search">
        <input type="text" id="searchInput" placeholder="Cari produk..." autocomplete="off">
        <i class="fas fa-search"></i>
    </div>
    <div style="font-size:.75rem; font-weight:600; color:#666; text-transform:uppercase; letter-spacing:.08em;">{{ $produk->count() }} Produk</div>
</div>

<div class="section-divider" id="catalog"></div>

<div class="row product-grid" id="productGrid">
    @forelse($produk as $item)
        @php
            $activePrice = $item->harga
                ->where('status','aktif')
                ->sortByDesc('created_at')
                ->first()->harga_jual ?? 0;
        @endphp
        <div class="col-6 col-sm-4 col-md-3 col-lg-3 mb-4 product-item" data-name="{{ strtolower($item->nama_barang) }}" data-category="{{ $item->kategori }}">
            <div class="card product-card">
                <div class="product-img-wrap">
                    <button class="wishlist-btn" title="Favorit"><i class="far fa-heart"></i></button>
                    <img class="img-fluid" style="max-height:250px; max-height:250px;" src="{{ asset($item->gambar ?? 'default.png') }}" alt="{{ $item->nama_barang }}">
                </div>
                <div class="p-3 d-flex flex-column">
                    <span class="p-meta">{{ ucfirst(str_replace('_',' ', $item->kategori)) }}</span>
                    <div class="p-title">{{ $item->nama_barang }}</div>
                    <div class="price mb-2">Rp {{ number_format($activePrice,0,',','.') }}</div>
                    <button class="add-btn add-to-cart" data-id="{{ $item->barang_id }}">
                        <i class="fas fa-cart-plus me-1"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-cube fa-3x mb-3" style="color:#bbb;"></i>
                <p>Belum ada produk tersedia.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="toast-cart" id="toastCart"><i class="fas fa-check-circle me-2 text-success"></i>Produk masuk ke cart.</div>
@endsection

@section('scripts')
<script>
    const chips = document.querySelectorAll('.cat-chip');
    const items = document.querySelectorAll('.product-item');
    const searchInput = document.getElementById('searchInput');
    const toast = document.getElementById('toastCart');
    let toastTimer = null;

    function applyFilter() {
        const activeChip = document.querySelector('.cat-chip.active');
        const cat = activeChip ? activeChip.getAttribute('data-category') : 'all';
        const q = searchInput.value.trim().toLowerCase();
        let visible = 0;
        items.forEach(it => {
            const matchCat = cat === 'all' || it.dataset.category === cat;
            const matchText = !q || it.dataset.name.includes(q);
            if(matchCat && matchText) { it.style.display=''; visible++; }
            else it.style.display='none';
        });
    }

    chips.forEach(c => c.addEventListener('click', () => {
        chips.forEach(x => x.classList.remove('active'));
        c.classList.add('active');
        applyFilter();
    }));
    searchInput.addEventListener('input', applyFilter);

    function showToast(msg) {
        toast.textContent = msg;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(()=> toast.classList.remove('show'), 2500);
    }

    const csrfToken = '{{ csrf_token() }}';
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ barang_id: id, quantity: 1 })
            })
            .then(r => r.json())
            .then(data => {
                if(data.success){
                    showToast('Produk masuk ke cart.');
                    btn.classList.add('btn-added');
                    btn.innerHTML = '<i class="fas fa-check me-1"></i> Ditambahkan';
                    setTimeout(()=>{ btn.innerHTML='<i class="fas fa-cart-plus me-1"></i> Tambah'; btn.classList.remove('btn-added'); }, 2200);
                } else {
                    showToast('Gagal menambah produk');
                }
            })
            .catch(() => showToast('Terjadi kesalahan'));
        });
    });
</script>
@endsection
