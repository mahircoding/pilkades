<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class CandidateDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'candidate_id' => \App\Models\Candidate::factory(),
            'type' => 'KTP',
            'file_path' => 'documents/test.pdf',
            'original_name' => 'KTP.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1024,
            'verification_status' => \App\Models\CandidateDocument::STATUS_SUBMITTED,
        ];
    }
}
