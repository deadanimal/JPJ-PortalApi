<?php

use App\Http\Controllers\DrivingLicenseController;
use App\Http\Controllers\LicRegDrivingPermitController;
use App\Http\Controllers\PermohonanPenubuhanInstitutMemanduController;
use App\Http\Controllers\SemakanDimeritController;
use App\Http\Controllers\SemakanNomborPendaftaranController;
use App\Http\Controllers\SemakanPermohonanRayuanLesenController;
use App\Http\Controllers\SemakanPertukaranLesenMemanduLNController;
use App\Http\Controllers\SemakanSenaraiHitamController;
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
    return view('welcome');
});
Route::get('/try', function () {
    dd('try');
});
Route::get('/driving_license_expiracy', [DrivingLicenseController::class, 'submit']);
Route::get('/driving_permit', [LicRegDrivingPermitController::class, 'submit']);
Route::get('/semakan_nombor_pendaftaran', [SemakanNomborPendaftaranController::class, 'submit']);
Route::get('/semakan_permohonan_penubuhan_institut_memandu', [PermohonanPenubuhanInstitutMemanduController::class, 'submit']);

Route::get('/rayuan_permohonan_lesen/semak', [SemakanPermohonanRayuanLesenController::class, 'semak_status']);
Route::get('/rayuan_permohonan_lesen/cetak', [SemakanPermohonanRayuanLesenController::class, 'cetak_surat']);
Route::get('/rayuan_permohonan_lesen/permohonan', [SemakanPermohonanRayuanLesenController::class, 'permohonan']);

Route::get('/senarai_hitam', [SemakanSenaraiHitamController::class, 'submit']);
Route::get('/dimerit', [SemakanDimeritController::class, 'submit']);
Route::get('/semakan_pertukaran_lesen', [SemakanPertukaranLesenMemanduLNController::class, 'submit']);

Route::get('/checkemail', [SemakanPertukaranLesenMemanduLNController::class, 'checkemail']);

Route::get('send-mail', function () {
   
    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];
   
    \Mail::to('areshera01@gmail.com')->send(new \App\Mail\MyTestMail($details));
   
    dd("Email is Sent.");
});