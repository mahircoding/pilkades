<?php

namespace App\Models;

use Database\Factories\VoterImportBatchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $election_period_id
 * @property int|null $uploaded_by
 * @property string $original_name
 * @property string $status
 * @property int $total_rows
 * @property int $accepted_rows
 * @property int $duplicate_rows
 * @property int $invalid_rows
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'election_period_id',
    'uploaded_by',
    'original_name',
    'status',
    'total_rows',
    'accepted_rows',
    'duplicate_rows',
    'invalid_rows',
    'started_at',
    'finished_at',
])]
class VoterImportBatch extends Model
{
    /** @use HasFactory<VoterImportBatchFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
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
     * @return BelongsTo<User, $this>
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * @return HasMany<VoterImportIssue, $this>
     */
    public function issues(): HasMany
    {
        return $this->hasMany(VoterImportIssue::class);
    }
}
