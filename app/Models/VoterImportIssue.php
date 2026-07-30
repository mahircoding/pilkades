<?php

namespace App\Models;

use Database\Factories\VoterImportIssueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $voter_import_batch_id
 * @property int $row_number
 * @property string|null $nik_masked
 * @property string $type
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'voter_import_batch_id',
    'row_number',
    'nik_masked',
    'type',
    'message',
])]
class VoterImportIssue extends Model
{
    /** @use HasFactory<VoterImportIssueFactory> */
    use HasFactory;

    public const TYPE_DUPLICATE_FILE = 'duplicate_file';

    public const TYPE_DUPLICATE_DB = 'duplicate_db';

    public const TYPE_INVALID = 'invalid';

    /**
     * @return BelongsTo<VoterImportBatch, $this>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(VoterImportBatch::class, 'voter_import_batch_id');
    }
}
