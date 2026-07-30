<?php

namespace App\Models;

use Database\Factories\VoterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $election_period_id
 * @property string $nik_hash
 * @property string $nik_encrypted
 * @property string $nik_masked
 * @property string $name
 * @property string|null $gender
 * @property string|null $birth_place
 * @property Carbon|null $birth_date
 * @property string|null $address
 * @property string|null $rt
 * @property string|null $rw
 * @property string|null $tps_label
 * @property string $list_status
 * @property int|null $import_batch_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'election_period_id',
    'nik_hash',
    'nik_encrypted',
    'nik_masked',
    'name',
    'gender',
    'birth_place',
    'birth_date',
    'address',
    'rt',
    'rw',
    'tps_label',
    'list_status',
    'import_batch_id',
])]
#[Hidden(['nik_encrypted'])]
class Voter extends Model
{
    /** @use HasFactory<VoterFactory> */
    use HasFactory, LogsActivity;

    public const STATUS_DPS = 'dps';

    public const STATUS_DPT = 'dpt';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<ElectionPeriod, $this>
     */
    public function electionPeriod(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class);
    }

    /**
     * @return BelongsTo<VoterImportBatch, $this>
     */
    public function importBatch(): BelongsTo
    {
        return $this->belongsTo(VoterImportBatch::class, 'import_batch_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
