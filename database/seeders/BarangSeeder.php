<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'nama_barang' => 'Semen',
                'jumlah_masuk' => 100,
                'jumlah_keluar' => 30,
                'satuan' => 'Sak',
                'harga_jual' => 50000,
                'harga_beli' => 45000,
                'kategori' => 'Bahan Bangunan',
                'gambar' => 'semen.jpg'
            ],
            [
                'nama_barang' => 'Pasir',
                'jumlah_masuk' => 200,
                'jumlah_keluar' => 50,
                'satuan' => 'Kubik',
                'harga_jual' => 300000,
                'harga_beli' => 250000,
                'kategori' => 'Bahan Bangunan',
                'gambar' => 'pasir.jpg'
            ],
            [
                'nama_barang' => 'Batu Bata',
                'jumlah_masuk' => 5000,
                'jumlah_keluar' => 1000,
                'satuan' => 'Buah',
                'harga_jual' => 8000,
                'harga_beli' => 6000,
                'kategori' => 'Bahan Bangunan',
                'gambar' => 'batu_bata.jpg'
            ],
            [
                'nama_barang' => 'Paku',
                'jumlah_masuk' => 10000,
                'jumlah_keluar' => 3000,
                'satuan' => 'Kilo',
                'harga_jual' => 20000,
                'harga_beli' => 15000,
                'kategori' => 'Perkakas',
                'gambar' => 'paku.jpg'
            ],
            [
                'nama_barang' => 'Cat Tembok',
                'jumlah_masuk' => 150,
                'jumlah_keluar' => 70,
                'satuan' => 'Kaleng',
                'harga_jual' => 75000,
                'harga_beli' => 60000,
                'kategori' => 'Perawatan',
                'gambar' => 'cat_tembok.jpg'
            ],
            [
                'nama_barang' => 'Gipsum',
                'jumlah_masuk' => 80,
                'jumlah_keluar' => 20,
                'satuan' => 'Sak',
                'harga_jual' => 55000,
                'harga_beli' => 50000,
                'kategori' => 'Bahan Bangunan',
                'gambar' => 'gipsum.jpg'
            ],
            [
                'nama_barang' => 'Triplek',
                'jumlah_masuk' => 60,
                'jumlah_keluar' => 10,
                'satuan' => 'Lembar',
                'harga_jual' => 120000,
                'harga_beli' => 100000,
                'kategori' => 'Perkakas',
                'gambar' => 'triplek.jpg'
            ],
            [
                'nama_barang' => 'Kayu',
                'jumlah_masuk' => 30,
                'jumlah_keluar' => 5,
                'satuan' => 'Kubik',
                'harga_jual' => 250000,
                'harga_beli' => 200000,
                'kategori' => 'Bahan Bangunan',
                'gambar' => 'kayu.jpg'
            ],
            [
                'nama_barang' => 'Kabel',
                'jumlah_masuk' => 500,
                'jumlah_keluar' => 100,
                'satuan' => 'Roll',
                'harga_jual' => 150000,
                'harga_beli' => 120000,
                'kategori' => 'Peralatan Elektrik',
                'gambar' => 'kabel.jpg'
            ],
            [
                'nama_barang' => 'Keramik',
                'jumlah_masuk' => 200,
                'jumlah_keluar' => 50,
                'satuan' => 'Lembar',
                'harga_jual' => 60000,
                'harga_beli' => 50000,
                'kategori' => 'Bahan Bangunan',
                'gambar' => 'keramik.jpg'
            ],
            [
                'nama_barang' => 'Mortar',
                'jumlah_masuk' => 120,
                'jumlah_keluar' => 40,
                'satuan' => 'Sak',
                'harga_jual' => 70000,
                'harga_beli' => 65000,
                'kategori' => 'Bahan Bangunan',
                'gambar' => 'mortar.jpg'
            ],
        ]);
    }
}
