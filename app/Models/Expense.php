<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    Use HasFactory;

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    protected $guarded = [];
}
