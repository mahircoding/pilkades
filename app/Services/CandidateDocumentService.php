<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CandidateDocumentService
{
    public function store(Candidate $candidate, UploadedFile $file, string $type): CandidateDocument
    {
        $path = $file->store("candidates/{$candidate->id}/documents", 'local');

        $document = CandidateDocument::create([
            'candidate_id' => $candidate->id,
            'type' => $type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'verification_status' => CandidateDocument::STATUS_SUBMITTED,
        ]);

        activity()->on($document)->log("Dokumen [{$type}] diunggah");

        return $document;
    }

    public function verify(CandidateDocument $document, User $verifier, string $status, ?string $notes = null): CandidateDocument
    {
        $document->update([
            'verification_status' => $status,
            'verification_notes' => $notes,
            'verified_by' => $verifier->id,
            'verified_at' => now(),
        ]);

        activity()->on($document)
            ->withProperties(['status' => $status])
            ->log("Dokumen [{$document->type}] diverifikasi dengan status [{$status}]");

        return $document;
    }

    public function delete(CandidateDocument $document): void
    {
        Storage::disk('local')->delete($document->file_path);
        activity()->on($document)->log("Dokumen [{$document->type}] dihapus");
        $document->delete();
    }
}
