<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BarangController extends Controller
{

    public function index()
    {
        $barang = Barang::all()->map(function ($barang) {
            $stokMasuk = $barang->barangMasuk()->sum('jumlah');
            $stokKeluar = $barang->barangKeluar()->sum('jumlah_keluar');
            $barang->stok = $stokMasuk - $stokKeluar;
            return $barang;
        });
        return view('admin.barang.index', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            // 'harga_jual' => 'required',
            // 'harga_beli' => 'required',
            'kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $barang = new Barang();
        $barang->nama_barang = $request->nama_barang;
        $barang->satuan = $request->satuan;
        // $barang->harga_jual = $this->removeRupiahFormat($request->harga_jual);
        // $barang->harga_beli = $this->removeRupiahFormat($request->harga_beli);
        $barang->kategori = $request->kategori;

        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('images', 'public');
            $barang->gambar = $imagePath;
        }

        $barang->save();

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required',
            // 'harga_beli' => 'required',
            // 'kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $barang->nama_barang = $request->nama_barang;
        $barang->satuan = $request->satuan;
        // $barang->harga_jual = $this->removeRupiahFormat($request->harga_jual);
        // $barang->harga_beli = $this->removeRupiahFormat($request->harga_beli);
        $barang->kategori = $request->kategori;

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($barang->gambar && Storage::exists('public/' . $barang->gambar)) {
                Storage::delete('public/' . $barang->gambar);
            }

            $imagePath = $request->file('gambar')->store('images', 'public');
            $barang->gambar = $imagePath;
        }

        $barang->save();

        return redirect()->back()->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json(['success' => 'Barang berhasil dihapus.']);
    }


    private function removeRupiahFormat($value)
    {
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}
