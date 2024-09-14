<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [\App\Http\Controllers\AuthController::class, 'login'])
    ->name('login');

Route::post('login', [\App\Http\Controllers\AuthController::class, 'postLogin'])
    ->name('postLogin');

Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])
    ->name('logout');

Route::group(['middleware' => ['admin', 'auth']], function() {
    Route::resource('penerimaan_barang', \App\Http\Controllers\PenerimaanBarangController::class);
    Route::resource('pengeluaran_barang', \App\Http\Controllers\PengeluaranBarangController::class);
    Route::resource('barang', \App\Http\Controllers\BarangController::class);
    Route::resource('gudang', \App\Http\Controllers\GudangController::class);

    Route::resource('user', \App\Http\Controllers\UserController::class);

    Route::get('laporan_stok_minimum', [\App\Http\Controllers\LaporanStokMinimumController::class, 'index'])
        ->name('laporan_stok_minimum');
    Route::post('laporan_stok_minimum/datasource', [\App\Http\Controllers\LaporanStokMinimumController::class, 'datasource'])
        ->name('laporan_stok_minimum.datasource');

    Route::get('laporan_kartu_stok', [\App\Http\Controllers\LaporanKartuStokController::class, 'index'])
        ->name('laporan_kartu_stok');
    Route::post('laporan_kartu_stok/datasource', [\App\Http\Controllers\LaporanKartuStokController::class, 'datasource'])
        ->name('laporan_kartu_stok.datasource');

    Route::get('/', function () {
        return view('welcome');
    });
});
