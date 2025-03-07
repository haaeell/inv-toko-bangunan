<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        $barangMasuk = BarangMasuk::with('barang')->orderBy('tanggal_masuk', 'desc')->get();
        return view('admin.barang_masuk.index', compact('barangMasuk', 'barang'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('barang_masuk.create', compact('barang'));
    }

    public function store(Request $request)
    {
        BarangMasuk::create([
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah_masuk,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'tanggal_masuk' => now(),
            'sisa_stok' => $request->jumlah_masuk
        ]);

        return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    public function edit(BarangMasuk $barangMasuk)
    {
        $barang = Barang::all();
        return view('barang_masuk.edit', compact('barangMasuk', 'barang'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'barang_id' => 'required|exists:barang,id',
        'jumlah_masuk' => 'required|integer|min:1',
    ]);

    $barangMasuk = BarangMasuk::findOrFail($id);
    $barang = Barang::findOrFail($request->barang_id);

    $jumlahSebelumnya = $barangMasuk->jumlah_masuk;
    $jumlahBaru = $request->jumlah_masuk;

    $barang->jumlah_masuk += ($jumlahBaru - $jumlahSebelumnya);
    $barang->save();

    $barangMasuk->update($request->all());

    return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
}


public function destroy($id)
{
    $barangMasuk = BarangMasuk::findOrFail($id);
    $barang = Barang::findOrFail($barangMasuk->barang_id);

    $barang->jumlah_masuk -= $barangMasuk->jumlah_masuk;
    $barang->save();

    $barangMasuk->delete();

    return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil dihapus.');
}

}
