<?php

namespace App\Services;

use App\Models\ElectionPeriod;
use App\Models\Voter;

class VoterListService
{
    public function __construct(
        private readonly NikService $nikService
    ) {}

    public function promoteToDpt(ElectionPeriod $period): int
    {
        $updated = Voter::where('election_period_id', $period->id)
            ->where('list_status', Voter::STATUS_DPS)
            ->update(['list_status' => Voter::STATUS_DPT]);

        activity()
            ->withProperties(['election_period_id' => $period->id, 'promoted' => $updated])
            ->log("DPS dipromosikan ke DPT: {$updated} pemilih (Periode: {$period->name}).");

        return $updated;
    }

    /**
     * Look up a DPT voter by raw NIK.
     *
     * Only returns voters with list_status = dpt on the active election period.
     * Returns only safe public fields (no nik_encrypted).
     */
    public function findDptVoter(string $rawNik): ?array
    {
        if (! $this->nikService->isValid($rawNik)) {
            return null;
        }

        $nikHash = $this->nikService->hash($rawNik);
        $period  = ElectionPeriod::where('is_active', true)->first();

        if (! $period) {
            return null;
        }

        $voter = Voter::where('election_period_id', $period->id)
            ->where('nik_hash', $nikHash)
            ->where('list_status', Voter::STATUS_DPT)
            ->first(['id', 'name', 'nik_masked', 'tps_label', 'rt', 'rw', 'list_status']);

        if (! $voter) {
            return null;
        }

        return [
            'name'       => $voter->name,
            'nik_masked' => $voter->nik_masked,
            'tps_label'  => $voter->tps_label,
            'rt'         => $voter->rt,
            'rw'         => $voter->rw,
            'status'     => 'TERDAFTAR DI DPT',
            'period'     => $period->name,
        ];
    }
}
