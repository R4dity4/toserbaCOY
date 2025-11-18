@extends('layouts.user')
@section('title', 'Troli Belanja')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center" style="color: #ff6f00; font-weight: bold;">Troli Belanja</h2>
    @php
        $total = 0;
    @endphp
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center" style="width:140px;">Jumlah</th>
                                <th>Harga</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            @php
                                $aktif = $item->produk->harga->where('status','aktif')->sortByDesc('created_at')->first();
                                $harga = $aktif->harga_jual ?? 0;
                                $subtotal = $harga * $item->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr data-id="{{ $item->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($item->produk->gambar ?? 'default.png') }}" alt="{{ $item->produk->nama_barang }}" style="width:60px; height:60px; object-fit:cover; border-radius:8px;" class="me-3">
                                        <div>
                                            <div class="fw-semibold">{{ $item->produk->nama_barang }}</div>
                                            <small class="text-muted">Kode: {{ $item->produk->kode_barang }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm justify-content-center" style="width:110px;">
                                        <button type="button" class="btn btn-outline-secondary btn-minus">-</button>
                                        <input type="text" class="form-control text-center quantity-input" value="{{ $item->quantity }}" style="max-width:45px;">
                                        <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($harga,0,',','.') }}</td>
                                <td class="text-end subtotal">Rp {{ number_format($subtotal,0,',','.') }}</td>
                                <td class="text-end"><button type="button" class="btn btn-sm btn-link text-danger btn-remove"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Cart masih kosong.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top:80px;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ringkasan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <strong id="cartTotal">Rp {{ number_format($total,0,',','.') }}</strong>
                    </div>
                    <hr>
                    <button type="button" id="checkoutBtn" class="btn btn-warning w-100" {{ $total ? '' : 'disabled' }}>Checkout <i class="fas fa-credit-card ms-1"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const csrf = '{{ csrf_token() }}';
    function format(num){ return new Intl.NumberFormat('id-ID').format(num); }
    function recalcTotal(){
        let sum=0; document.querySelectorAll('.subtotal').forEach(s=>{ const v=parseInt(s.dataset.raw||'0'); sum+=v; });
        document.getElementById('cartTotal').textContent = 'Rp '+format(sum);
    }
    const cartBase = "{{ url('/cart') }}";
    document.querySelectorAll('tr[data-id]').forEach(row => {
        const id = row.dataset.id;
        const minus = row.querySelector('.btn-minus');
        const plus = row.querySelector('.btn-plus');
        const input = row.querySelector('.quantity-input');
        const subtotalCell = row.querySelector('.subtotal');
        const price = parseInt(subtotalCell.textContent.replace(/[^0-9]/g,'')) / parseInt(input.value);
        subtotalCell.dataset.raw = price * parseInt(input.value);

        function updateServer(qty){
            fetch(`${cartBase}/${id}`, {
                method:'PATCH',
                headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json' },
                body:JSON.stringify({ quantity: qty })
            })
            .then(r=>r.json())
            .then(d=>{ console.log('updateServer response for id', id, d); })
            .catch(err=>{ console.error('updateServer error', err); });
        }
        function apply(q){
            if(q<1) q=1;
            input.value=q;
            const sub = price*q;
            subtotalCell.textContent='Rp '+format(sub);
            subtotalCell.dataset.raw=sub;
            recalcTotal();
            updateServer(q);
        }
        minus.addEventListener('click', (e)=>{ e.stopPropagation(); apply(parseInt(input.value)-1); });
        plus.addEventListener('click', (e)=>{ e.stopPropagation(); apply(parseInt(input.value)+1); });
        input.addEventListener('change', (e)=>{ e.stopPropagation(); apply(parseInt(input.value)||1); });
        row.querySelector('.btn-remove').addEventListener('click', (e)=>{
            e.stopPropagation();
            fetch(`${cartBase}/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'} })
                .then(r=>r.json()).then((d)=>{ console.log('remove response', d); row.remove(); recalcTotal(); }).catch(err=>console.error(err));
        });
    });
    recalcTotal();

    const checkoutBtn = document.getElementById('checkoutBtn');
    if(checkoutBtn){
        checkoutBtn.addEventListener('click', ()=>{
            if(checkoutBtn.disabled) return;
            checkoutBtn.disabled = true; checkoutBtn.innerHTML = 'Memproses...';
            fetch("{{ route('cart.checkout') }}", {
                method:'POST',
                headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            })
            .then(r=>r.json())
            .then(data=>{
                if(data.success){
                    if (data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }
                    alert('Checkout berhasil');
                    location.reload();
                } else {
                    alert(data.message || 'Checkout gagal');
                    checkoutBtn.disabled = false; checkoutBtn.innerHTML = 'Checkout <i class="fas fa-credit-card ms-1"></i>';
                }
            })
            .catch(()=>{ alert('Terjadi kesalahan'); checkoutBtn.disabled=false; checkoutBtn.innerHTML='Checkout <i class="fas fa-credit-card ms-1"></i>'; });
        });
    }
</script>
@endsection

