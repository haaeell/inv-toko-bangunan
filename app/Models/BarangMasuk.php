<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $table = "barang_masuk";
    protected $fillable = ['barang_id', 'jumlah', 'harga_beli', 'harga_jual', 'tanggal_masuk', 'sisa_stok'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function totalHargaBeli()
    {
        return $this->jumlah * $this->harga_beli;
    }
}
