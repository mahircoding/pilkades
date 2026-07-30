<?php

namespace App\Models;

use Database\Factories\ElectionPeriodFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $election_date
 * @property Carbon|null $registration_starts_at
 * @property Carbon|null $registration_ends_at
 * @property Carbon|null $dps_starts_at
 * @property Carbon|null $dps_ends_at
 * @property string $status
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'election_date',
    'registration_starts_at',
    'registration_ends_at',
    'dps_starts_at',
    'dps_ends_at',
    'status',
    'is_active',
])]
class ElectionPeriod extends Model
{
    /** @use HasFactory<ElectionPeriodFactory> */
    use HasFactory, LogsActivity;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_CLOSED = 'closed';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'election_date' => 'date',
            'registration_starts_at' => 'datetime',
            'registration_ends_at' => 'datetime',
            'dps_starts_at' => 'datetime',
            'dps_ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Candidate, $this>
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    /**
     * @return HasMany<Voter, $this>
     */
    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class);
    }

    /**
     * @return HasMany<VoterImportBatch, $this>
     */
    public function voterImportBatches(): HasMany
    {
        return $this->hasMany(VoterImportBatch::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
