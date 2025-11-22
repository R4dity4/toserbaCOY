<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Stok;
use App\Models\Harga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        // Eager load stok and the current active harga (hasOne relation `currentHarga`)
        $produk = Produk::with(['stok', 'currentHarga'])->get();
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
            'deskripsi' => 'nullable',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]);

        $data = $request->only([
            'kode_barang',
            'nama_barang',
            'kategori',
            'satuan',
            'deskripsi',
            'status'
        ]);
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $disk = env('FILESYSTEM_DISK', 'public');
            // store file under folder 'produk' on configured disk
            $path = Storage::disk($disk)->putFileAs('produk', $image, $imageName);
            // store relative path in DB (e.g. 'produk/xxxxx.jpg')
            $data['gambar'] = $path;
        }

        $produk = Produk::create($data);

        // Create or update initial harga record for this product
        \App\Models\Harga::updateOrCreate(
            ['barang_id' => $produk->barang_id],
            [
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'status' => 'aktif'
            ]
        );

        // Ensure a stok row exists for the product
        $initialStock = intval($request->input('jumlah_stok', 0));
        \App\Models\Stok::updateOrCreate(
            ['barang_id' => $produk->barang_id],
            [
                'jumlah_stok' => $initialStock,
                'status_stok' => $initialStock > 0 ? 'tersedia' : 'habis'
            ]
        );

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
            'deskripsi' => 'nullable',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]);

        $data = $request->only([
            'kode_barang',
            'nama_barang',
            'kategori',
            'satuan',
            'deskripsi',
            'status'
        ]);
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            $disk = env('FILESYSTEM_DISK', 'public');
            if ($produk->gambar && Storage::disk($disk)->exists($produk->gambar)) {
                Storage::disk($disk)->delete($produk->gambar);
            }

            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = Storage::disk($disk)->putFileAs('produk', $image, $imageName);
            $data['gambar'] = $path;
        }

        $produk->update($data);

        // Update or create harga for this product
        \App\Models\Harga::updateOrCreate(
            ['barang_id' => $produk->barang_id],
            [
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'status' => 'aktif'
            ]
        );

        // Update or create stok row if jumlah_stok provided
        if ($request->has('jumlah_stok')) {
            $updatedStock = intval($request->input('jumlah_stok', 0));
            \App\Models\Stok::updateOrCreate(
                ['barang_id' => $produk->barang_id],
                [
                    'jumlah_stok' => $updatedStock,
                    'status_stok' => $updatedStock > 0 ? 'tersedia' : 'habis'
                ]
            );
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Produk $produk)
    {
        // Delete image if exists
        $disk = env('FILESYSTEM_DISK', 'public');
        if ($produk->gambar && Storage::disk($disk)->exists($produk->gambar)) {
            Storage::disk($disk)->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
