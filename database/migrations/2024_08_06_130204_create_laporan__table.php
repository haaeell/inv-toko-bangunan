<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
        $table->string('nama_barang'); // Menyimpan nama barang
        $table->integer('jumlah_barang_masuk')->default(0);
        $table->integer('jumlah_barang_keluar')->default(0);
        $table->decimal('penghasilan', 15, 2)->default(0.00);
        $table->decimal('keuntungan', 15, 2)->default(0.00);
        $table->date('tanggal_laporan');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_');
    }
};
