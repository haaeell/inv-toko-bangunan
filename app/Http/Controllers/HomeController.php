<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    // Jumlah data barang
    $barangCount = Barang::count();

    // Peringatan stok kurang dari 5
    $barangWarning = Barang::select('id', 'nama_barang', DB::raw('jumlah_masuk - jumlah_keluar as stok'))
        ->whereRaw('jumlah_masuk - jumlah_keluar < 5')
        ->get();

    // Data untuk chart jumlah stok
    $stokData = Barang::select('nama_barang', DB::raw('jumlah_masuk - jumlah_keluar as stok'))
        ->get();

    // Data untuk chart barang keluar
    $barangKeluarData = DB::table('barang_keluar')
        ->join('barang', 'barang_keluar.barang_id', '=', 'barang.id')
        ->select('barang.nama_barang', DB::raw('SUM(barang_keluar.jumlah_keluar) as total_keluar'))
        ->groupBy('barang.nama_barang')
        ->get();

    // Data untuk chart barang masuk
    $barangMasukData = DB::table('barang_masuk')
        ->join('barang', 'barang_masuk.barang_id', '=', 'barang.id')
        ->select('barang.nama_barang', DB::raw('SUM(barang_masuk.jumlah_masuk) as total_masuk'))
        ->groupBy('barang.nama_barang')
        ->get();
        

    return view('home', compact('barangCount', 'barangWarning', 'stokData', 'barangKeluarData', 'barangMasukData'));
}


}
