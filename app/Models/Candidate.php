<?php

namespace App\Models;

use Database\Factories\CandidateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $election_period_id
 * @property int|null $ballot_number
 * @property string $name
 * @property string $nik_hash
 * @property string $nik_encrypted
 * @property string $nik_masked
 * @property string|null $birth_place
 * @property Carbon|null $birth_date
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $photo_path
 * @property string|null $vision
 * @property string|null $mission
 * @property string $verification_status
 * @property string|null $verification_notes
 * @property int|null $verified_by
 * @property Carbon|null $verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'election_period_id',
    'ballot_number',
    'name',
    'nik_hash',
    'nik_encrypted',
    'nik_masked',
    'birth_place',
    'birth_date',
    'gender',
    'address',
    'photo_path',
    'vision',
    'mission',
    'verification_status',
    'verification_notes',
    'verified_by',
    'verified_at',
])]
#[Hidden(['nik_encrypted'])]
class Candidate extends Model
{
    /** @use HasFactory<CandidateFactory> */
    use HasFactory, LogsActivity;

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_VERIFIED = 'verified';

    public const STATUS_REJECTED = 'rejected';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'verified_at' => 'datetime',
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
     * @return HasMany<CandidateDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(CandidateDocument::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
