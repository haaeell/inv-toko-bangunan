<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'jumlah_masuk',
        'jumlah_keluar',
        'satuan',
        'harga_jual',
        'harga_beli',
        'kategori',
        'gambar',
    ];
}
