<?php

use App\Http\Controllers\PortalApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/semakan_tarikh_luput_lesen_kenderaan_motor', [PortalApiController::class, 'semakan_tarikh_luput_lesen_kenderaan_motor']);
Route::post('/semakan_nombor_pendaftaran', [PortalApiController::class, 'semakan_nombor_pendaftaran']);
Route::post('/semakan_status_permohonan_penubuhan_institut_memandu', [PortalApiController::class, 'semakan_status_permohonan_penubuhan_institut_memandu']);

Route::post('/rayuan_permohonan_lesen/semak', [PortalApiController::class, 'semak_status_permohonan']);
Route::post('/rayuan_permohonan_lesen/cetak', [PortalApiController::class, 'cetak_surat_permohonan']);
Route::post('/rayuan_permohonan_lesen/permohonan', [PortalApiController::class, 'permohonan_rayuan']);

Route::post('/semakan_tarikh_luput_lesen_memandu', [PortalApiController::class, 'semakan_tarikh_luput_lesen_memandu']);
Route::post('/semakan_status_senarai_hitam', [PortalApiController::class, 'semakan_status_senarai_hitam']);
Route::post('/semakan_dimerit', [PortalApiController::class, 'semakan_dimerit']);
Route::post('/semakan_pertukaran_lesen_memandu_luar_negara', [PortalApiController::class, 'semakan_pertukaran_lesen_memandu_luar_negara']);
Route::post('/semakan_ujian_memandu', [PortalApiController::class, 'semakan_ujian_memandu']);