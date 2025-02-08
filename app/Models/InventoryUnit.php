<?php

namespace App\Models;

use App\Helpers\ConditionType;
use App\Helpers\UsageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryUnit extends Model
{
    protected $fillable = [
        'serial_number',
        'acquisition_date',
        'condition',
        'usage',
        'expense_id',
        'component_id'
    ];
    Use HasFactory;
    protected $guarded = [];

    protected $casts = [
        self::condition => ConditionType::class,
        self::usage => UsageType::class,
    ];

    public const condition = 'condition';
    public const usage = 'usage';

    public function dispositions(): BelongsTo
    {
        return $this->belongsTo(Disposition::class);
    }

    public function storage_locations(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }
}
