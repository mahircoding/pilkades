<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class VoterImportBatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'election_period_id' => \App\Models\ElectionPeriod::factory(),
            'uploaded_by' => \App\Models\User::factory(),
            'original_name' => 'dps.xlsx',
            'status' => \App\Models\VoterImportBatch::STATUS_PENDING,
        ];
    }
}
