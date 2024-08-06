<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = "laporan";
    protected $fillable = [
        'nama_barang',
        'jumlah_barang_masuk',
        'jumlah_barang_keluar',
        'penghasilan',
        'keuntungan',
        'tanggal_laporan',
    ];
}
