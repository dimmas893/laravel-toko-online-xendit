<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//API route for login user
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'apiLogin']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    
    Route::get('/total-kas', [App\Http\Controllers\KasController::class, 'apiTotalKas']);
    Route::post('/simpan-kas', [App\Http\Controllers\KasController::class, 'apiStore']);
    Route::post('/transaksi-confirs', [App\Http\Controllers\TransaksiController::class, 'apiVerifikasiTransaksi']);
    Route::post('/pengeluaran-confirs', [App\Http\Controllers\PengeluaranController::class, 'apiVerifikasiPengeluaran']);
    Route::post('/simpan-pengeluaran', [App\Http\Controllers\PengeluaranController::class, 'apiSimpanPengeluaran']);
    Route::post('/ubah-pengeluaran', [App\Http\Controllers\PengeluaranController::class, 'apiSimpanPengeluaran']);
    //Api Notifikasi
    Route::get('/api-notifikasi', [App\Http\Controllers\NotifikasiController::class, 'ApiNotif']);
    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});

Route::get('/api-data-transaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'apiDataTransaksi']);
Route::get('/api-data-pengeluaran/{id}', [App\Http\Controllers\PengeluaranController::class, 'apiDataPengeluaran']);
Route::get('/api-data-kas', [App\Http\Controllers\KasController::class, 'apiDataKas']);



