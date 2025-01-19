<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    $bulan = $request->input('bulan', date('n'));
    $tahun = $request->input('tahun', date('Y'));

    $tanggal = "{$tahun}-{$bulan}";
    // Menentukan tanggal awal dan akhir bulan
    $startDate = "{$tahun}-{$bulan}-01";
    $endDate = date('Y-m-t', strtotime($startDate));

    $laporan = BarangKeluar::whereBetween('created_at', [$startDate, $endDate])->get();
    return view('admin.laporan.index', compact('laporan', 'bulan', 'tahun', 'tanggal'));
}







    public function generate(Request $request)
    {

        $tanggal = $request->tanggal_laporan;
        $startDate = date('Y-m-01', strtotime($tanggal));
        $endDate = date('Y-m-t', strtotime($tanggal));

        Laporan::whereYear('tanggal_laporan', date('Y', strtotime($tanggal)))
            ->whereMonth('tanggal_laporan', date('m', strtotime($tanggal)))
            ->delete();

        $barangList = Barang::all();
        foreach ($barangList as $barang) {
            $jumlahMasuk = DB::table('barang_masuk')
                ->where('barang_id', $barang->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('jumlah_masuk');

            $jumlahKeluar = DB::table('barang_keluar')
                ->where('barang_id', $barang->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('jumlah_keluar');

            $penghasilan = $barang->harga_jual * $jumlahKeluar;
            $keuntungan = $penghasilan - ($barang->harga_beli * $jumlahKeluar);

            Laporan::create([
                'nama_barang' => $barang->nama_barang,
                'jumlah_barang_masuk' => $jumlahMasuk,
                'jumlah_barang_keluar' => $jumlahKeluar,
                'penghasilan' => $penghasilan,
                'keuntungan' => $keuntungan,
                'tanggal_laporan' => $startDate,
            ]);
        }

        return redirect()->route('laporan.index', ['bulan' => date('n', strtotime($tanggal)), 'tahun' => date('Y', strtotime($tanggal))])
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function cetakPDF(Request $request)
    {
        $tanggal = $request->input('tanggal_laporan', date('Y-m'));
        $startDate = date('Y-m-01', strtotime($tanggal));
        $endDate = date('Y-m-t', strtotime($tanggal));

        $laporan = Laporan::whereYear('tanggal_laporan', date('Y', strtotime($tanggal)))
            ->whereMonth('tanggal_laporan', date('m', strtotime($tanggal)))
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporan', 'tanggal'));
        return $pdf->download('LAPORAN_' . $tanggal . '.pdf');
    }
}
