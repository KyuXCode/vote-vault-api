<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certification extends Model
{
    Use HasFactory;

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }
    //Fields that can't be assigned
    protected $guarded = [];
}
