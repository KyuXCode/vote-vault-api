<?php

namespace App\Models;

use App\Helpers\ComponentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'certification_id'
    ];
    protected $guarded = [];
    protected $casts = [
        self::type => ComponentType::class
    ];

    public const type = 'type';

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }
    public function inventoryUnits(): HasMany
    {
        return $this->hasMany(InventoryUnit::class);
    }
}
