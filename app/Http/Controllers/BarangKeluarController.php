<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangKeluar = BarangKeluar::with('barang')->get();

        $barangList = Barang::all()->map(function ($barang) {
            $stokMasuk = $barang->barangMasuk()->sum('jumlah');
            $stokKeluar = $barang->barangKeluar()->sum('jumlah_keluar');
            $barang->stok = $stokMasuk - $stokKeluar;
            return $barang;
        });

        return view('admin.barang_keluar.index', compact('barangKeluar', 'barangList'));
    }

    public function sfsdf(Request $request)
    {
        $barangMasuk = BarangMasuk::where('barang_id', $request->barang_id)
            ->where('jumlah', '>', 0)
            ->whereRaw('jumlah - stok_keluar > 0')
            ->orderBy('tanggal_masuk')
            ->get();

        $remainingJumlah = $request->jumlah_keluar;
        $totalHargaJual = 0;

        foreach ($barangMasuk as $masuk) {
            if ($remainingJumlah <= 0) {
                break;
            }

            $jumlahKeluar = $request->jumlah_keluar;

            $masuk->stok_keluar += $jumlahKeluar;
            $masuk->save();

            $totalHargaJual += $jumlahKeluar * $masuk->harga_jual;

            // BarangKeluar::create([
            //     'barang_id' => $request->barang_id,
            //     'jumlah_keluar' => $jumlahKeluar,
            //     'harga_jual' => $totalHargaJual,  
            // ]);

            $data = [
                'barang_id' => $request->barang_id,
                'jumlah_keluar' => $jumlahKeluar,
                'harga_jual' => $totalHargaJual,
            ];
            dd($data);
            $remainingJumlah -= $jumlahKeluar;
        }

        if ($remainingJumlah > 0) {
            return redirect()->route('barang_keluar.index')->with('error', 'Jumlah barang keluar melebihi stok yang tersedia.');
        }

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil ditambahkan. Total harga jual: ' . number_format($totalHargaJual, 0, ',', '.'));
    }

    public function store(Request $request)
    {
        $barangId = $request->barang_id;
        $jumlahKeluar = $request->jumlah_keluar;

        $barangMasuk = BarangMasuk::where('barang_id', $barangId)
            ->where('sisa_stok', '>', 0) 
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        $sisaJumlah = $jumlahKeluar;
        $hargaJualTotal = 0;
        $hargaBeliTotal = 0;

        foreach ($barangMasuk as $masuk) {
            if ($sisaJumlah <= $masuk->sisa_stok) {
                $hargaJualTotal += $sisaJumlah * $masuk->harga_jual;
                $hargaBeliTotal += $sisaJumlah * $masuk->harga_beli;
                $masuk->sisa_stok -= $sisaJumlah;
                $masuk->save();
                $sisaJumlah = 0; 
                break;
            } else {
                $hargaJualTotal += $masuk->sisa_stok * $masuk->harga_jual;
                $hargaBeliTotal += $masuk->sisa_stok * $masuk->harga_beli;
                $sisaJumlah -= $masuk->sisa_stok;
                $masuk->sisa_stok = 0;
                $masuk->save();
            }
        }

        if ($sisaJumlah > 0) {
            return redirect()->route('barang_keluar.index')->with('error', 'Jumlah barang keluar melebihi stok yang tersedia.');
        }

        BarangKeluar::create([
            'barang_id' => $barangId,
            'jumlah_keluar' => $jumlahKeluar,
            'harga_jual' => $hargaJualTotal,
            'harga_beli' => $hargaBeliTotal,
        ]);

        return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil ditambahkan. Total harga jual: ' . number_format($hargaJualTotal, 0, ',', '.'));
    }




    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'barang_id' => 'required|exists:barang,id',
    //         'jumlah_keluar' => 'required|integer|min:1',
    //     ]);

    //     $barangKeluar = BarangKeluar::findOrFail($id);
    //     $barang = Barang::findOrFail($request->barang_id);

    //     $jumlahSebelumnya = $barangKeluar->jumlah_keluar;
    //     $jumlahBaru = $request->jumlah_keluar;

    //     $stokTersedia = $barang->barangMasuk()->sum('jumlah') - $barang->barangKeluar->sum('jumlah_keluar');

    //     if ($jumlahBaru > $stokTersedia) {
    //         return redirect()->back()->withErrors(['jumlah_keluar' => 'Jumlah keluar melebihi stok yang tersedia.'])->withInput();
    //     }

    //     // Update stok
    //     $barang->barangMasuk()->sum('jumlah') -= ($jumlahSebelumnya - $jumlahBaru);
    //     $barang->save();

    //     $barangKeluar->update($request->only('barang_id', 'jumlah_keluar'));

    //     return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil diperbarui.');
    // }

    // public function destroy($id)
    // {
    //     $barangKeluar = BarangKeluar::findOrFail($id);
    //     $barang = Barang::findOrFail($barangKeluar->barang_id);

    //     // Mengembalikan stok barang yang keluar
    //     $barang->barangMasuk()->sum('jumlah') += $barangKeluar->jumlah_keluar;
    //     $barang->save();

    //     $barangKeluar->delete();

    //     return redirect()->route('barang_keluar.index')->with('success', 'Barang keluar berhasil dihapus.');
    // }
}
