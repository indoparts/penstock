<?php

use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\DataProdukController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();
Route::middleware('auth')->group(static function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('users', UserController::class);
    Route::resource('penjualan', DataPenjualanController::class);
    Route::resource('produk', DataProdukController::class);
    Route::get('perhitungan', [PeramalanController::class, 'perhitungan'])->name('perhitungan');
    Route::post('setup_a', [PeramalanController::class, 'setup_a'])->name('setup_a');
    Route::post('setup_x', [PeramalanController::class, 'setup_x'])->name('setup_x');
    Route::get('perhitungan-export', [PeramalanController::class, 'export'])->name('perhitungan_export');
});
