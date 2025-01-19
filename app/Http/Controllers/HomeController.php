<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Jumlah data barang
        $barangCount = Barang::count();

        // Peringatan stok kurang dari 5
        $barangWarning = Barang::all()->map(function ($barang) {
            $stokMasuk = $barang->barangMasuk()->sum('jumlah');
            $stokKeluar = $barang->barangKeluar()->sum('jumlah_keluar');
            $barang->stok = $stokMasuk - $stokKeluar;
            return $barang;
        })->filter(function ($barang) {
            return $barang->stok < 5; // Hanya ambil barang dengan stok kurang dari 5
        });


        // Data untuk chart jumlah stok
        $stokData = Barang::all()->map(function ($barang) {
            $stokMasuk = $barang->barangMasuk()->sum('jumlah');
            $stokKeluar = $barang->barangKeluar()->sum('jumlah_keluar');
            $barang->stok = $stokMasuk - $stokKeluar;
            return $barang;
        });


        return view('home', compact('barangCount', 'barangWarning', 'stokData'));
    }
}
