<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangKeluar;
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

        $laporan = BarangKeluar::whereBetween('created_at', [$startDate, $endDate])->get();
        return view('admin.laporan.index', compact('laporan', 'bulan', 'tahun', 'tanggal'));
    }

    public function cetakPDF(Request $request)
    {
        $tanggalLaporan = $request->get('tanggal_laporan');
        $tanggal = Carbon::parse($tanggalLaporan);
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;

        $laporan = BarangKeluar::whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)->get();

        $pdf = PDF::loadView('admin.laporan.pdf', [
            'tanggal' => $tanggal,
            'laporan' => $laporan,
        ]);

        return $pdf->download('LAPORAN_' . $tanggal . '.pdf');
    }
}
