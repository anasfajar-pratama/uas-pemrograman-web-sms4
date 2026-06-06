<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\DosenController;
use Illuminate\Support\Facades\Route;

// Halaman utama → redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Auth ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register',          [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',         [AuthController::class, 'register']);
    Route::get('/login',             [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',            [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Mahasiswa (ujian) ──────────────────────────────────
Route::middleware(['auth', 'role:mahasiswa'])->prefix('ujian')->name('ujian.')->group(function () {
    Route::get('/',                  [ExamController::class, 'index'])->name('index');
    Route::post('/jawab',            [ExamController::class, 'saveAnswer'])->name('jawab');
    Route::post('/sync-timer',       [ExamController::class, 'syncTimer'])->name('sync-timer');
    Route::get('/selesai',           [ExamController::class, 'showFinish'])->name('selesai');
    Route::post('/selesai',          [ExamController::class, 'submitFinish'])->name('selesai.submit');
    Route::get('/hasil',             [ExamController::class, 'hasil'])->name('hasil');
});

// ─── Dosen ──────────────────────────────────────────────
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/',                  [DosenController::class, 'dashboard'])->name('dashboard');
    Route::get('/mahasiswa/{id}',    [DosenController::class, 'detailMahasiswa'])->name('detail');
    Route::get('/export-csv',        [DosenController::class, 'exportCsv'])->name('export');
});
