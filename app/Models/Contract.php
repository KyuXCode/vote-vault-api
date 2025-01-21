<?php

namespace App\Models;

use App\Helpers\ContractType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    Use HasFactory;
    protected $guarded = [];
    protected $casts = [
        self::type => ContractType::class,
    ];

    public const type = 'type';

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
