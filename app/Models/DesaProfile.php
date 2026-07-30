<?php

namespace App\Models;

use Database\Factories\DesaProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property string|null $district
 * @property string|null $regency
 * @property string|null $province
 * @property string|null $postal_code
 * @property string|null $address
 * @property string|null $head_name
 * @property string|null $logo_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'district', 'regency', 'province', 'postal_code', 'address', 'head_name', 'logo_path'])]
class DesaProfile extends Model
{
    /** @use HasFactory<DesaProfileFactory> */
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
