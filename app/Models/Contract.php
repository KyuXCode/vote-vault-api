<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    Use HasFactory;

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }

    protected $guarded = [];
}
