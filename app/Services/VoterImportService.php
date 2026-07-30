<?php

namespace App\Services;

use App\Models\ElectionPeriod;
use App\Models\Voter;
use App\Models\VoterImportBatch;
use App\Models\VoterImportIssue;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VoterImportService
{
    public function __construct(
        private readonly NikService $nikService
    ) {}

    public function import(ElectionPeriod $period, UploadedFile $file, int $uploadedBy): VoterImportBatch
    {
        $batch = VoterImportBatch::create([
            'election_period_id' => $period->id,
            'uploaded_by'        => $uploadedBy,
            'original_name'      => $file->getClientOriginalName(),
            'status'             => VoterImportBatch::STATUS_PROCESSING,
            'started_at'         => now(),
        ]);

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            array_shift($rows); // Remove header row

            $seenNiks   = [];
            $total      = count($rows);
            $accepted   = 0;
            $duplicates = 0;
            $invalid    = 0;

            foreach ($rows as $rowNum => $row) {
                $rawNik = trim((string) ($row['A'] ?? ''));
                $name   = trim((string) ($row['B'] ?? ''));

                // Validate NIK
                if (! $this->nikService->isValid($rawNik)) {
                    $invalid++;
                    VoterImportIssue::create([
                        'voter_import_batch_id' => $batch->id,
                        'row_number'            => $rowNum + 1,
                        'nik_masked'            => $rawNik !== '' ? $this->nikService->mask($rawNik) : null,
                        'type'                  => VoterImportIssue::TYPE_INVALID,
                        'message'               => 'NIK tidak valid (harus 16 digit angka).',
                    ]);
                    continue;
                }

                $nikHash = $this->nikService->hash($rawNik);

                // Duplicate within this file
                if (isset($seenNiks[$nikHash])) {
                    $duplicates++;
                    VoterImportIssue::create([
                        'voter_import_batch_id' => $batch->id,
                        'row_number'            => $rowNum + 1,
                        'nik_masked'            => $this->nikService->mask($rawNik),
                        'type'                  => VoterImportIssue::TYPE_DUPLICATE_FILE,
                        'message'               => 'NIK duplikat dalam file yang diunggah.',
                    ]);
                    continue;
                }
                $seenNiks[$nikHash] = true;

                // Duplicate against existing DB voters
                $exists = Voter::where('election_period_id', $period->id)
                    ->where('nik_hash', $nikHash)
                    ->exists();

                if ($exists) {
                    $duplicates++;
                    VoterImportIssue::create([
                        'voter_import_batch_id' => $batch->id,
                        'row_number'            => $rowNum + 1,
                        'nik_masked'            => $this->nikService->mask($rawNik),
                        'type'                  => VoterImportIssue::TYPE_DUPLICATE_DB,
                        'message'               => 'NIK sudah terdaftar di DPS/DPT.',
                    ]);
                    continue;
                }

                $nikFields = $this->nikService->buildFields($rawNik);

                Voter::create(array_merge($nikFields, [
                    'election_period_id' => $period->id,
                    'name'               => $name ?: 'Tidak Diketahui',
                    'gender'             => trim((string) ($row['C'] ?? '')),
                    'birth_place'        => trim((string) ($row['D'] ?? '')),
                    'birth_date'         => trim((string) ($row['E'] ?? '')) ?: null,
                    'address'            => trim((string) ($row['F'] ?? '')),
                    'rt'                 => trim((string) ($row['G'] ?? '')),
                    'rw'                 => trim((string) ($row['H'] ?? '')),
                    'tps_label'          => trim((string) ($row['I'] ?? '')),
                    'list_status'        => Voter::STATUS_DPS,
                    'import_batch_id'    => $batch->id,
                ]));

                $accepted++;
            }

            $batch->update([
                'status'        => VoterImportBatch::STATUS_COMPLETED,
                'total_rows'    => $total,
                'accepted_rows' => $accepted,
                'duplicate_rows' => $duplicates,
                'invalid_rows'  => $invalid,
                'finished_at'   => now(),
            ]);

            activity()->on($batch)
                ->withProperties(compact('total', 'accepted', 'duplicates', 'invalid'))
                ->log("Impor DPS selesai: {$accepted}/{$total} diterima.");
        } catch (\Throwable $e) {
            $batch->update(['status' => VoterImportBatch::STATUS_FAILED, 'finished_at' => now()]);
            throw $e;
        }

        return $batch;
    }
}
