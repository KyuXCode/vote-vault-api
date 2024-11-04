<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryUnit extends Model
{
    Use HasFactory;

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class);
    }

    protected $guarded = [];
}
