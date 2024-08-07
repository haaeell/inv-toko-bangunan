<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('n')); // Get month number or default to current month
        $tahun = $request->input('tahun', date('Y')); // Get year or default to current year

        // Build start and end date for the given month and year
        $startDate = "{$tahun}-{$bulan}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $tanggal = "{$tahun}-{$bulan}";

        $laporan = DB::table('barang')
            ->select(
                'barang.nama_barang',
                'harga_jual',
                'harga_beli',
                DB::raw('COALESCE(SUM(barang_masuk.jumlah_masuk), 0) as jumlah_barang_masuk'),
                DB::raw('COALESCE(SUM(barang_keluar.jumlah_keluar), 0) as jumlah_barang_keluar'),
                DB::raw('COALESCE(SUM(barang_keluar.jumlah_keluar * barang.harga_jual), 0) as penghasilan'),
                DB::raw('COALESCE(SUM(barang_keluar.jumlah_keluar * (barang.harga_jual - barang.harga_beli)), 0) as keuntungan'),
                DB::raw('DATE_FORMAT("' . $startDate . '", "%Y-%m") as tanggal_laporan')
            )
            ->leftJoin('barang_masuk', 'barang.id', '=', 'barang_masuk.barang_id')
            ->leftJoin('barang_keluar', 'barang.id', '=', 'barang_keluar.barang_id')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('barang_masuk.created_at', [$startDate, $endDate])
                    ->orWhereBetween('barang_keluar.created_at', [$startDate, $endDate]);
            })
            ->groupBy('barang.id', 'barang.nama_barang', 'barang.harga_jual', 'barang.harga_beli')
            ->get();

            $totalPenghasilan = $laporan->sum('penghasilan');
            $totalKeuntungan = $laporan->sum('keuntungan');

        return view('admin.laporan.index', compact('laporan', 'tanggal', 'bulan', 'tahun','totalPenghasilan','totalKeuntungan'));
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
