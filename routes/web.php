<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\DetailBarangController;
use App\Http\Controllers\PenempatanBarangController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('barang', BarangController::class);
Route::resource('detail_barang', DetailBarangController::class);
// Route::get('/api/child-data/{id}', function ($id) {
//     // Fetch child data based on the provided ID
//     // Example data
//     $data = [
//         ['name' => 'John Doe', 'position' => 'Developer', 'office' => 'New York', 'age' => 30, 'start_date' => '2019/01/01', 'salary' => '$100,000'],
//         ['name' => 'Jane Smith', 'position' => 'Manager', 'office' => 'London', 'age' => 40, 'start_date' => '2018/05/15', 'salary' => '$150,000'],
//         // Add more data as needed
//     ];

//     return response()->json(['data' => $data]);
// });
Route::get('/api/child-data/{id}',[DetailBarangController::class, 'getSuratMasukData']);

Route::resource('surat_masuk', SuratMasukController::class);
Route::resource('ruangan', RuanganController::class);
Route::resource('penempatan_barang', PenempatanBarangController::class);
