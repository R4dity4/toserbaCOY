<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\StokIn;
use App\Models\StokOut;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function stokInForm()
    {
        $produk = Produk::where('status', 'aktif')->get();
        return view('stok.stok-in', compact('produk'));
    }

    public function stokInStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:produk,barang_id',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_satuan' => 'nullable|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'supplier' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['total_harga'] = $data['jumlah_masuk'] * ($data['harga_satuan'] ?? 0);

        StokIn::create($data);

        return redirect()->route('stok.in.index')->with('success', 'Kamu berhasil nambah jumlah stoknya!');
    }

    public function stokInIndex()
    {
        $stokIn = StokIn::with('produk')->latest()->paginate(20);
        return view('stok.stok-in-index', compact('stokIn'));
    }

    public function stokOutForm()
    {
        $produk = Produk::whereHas('stok', function($query) {
            $query->where('jumlah_stok', '>', 0);
        })->with('stok')->get();

        return view('stok.stok-out', compact('produk'));
    }

    public function stokOutStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:produk,barang_id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'jenis_keluar' => 'required|in:penjualan,retur,rusak,hilang,transfer'
        ]);

        $data = $request->only(['barang_id','jumlah_keluar','tanggal_keluar','jenis_keluar']);

        try {
            StokOut::create($data);
            return redirect()->back()->with('success', 'Stok berhasil dikeluarkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Maaf, Stok tidak mencukupi untuk pengeluaran barang...');
        }
    }

    public function stokOutIndex()
    {
        $stokOut = StokOut::with('produk')->latest()->paginate(20);
        return view('stok.stok-out-index', compact('stokOut'));
    }

    public function riwayatStok()
    {
        $stokIn = StokIn::with('produk')->latest()->get();
        $stokOut = StokOut::with('produk')->latest()->get();

        return view('stok.riwayat', compact('stokIn', 'stokOut'));
    }
}
