<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('barang', BarangController::class);
Route::resource('barang_masuk', BarangMasukController::class);
Route::resource('barang_keluar', BarangKeluarController::class);
Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::post('laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
Route::post('laporan/cetak_pdf', [LaporanController::class, 'cetakPDF'])->name('laporan.cetak_pdf');


