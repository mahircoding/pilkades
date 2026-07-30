<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class VoterImportIssueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'voter_import_batch_id' => \App\Models\VoterImportBatch::factory(),
            'row_number' => 2,
            'nik_masked' => '3201****1234',
            'type' => \App\Models\VoterImportIssue::TYPE_INVALID,
            'message' => 'NIK tidak valid',
        ];
    }
}
