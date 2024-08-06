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
        $tanggal = $request->input('tanggal', date('Y-m'));
        $startDate = date('Y-m-01', strtotime($tanggal));
        $endDate = date('Y-m-t', strtotime($tanggal));

        // Mengambil laporan berdasarkan bulan dan tahun
        $laporan = Laporan::whereYear('tanggal_laporan', date('Y', strtotime($tanggal)))
            ->whereMonth('tanggal_laporan', date('m', strtotime($tanggal)))
            ->get();

        return view('admin.laporan.index', compact('laporan', 'tanggal'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date_format:Y-m',
        ]);

        $tanggal = $request->tanggal_laporan;
        $startDate = date('Y-m-01', strtotime($tanggal));
        $endDate = date('Y-m-t', strtotime($tanggal));

        // Menghapus laporan yang ada untuk bulan ini
        Laporan::whereYear('tanggal_laporan', date('Y', strtotime($tanggal)))
            ->whereMonth('tanggal_laporan', date('m', strtotime($tanggal)))
            ->delete();

        // Menghitung laporan baru
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

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui.');
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
        return $pdf->download('LAPORAN_'.$tanggal.'.pdf');
    }
}
