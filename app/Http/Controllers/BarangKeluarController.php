<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangKeluar;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangList = Barang::all();
        $barangKeluar = BarangKeluar::with('barang')->get();
        return view('admin.barang_keluar.index', compact('barangKeluar','barangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_keluar' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        $barang->jumlah_masuk -= $request->jumlah_keluar;
        $barang->save();

        BarangKeluar::create($request->all());
        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_keluar' => 'required|integer|min:1',
        ]);

        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = Barang::findOrFail($request->barang_id);

        $jumlahSebelumnya = $barangKeluar->jumlah_keluar;
        $jumlahBaru = $request->jumlah_keluar;

        $barang->jumlah_masuk += ($jumlahSebelumnya - $jumlahBaru);
        $barang->save();

        $barangKeluar->update($request->all());

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = Barang::findOrFail($barangKeluar->barang_id);

        $barang->jumlah_masuk += $barangKeluar->jumlah_keluar;
        $barang->save();

        $barangKeluar->delete();

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil dihapus.');
    }
}
