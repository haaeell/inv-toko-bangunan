<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $table = "barang_keluar";
    protected $fillable = ['barang_id', 'jumlah_keluar', 'harga_jual'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function totalHargaJual()
    {
        return $this->jumlah_keluar * $this->harga_jual;
    }
}
