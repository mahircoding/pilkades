<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DesaProfileController;
use App\Http\Controllers\Admin\ElectionPeriodController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\PublicDptCheckController;
use Illuminate\Support\Facades\Route;

// ─── Public routes ───────────────────────────────────────────────────────────

Route::middleware('throttle:dpt-check')->group(function () {
    Route::get('/cek-dpt', [PublicDptCheckController::class, 'index'])->name('public.dpt-check.index');
    Route::post('/cek-dpt', [PublicDptCheckController::class, 'store'])->name('public.dpt-check.store');
});

// ─── Admin routes ─────────────────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'role_or_permission:super-admin|panitia|verifikator|pengawas'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Profil Desa
        Route::get('/profil-desa', [DesaProfileController::class, 'edit'])
            ->middleware('permission:manage desa profile')
            ->name('desa-profile.edit');

        Route::put('/profil-desa', [DesaProfileController::class, 'update'])
            ->middleware('permission:manage desa profile')
            ->name('desa-profile.update');

        // Periode Pemilihan
        Route::resource('periode-pemilihan', ElectionPeriodController::class)
            ->middleware('permission:manage election periods')
            ->names('election-periods')
            ->except(['show', 'destroy']);

        // Calon Kepala Desa
        Route::resource('calon', CandidateController::class)
            ->names('candidates');

        Route::post('/calon/{candidate}/verifikasi', [CandidateController::class, 'verify'])
            ->middleware('permission:verify candidates')
            ->name('candidates.verify');

        Route::post('/calon/{candidate}/dokumen', [CandidateController::class, 'storeDocument'])
            ->middleware('permission:manage candidates')
            ->name('candidates.documents.store');

        // DPS / DPT
        Route::get('/pemilih', [VoterController::class, 'index'])
            ->middleware('permission:manage voters')
            ->name('voters.index');

        Route::get('/pemilih/impor', [VoterController::class, 'importCreate'])
            ->middleware('permission:import voters')
            ->name('voters.import-create');

        Route::post('/pemilih/impor', [VoterController::class, 'importStore'])
            ->middleware('permission:import voters')
            ->name('voters.import-store');

        Route::get('/pemilih/impor/{batch}', [VoterController::class, 'importShow'])
            ->middleware('permission:import voters')
            ->name('voters.import-show');

        Route::post('/pemilih/promosi-dpt', [VoterController::class, 'promoteToDpt'])
            ->middleware('permission:manage voters')
            ->name('voters.promote-dpt');

        // Activity Log
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->middleware('permission:view activity logs')
            ->name('activity-logs.index');
    });
