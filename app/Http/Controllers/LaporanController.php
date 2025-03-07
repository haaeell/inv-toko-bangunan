<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

        $laporanKeluar = BarangKeluar::whereBetween('created_at', [$startDate, $endDate])->get();
        $laporanMasuk = BarangMasuk::select(
            'barang_id',
            DB::raw('SUM(jumlah) as total_jumlah'),
            DB::raw('SUM(jumlah * harga_beli) as total_harga')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('barang_id')
            ->get();

        return view('admin.laporan.index', compact('laporanKeluar', 'laporanMasuk', 'bulan', 'tahun', 'tanggal'));
    }

    public function cetakPDF(Request $request)
    {
        $tanggalLaporan = $request->get('tanggal_laporan');
        $tanggal = Carbon::parse($tanggalLaporan);
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;

        $laporan = BarangKeluar::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)->get();

        $laporanMasuk = BarangMasuk::select(
            'barang_id',
            DB::raw('SUM(jumlah) as total_jumlah'),
            DB::raw('SUM(jumlah * harga_beli) as total_harga')
        )
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('barang_id')
            ->get();

        $pdf = PDF::loadView('admin.laporan.pdf', [
            'tanggal' => $tanggal,
            'laporan' => $laporan,
            'laporanMasuk' => $laporanMasuk
        ]);

        return $pdf->download('LAPORAN_' . $tanggal . '.pdf');
    }
}
