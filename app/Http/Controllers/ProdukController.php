<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Stok;
use App\Models\Harga;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with(['stok', 'harga' => function($query) {
            $query->where('status', 'aktif')->latest();
        }])->get();
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:produk',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'satuan' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/produk'), $imageName);
            $data['gambar'] = 'images/produk/' . $imageName;
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Produk $produk)
    {
        $produk->load(['stok', 'stokIn', 'stokOut', 'harga' => function($query) {
            $query->latest();
        }]);
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode_barang' => 'required|unique:produk,kode_barang,' . $produk->barang_id . ',barang_id',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'satuan' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                unlink(public_path($produk->gambar));
            }
            
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/produk'), $imageName);
            $data['gambar'] = 'images/produk/' . $imageName;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Produk $produk)
    {
        // Delete image if exists
        if ($produk->gambar && file_exists(public_path($produk->gambar))) {
            unlink(public_path($produk->gambar));
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
