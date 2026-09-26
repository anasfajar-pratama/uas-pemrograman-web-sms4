<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\Dosen\ExamController as DosenExamController;
use App\Http\Controllers\Dosen\ExamQuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isMahasiswa()
            ? redirect()->route('ujian.enter-code')
            : redirect()->route('dosen.dashboard');
    }
    return redirect()->route('login');
});

// ─── Auth ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Mahasiswa (ujian) ──────────────────────────────────
Route::middleware(['auth', 'role:mahasiswa'])->prefix('ujian')->name('ujian.')->group(function () {
    Route::get('/masuk',                  [StudentExamController::class, 'showEnterCode'])->name('enter-code');
    Route::post('/masuk',                 [StudentExamController::class, 'enterCode'])->name('submit-code');
    Route::get('/{examCode}',             [StudentExamController::class, 'index'])->name('index');
    Route::post('/{examCode}/jawab',      [StudentExamController::class, 'saveAnswer'])->name('jawab');
    Route::post('/{examCode}/sync-timer', [StudentExamController::class, 'syncTimer'])->name('sync-timer');
    Route::get('/{examCode}/selesai',     [StudentExamController::class, 'showFinish'])->name('selesai');
    Route::post('/{examCode}/selesai',    [StudentExamController::class, 'submitFinish'])->name('selesai.submit');
    Route::get('/{examCode}/hasil',       [StudentExamController::class, 'hasil'])->name('hasil');
});

// ─── Dosen ──────────────────────────────────────────────
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/',                  [DosenController::class, 'dashboard'])->name('dashboard');
    Route::get('/mahasiswa/{id}',    [DosenController::class, 'detailMahasiswa'])->name('detail');
    Route::get('/export-csv',        [DosenController::class, 'exportCsv'])->name('export');

    Route::prefix('ujian')->name('ujian.')->group(function () {
        Route::get('/',                  [DosenExamController::class, 'index'])->name('index');
        Route::get('/create',            [DosenExamController::class, 'create'])->name('create');
        Route::post('/',                 [DosenExamController::class, 'store'])->name('store');
        Route::get('/{id}',              [DosenExamController::class, 'show'])->name('show');
        Route::get('/{id}/edit',         [DosenExamController::class, 'edit'])->name('edit');
        Route::put('/{id}',              [DosenExamController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle',     [DosenExamController::class, 'toggleActive'])->name('toggle');
        Route::delete('/{id}',           [DosenExamController::class, 'destroy'])->name('destroy');

        Route::prefix('/{id}/soal')->name('soal.')->group(function () {
            Route::get('/',              [ExamQuestionController::class, 'index'])->name('index');
            Route::get('/create',        [ExamQuestionController::class, 'create'])->name('create');
            Route::post('/',             [ExamQuestionController::class, 'store'])->name('store');
            Route::get('/import',        [ExamQuestionController::class, 'showImport'])->name('import');
            Route::post('/import',       [ExamQuestionController::class, 'importExcel'])->name('import.store');
            Route::get('/template',      [ExamQuestionController::class, 'downloadTemplate'])->name('template');
            Route::get('/{qid}/edit',    [ExamQuestionController::class, 'edit'])->name('edit');
            Route::put('/{qid}',         [ExamQuestionController::class, 'update'])->name('update');
            Route::delete('/{qid}',      [ExamQuestionController::class, 'destroy'])->name('destroy');
        });
    });
});
