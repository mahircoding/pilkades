<?php

use Illuminate\Support\Facades\Route;
use App\Models\ElectionPeriod;
use App\Models\Candidate;
use App\Models\Voter;
use App\Models\VoterImportBatch;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $activePeriod = ElectionPeriod::where('is_active', true)->first();
        $totalCandidates = 0;
        $verifiedCandidates = 0;
        $totalDps = 0;
        $totalDpt = 0;
        $latestImport = null;

        if ($activePeriod) {
            $totalCandidates = Candidate::where('election_period_id', $activePeriod->id)->count();
            $verifiedCandidates = Candidate::where('election_period_id', $activePeriod->id)
                ->where('verification_status', Candidate::STATUS_VERIFIED)
                ->count();
            
            $totalDps = Voter::where('election_period_id', $activePeriod->id)
                ->where('list_status', Voter::STATUS_DPS)->count();
            $totalDpt = Voter::where('election_period_id', $activePeriod->id)
                ->where('list_status', Voter::STATUS_DPT)->count();
                
            $latestImport = VoterImportBatch::where('election_period_id', $activePeriod->id)
                ->latest()->first();
        }

        return view('dashboard', compact(
            'activePeriod', 'totalCandidates', 'verifiedCandidates', 
            'totalDps', 'totalDpt', 'latestImport'
        ));
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
