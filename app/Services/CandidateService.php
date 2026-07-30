<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\ElectionPeriod;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Facades\Activity;

class CandidateService
{
    public function __construct(
        private readonly NikService $nikService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function createCandidate(ElectionPeriod $period, array $data): Candidate
    {
        $nikFields = $this->nikService->buildFields($data['nik']);
        unset($data['nik'], $data['photo']);

        $candidate = Candidate::create(array_merge($data, $nikFields, [
            'election_period_id' => $period->id,
            'verification_status' => Candidate::STATUS_SUBMITTED,
        ]));

        return $candidate;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateCandidate(Candidate $candidate, array $data): Candidate
    {
        if (isset($data['nik'])) {
            $nikFields = $this->nikService->buildFields($data['nik']);
            $data = array_merge($data, $nikFields);
            unset($data['nik']);
        }

        unset($data['photo']);

        $candidate->update($data);

        return $candidate;
    }

    public function storePhoto(Candidate $candidate, \Illuminate\Http\UploadedFile $photo): Candidate
    {
        if ($candidate->photo_path) {
            Storage::disk('local')->delete($candidate->photo_path);
        }

        $path = $photo->store('candidates/photos', 'local');

        $candidate->update(['photo_path' => $path]);

        activity()->on($candidate)->log('Foto calon diunggah');

        return $candidate;
    }

    public function verify(Candidate $candidate, User $verifier, string $status, ?string $notes = null): Candidate
    {
        $candidate->update([
            'verification_status' => $status,
            'verification_notes' => $notes,
            'verified_by' => $verifier->id,
            'verified_at' => now(),
        ]);

        activity()->on($candidate)
            ->withProperties(['status' => $status, 'notes' => $notes])
            ->log("Status verifikasi calon diubah menjadi [{$status}]");

        return $candidate;
    }
}
