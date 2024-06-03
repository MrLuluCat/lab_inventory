<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\DetailBarangController;
use App\Http\Controllers\PenempatanBarangController;

Route::get('/', function () {
    // return view('welcome');
    return redirect('dashboard');
});

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('dashboard', DashboardController::class);


// Barang Controller
Route::resource('barang', BarangController::class);

// Detail Barang Controller
Route::resource('detail_barang', DetailBarangController::class);
Route::get('/api/child-data/{id}',[DetailBarangController::class, 'getSuratMasukData']);

// Surat Masuk Controller
Route::resource('surat_masuk', SuratMasukController::class);
Route::get('/api/child-barang/{id}', [SuratMasukController::class, 'getDetailBarangData']);

// Ruangan Controller
Route::resource('ruangan', RuanganController::class);

// Penempatan Barang Controller
Route::resource('penempatan_barang', PenempatanBarangController::class);
