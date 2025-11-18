@extends('layouts.user')
@section('title', $produk->nama_barang ?? 'Produk')
@section('content')
<style>
    .product-detail-wrap { display:flex; gap:24px; align-items:flex-start; }
    .pd-left { flex:0 0 46%; border-radius:12px; overflow:hidden; background:#fff; }
    .pd-left img { width:100%; height:100%; object-fit:cover; display:block; border-radius:8px; }
    .pd-center { flex:1 1 34%; padding:8px 16px; }
    .pd-right { flex:0 0 22%; background:#fff; border-radius:12px; padding:18px; box-shadow:0 6px 20px rgba(0,0,0,0.04); height:max-content; }
    .pd-price { font-size:1.4rem; font-weight:800; color:#222; margin-bottom:12px; }
    .pd-meta { color:#666; font-size:.95rem; margin-bottom:8px; }
    .pd-desc { color:#444; line-height:1.6; margin-top:12px; }
    .action-btn { width:100%; padding:10px 12px; border-radius:10px; font-weight:700; }
    .btn-buy { background:#ff6700; color:#fff; border:none; }
    .btn-wishlist { background:#fff; color:#ff2d84; border:2px solid #ff2d84; }
    .label-box { font-size:.85rem; color:#444; margin-bottom:6px; }
    @media (max-width: 992px){ .product-detail-wrap{flex-direction:column} .pd-left,.pd-right,.pd-center{flex:unset;width:100%} }
</style>

<div class="card p-3" style="border-radius:12px;">
    <div class="product-detail-wrap">
        <div class="pd-left">
            <img class="lazy" data-src="{{ asset($produk->gambar ?? 'images/produk/default.png') }}" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20'><rect width='20' height='20' fill='%23f5f5f5'/></svg>" alt="{{ $produk->nama_barang }}">
        </div>
        <div class="pd-center">
            <h3 style="margin-top:6px;margin-bottom:6px">{{ $produk->nama_barang }}</h3>
            <div class="pd-meta">{{ $produk->satuan ? $produk->satuan : 'pcs' }}</div>
            <div class="pd-desc">{!! nl2br(e($produk->deskripsi ?? '-')) !!}</div>
        </div>
        <aside class="pd-right" style="border: solid rgb(219, 219, 219);">
            <div class="label-box">Kategori</div>
            <div style="font-weight:700;margin-bottom:14px">{{ ucfirst(str_replace('_',' ', $produk->kategori)) }}</div>
            <div class="pd-price">Rp {{ number_format(optional($produk->currentHarga)->harga_jual ?? ($produk->harga->first()->harga_jual ?? 0),0,',','.') }}</div>
            <div class="pd-meta">Stok: <strong>{{ $produk->stok->jumlah_stok ?? 0 }}</strong></div>
            <div style="height:12px"></div>
            <button id="btnCart" type="button" class="action-btn btn-buy mb-2" data-id="{{ $produk->barang_id }}">
                <i class="fas fa-cart-plus me-2"></i> Beli
            </button>
            <button id="btnWishlist" type="button" class="action-btn btn-wishlist" data-id="{{ $produk->barang_id }}">
                <i class="{{ (isset($wishlist) && in_array($produk->barang_id, $wishlist)) ? 'fas' : 'far' }} fa-heart me-2"></i>
                {{ (isset($wishlist) && in_array($produk->barang_id, $wishlist)) ? 'Hapus Wishlist' : 'Tambah Wishlist' }}
            </button>
        </aside>
    </div>
</div>

@endsection

@section('scripts')
<script>
// reuse lazy loader
(function(){
    function loadImg(img){
        const src = img.getAttribute('data-src');
        if(!src) return;
        img.src = src;
        img.classList.remove('lazy');
    }
    if('IntersectionObserver' in window){
        const io = new IntersectionObserver((entries)=>{
            entries.forEach(e=>{
                if(e.isIntersecting){
                    loadImg(e.target);
                    io.unobserve(e.target);
                }
            });
        }, {rootMargin: '200px 0px'});
        document.querySelectorAll('img.lazy').forEach(img=> io.observe(img));
    } else {
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('img.lazy').forEach(loadImg);
        });
    }
})();

const csrfToken = '{{ csrf_token() }}';
const isAuthenticated = @json(auth()->check());
const loginUrl = '{{ route('login') }}';
const redirectUrl = '{{ request()->fullUrl() }}';

// Wishlist toggle
const btnWishlist = document.getElementById('btnWishlist');
if(btnWishlist){
    btnWishlist.addEventListener('click', function(){
        if(!isAuthenticated){
            window.location.href = loginUrl + '?redirect=' + encodeURIComponent(redirectUrl);
            return;
        }
        const id = this.getAttribute('data-id');
        fetch("{{ route('wishlist.toggle') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ barang_id: id })
        })
        .then(r => r.json())
        .then(data => {
            if(data.success){
                const icon = this.querySelector('i');
                if(data.action === 'added'){
                    icon.classList.remove('far'); icon.classList.add('fas');
                    this.querySelector('span').textContent = 'Hapus Wishlist';
                    try{ localStorage.setItem('wishlist_update', JSON.stringify({ barang_id: id, action: 'added', ts: Date.now() })); }catch(e){}
                } else {
                    icon.classList.remove('fas'); icon.classList.add('far');
                    this.querySelector('span').textContent = 'Tambah Wishlist';
                    try{ localStorage.setItem('wishlist_update', JSON.stringify({ barang_id: id, action: 'removed', ts: Date.now() })); }catch(e){}
                }
            }
        })
        .catch(err => console.error('wishlist error', err));
    });
}

// Add to cart
const btnCart = document.getElementById('btnCart');
if(btnCart){
    btnCart.addEventListener('click', function(){
        if(!isAuthenticated){
            window.location.href = loginUrl + '?redirect=' + encodeURIComponent(redirectUrl);
            return;
        }
        const id = this.getAttribute('data-id');
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
                this.innerHTML = '<i class="fas fa-check me-1"></i> Ditambahkan';
                // show only cart dot(s) in header / mobile nav
                document.querySelectorAll('.dot-cart').forEach(el => el.classList.add('show'));
                setTimeout(()=> this.innerHTML = '<i class="fas fa-cart-plus me-1"></i> Masuk Keranjang', 2200);
            }
        })
        .catch(err => console.error('cart error', err));
    });
}
// listen for wishlist updates from other tabs/pages
window.addEventListener('storage', function(e){
    if(!e.key) return;
    if(e.key === 'wishlist_update'){
        try{
            const payload = JSON.parse(e.newValue || '{}');
            if(payload && payload.barang_id && payload.barang_id == '{{ $produk->barang_id }}'){
                const icon = document.querySelector('#btnWishlist i');
                const label = document.querySelector('#btnWishlist span');
                if(payload.action === 'added'){
                    if(icon) { icon.classList.remove('far'); icon.classList.add('fas'); }
                    if(label) label.textContent = 'Hapus Wishlist';
                } else {
                    if(icon) { icon.classList.remove('fas'); icon.classList.add('far'); }
                    if(label) label.textContent = 'Tambah Wishlist';
                }
            }
        } catch(err){}
    }
});
</script>
@endsection
