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
    $bulan = $request->input('bulan', date('n'));
    $tahun = $request->input('tahun', date('Y'));

    $tanggal = "{$tahun}-{$bulan}";
    // Menentukan tanggal awal dan akhir bulan
    $startDate = "{$tahun}-{$bulan}-01";
    $endDate = date('Y-m-t', strtotime($startDate));

    // Query untuk mendapatkan data barang masuk berdasarkan tanggal
    $barangMasuk = DB::table('barang_masuk')
        ->join('barang', 'barang_masuk.barang_id', '=', 'barang.id')
        ->whereBetween('barang_masuk.tanggal_masuk', [$startDate, $endDate])
        ->select('barang_masuk.barang_id', 'barang.nama_barang', 'barang_masuk.tanggal_masuk', 'barang_masuk.harga_beli', 'barang_masuk.jumlah')
        ->orderBy('barang_masuk.tanggal_masuk')  // Urutkan berdasarkan tanggal masuk barang
        ->get();

    // Query untuk mendapatkan total penjualan dan total jumlah barang keluar
    $barangKeluar = DB::table('barang_keluar')
        ->join('barang', 'barang_keluar.barang_id', '=', 'barang.id')
        ->whereBetween('barang_keluar.created_at', [$startDate, $endDate])
        ->select(
            'barang_keluar.barang_id',
            DB::raw('SUM(barang_keluar.jumlah_keluar) as total_barang_keluar'),
            DB::raw('SUM(barang_keluar.harga_jual) as total_penjualan')
        )
        ->groupBy('barang_keluar.barang_id')
        ->get();

    // Proses FIFO untuk menghitung pembelian barang yang keluar
    $laporan = $barangKeluar->map(function ($item) use ($barangMasuk) {
        // Ambil semua barang masuk yang sesuai dengan barang_id
        $barangMasukData = $barangMasuk->where('barang_id', $item->barang_id);

        $totalPembelian = 0;
        $totalBarangKeluar = $item->total_barang_keluar;
        $hargaBeliPerBarangKeluar = [];

        // Menggunakan FIFO untuk menghitung harga beli barang yang keluar
        foreach ($barangMasukData as $barang) {
            // Jika jumlah barang yang keluar sudah tercapai, berhenti
            if ($totalBarangKeluar <= 0) {
                break;
            }

            // Hitung jumlah barang yang akan keluar dari stok FIFO
            $jumlahKeluar = min($totalBarangKeluar, $barang->jumlah);  // Ambil yang pertama kali masuk
            $totalBarangKeluar -= $jumlahKeluar;

            // Hitung total pembelian (harga beli * jumlah yang keluar)
            $totalPembelian += $jumlahKeluar * $barang->harga_beli;

            // Catat harga beli per barang keluar
            $hargaBeliPerBarangKeluar[] = [
                'jumlah_keluar' => $jumlahKeluar,
                'harga_beli' => $barang->harga_beli,
                'total' => $jumlahKeluar * $barang->harga_beli
            ];

            // Kurangi jumlah barang yang masuk setelah keluar
            $barang->jumlah -= $jumlahKeluar;
        }

        // Menghitung total penjualan (sudah ada di data barang keluar)
        $totalPenjualan = $item->total_penjualan;

        // Menghitung penghasilan (total penjualan - total pembelian)
        $penghasilan = $totalPenjualan - $totalPembelian;

        return [
            'nama_barang' => $barangMasukData->first()->nama_barang,
            'total_barang_keluar' => $item->total_barang_keluar,
            'total_pembelian' => $totalPembelian,
            'total_penjualan' => $totalPenjualan,
            'penghasilan' => $penghasilan,
            'harga_beli_per_barang_keluar' => $hargaBeliPerBarangKeluar,
        ];
    });

    // Kirim data ke view
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
