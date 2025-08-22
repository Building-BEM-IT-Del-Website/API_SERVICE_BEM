<?php

use App\Http\Controllers\AspirasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisOrmawaController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\SubKategoriController;
use App\Http\Controllers\UserController;

Route::prefix('auth')->group(function () {
    // Login route tanpa middleware
    Route::post('login', [AuthController::class, 'login']);

    // Hanya bisa diakses jika sudah login (token valid)
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
    });
});


// Route CRUD dengan apiResource
Route::middleware('auth:api')->group(function () {
    // RESTful CRUD user
    Route::apiResource('users', UserController::class)->middleware('struktur_permission:kelola_anggota');
    // Extra: restore & force-delete
    Route::prefix('users')->group(function () {
        Route::post('restore/{id}', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('force-delete/{id}', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    });

    Route::apiResource('jenis-ormawas', JenisOrmawaController::class);

    // Rute tambahan untuk soft delete
    Route::prefix('jenis-ormawas')->group(function () {
        Route::get('trashed/list', [JenisOrmawaController::class, 'trashed']);
        Route::post('restore/{id}', [JenisOrmawaController::class, 'restore']);
        Route::delete('force-delete/{id}', [JenisOrmawaController::class, 'forceDelete']);
    });

    Route::apiResource('jabatan', JabatanController::class);



    Route::apiResource('ormawas', OrmawaController::class);

    Route::prefix('ormawas')->group(function () {
        Route::get('trashed/list', [OrmawaController::class, 'trashed']);
        Route::post('restore/{id}', [OrmawaController::class, 'restore']);
        Route::delete('force-delete/{id}', [OrmawaController::class, 'forceDelete']);
    });

    Route::apiResource('struktur-organisasi', StrukturOrganisasiController::class);

    Route::prefix('struktur-organisasi')->group(function () {
        Route::get('trashed/list', [StrukturOrganisasiController::class, 'trashed']);
        Route::post('restore/{id}', [StrukturOrganisasiController::class, 'restore']);
        Route::delete('force-delete/{id}', [StrukturOrganisasiController::class, 'forceDelete']);
    });

    Route::apiResource('sub-kategori', SubKategoriController::class);

    Route::prefix('sub-kategori')->group(function () {
        Route::get('trashed/list', [SubKategoriController::class, 'trashed']);
        Route::post('restore/{id}', [SubKategoriController::class, 'restore']);
        Route::delete('force-delete/{id}', [SubKategoriController::class, 'forceDelete']);
    });
    Route::apiResource('kategori', KategoriController::class);

    Route::prefix('kategori')->group(function () {
        Route::get('trashed/list', [KategoriController::class, 'trashed']);
        Route::post('restore/{id}', [KategoriController::class, 'restore']);
        Route::delete('force-delete/{id}', [KategoriController::class, 'forceDelete']);
    });

    Route::prefix('aspirasi')->group(function () {
        // butuh permission 'lihat_aspirasi' (admin & kemahasiswaan)
        Route::get('{aspirasi}', [AspirasiController::class, 'show'])
            ->name('show')
            ->middleware('permission:lihat_aspirasi');

        // butuh permission 'kelola_aspirasi' (admin)
        Route::middleware('permission:kelola_aspirasi')->group(function () {
            Route::patch('{aspirasi}', [AspirasiController::class, 'update'])->name('update');
            Route::delete('{aspirasi}', [AspirasiController::class, 'destroy'])->name('destroy');
            Route::get('trashed/list', [AspirasiController::class, 'trashed'])->name('trashed');
            Route::post('restore/{id}', [AspirasiController::class, 'restore'])->name('restore');
            Route::delete('force-delete/{id}', [AspirasiController::class, 'forceDelete'])->name('forceDelete');
        });
    });
    
    // butuh permission 'kelola_kalender' (admin)
    Route::prefix('kalender')->middleware(['permission:kelola_kalender'])->group(function () {
        Route::post('/', [KalenderController::class, 'store'])->name('store');
        Route::patch('{kalender}', [KalenderController::class, 'update'])->name('update');
        Route::delete('{kalender}', [KalenderController::class, 'destroy'])->name('destroy');

        Route::get('trashed/list', [KalenderController::class, 'trashed'])->name('trashed');
        Route::post('restore/{id}', [KalenderController::class, 'restore'])->name('restore');
        Route::delete('force-delete/{id}', [KalenderController::class, 'forceDelete'])->name('forceDelete');
    });
});

// Rute Publik untuk aspirasi
Route::get('aspirasi/', [AspirasiController::class, 'index'])->name('index');
Route::post('aspirasi/', [AspirasiController::class, 'store'])->name('store');

// Rute publik untuk kalender
Route::get('kalender', [KalenderController::class, 'index'])->name('kalender.index');
Route::get('kalender/{kalender}', [KalenderController::class, 'show'])->name('kalender.show');