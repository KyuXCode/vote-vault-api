<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryUnit extends Model
{
    Use HasFactory;

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

    protected $guarded = [];
}
